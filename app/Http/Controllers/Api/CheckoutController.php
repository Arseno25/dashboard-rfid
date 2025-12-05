<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\States\OrderStatus\Failed as OrderFailed;
use App\Models\States\OrderStatus\Success as OrderSuccess;
use App\Services\InvoiceService;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtrans,
        private readonly InvoiceService $invoiceService
    )
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'method' => ['required', 'in:midtrans'],
            'customer.note' => ['nullable', 'string', 'max:255'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if (!$user->phone) {
            throw ValidationException::withMessages([
                'phone' => 'Lengkapi nomor telepon pada profil sebelum checkout.',
            ]);
        }

        if (empty($data['cart'])) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang tidak boleh kosong.',
            ]);
        }

        $discount = Discount::where('status', 'active')->first();
        $discountPercentage = $discount?->percentage ?? 0;

        $order = DB::transaction(function () use ($data, $discountPercentage, $user) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber('INV'),
                'customer_id' => null,
                'product_id' => null,
                'user_id' => $user->id,
                'buyer_name' => $user->name,
                'buyer_email' => $user->email,
                'buyer_phone' => $user->phone,
                'buyer_note' => data_get($data, 'customer.note'),
                'status' => 'pending',
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'quantity' => 0,
                'price' => 0,
                'price_before_discount' => 0,
                'discount_amount' => 0,
                'total' => 0,
            ]);

            $cartQuantity = 0;
            $totalBeforeDiscount = 0;
            $totalDiscount = 0;
            $totalAfterDiscount = 0;

            foreach ($data['cart'] as $item) {
                $product = Product::find($item['id']);

                if (!$product || $product->is_enabled === 0) {
                    throw ValidationException::withMessages([
                        'cart' => sprintf('Produk dengan ID %s tidak tersedia.', $item['id']),
                    ]);
                }

                $quantity = max(1, (int) $item['quantity']);

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => sprintf('Stok %s tidak mencukupi.', $product->name),
                    ]);
                }

                $cartQuantity += $quantity;
                $basePrice = $product->price;
                $subtotalBefore = $basePrice * $quantity;
                $discountAmount = $discountPercentage > 0
                    ? (int) round($subtotalBefore * ($discountPercentage / 100))
                    : 0;
                $subtotalAfter = $subtotalBefore - $discountAmount;

                $totalBeforeDiscount += $subtotalBefore;
                $totalDiscount += $discountAmount;
                $totalAfterDiscount += $subtotalAfter;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $basePrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotalAfter,
                ]);
            }

            $order->update([
                'quantity' => $cartQuantity,
                'price' => $totalAfterDiscount,
                'price_before_discount' => $totalBeforeDiscount,
                'discount_amount' => $totalDiscount,
                'total' => $totalAfterDiscount,
            ]);

            return $order->fresh('items');
        });

        $midtrans = $this->midtrans->createTransaction($order, [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        $order->update([
            'gateway_reference' => $midtrans['token'],
            'gateway_payload' => $midtrans['payload'],
        ]);

        return response()->json([
            'order' => $order->fresh('items'),
            'snap_token' => $midtrans['token'],
            'redirect_url' => $midtrans['redirect_url'],
        ], 201);
    }

    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (!$this->midtrans->validateSignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', Arr::get($payload, 'order_id'))
            ->with(['items.product'])
            ->firstOrFail();

        $paymentStatus = $this->midtrans->mapTransactionStatus(
            Arr::get($payload, 'transaction_status', 'pending'),
            Arr::get($payload, 'fraud_status')
        );

        $updates = [
            'payment_status' => $paymentStatus,
            'gateway_reference' => Arr::get($payload, 'transaction_id', $order->gateway_reference),
        ];

        $gatewayPayload = $order->gateway_payload ?? [];
        $gatewayPayload['last_notification'] = $payload;

        if ($paymentStatus === 'paid') {
            $updates['status'] = OrderSuccess::$name;
            $this->finalizeOrder($order);
            $this->dispatchInvoice($order);
            $gatewayPayload['finalized_at'] = now()->toIso8601String();
        } elseif ($paymentStatus === 'failed') {
            $updates['status'] = OrderFailed::$name ?? 'failed';
        } else {
            $updates['status'] = 'pending';
        }

        $updates['gateway_payload'] = $gatewayPayload;

        $order->update($updates);

        return response()->json(['message' => 'ok']);
    }

    public function status(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'total' => $order->total,
        ]);
    }

    protected function finalizeOrder(Order $order): void
    {
        if (Arr::get($order->gateway_payload, 'finalized_at')) {
            return;
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);

                if ($item->product->stock <= 0) {
                    $item->product->update(['is_enabled' => 0]);
                }
            }
        }
    }

    protected function dispatchInvoice(Order $order): void
    {
        if (!$order->buyer_email || $order->invoice_sent_at) {
            return;
        }

        $order->loadMissing('items');

        $invoicePath = $order->invoice_path;

        if (blank($invoicePath)) {
            $invoicePath = $this->invoiceService->generate($order);
        }

        Mail::to($order->buyer_email)->send(new InvoiceMail($order, $invoicePath));

        $order->forceFill([
            'invoice_path' => $invoicePath,
            'invoice_sent_at' => now(),
        ])->save();
    }
}
