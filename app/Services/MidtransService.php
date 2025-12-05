<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Arr;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * @return array{token: string, redirect_url: string, payload: array}
     */
    public function createTransaction(Order $order, array $customerDetails = []): array
    {
        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->product_id ?? $item->id,
                'price' => max(0, $item->price),
                'quantity' => max(1, $item->quantity),
                'name' => $item->product_name,
            ];
        })->toArray();

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => max(0, $order->total),
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $customerDetails['name'] ?? $order->buyer_name,
                'email' => $customerDetails['email'] ?? $order->buyer_email,
                'phone' => $customerDetails['phone'] ?? $order->buyer_phone,
            ],
        ];

        $transaction = Snap::createTransaction($params);
        $token = data_get($transaction, 'token');
        $redirectUrl = data_get($transaction, 'redirect_url');

        return [
            'token' => $token,
            'redirect_url' => $redirectUrl,
            'payload' => $params,
        ];
    }

    public function validateSignature(array $payload): bool
    {
        $signature = Arr::get($payload, 'signature_key');
        $orderId = Arr::get($payload, 'order_id');
        $statusCode = Arr::get($payload, 'status_code');
        $grossAmount = Arr::get($payload, 'gross_amount');
        $serverKey = config('services.midtrans.server_key');

        if (!$signature || !$serverKey) {
            return false;
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signature);
    }

    public function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus = null): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'pending' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'cancel', 'deny', 'expire' => 'failed',
            default => 'failed',
        };
    }
}
