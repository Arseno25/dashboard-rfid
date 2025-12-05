<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\States\OrderStatus\Success as OrderSuccess;
use App\Models\States\Status\Inactive;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    public function prosesTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'qty' => 'required|numeric',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error_response  = $this->generateErrorResponse("ID Belum Terdaftar atau Jumlah Barang Tidak Valid");
            return response()->json($error_response);
        }

        $uid = $request->input('uid');
        $qty_barang = $request->input('qty');

        // Memastikan input yang valid
        $validationResult = $this->validateInput($uid, $qty_barang);
        if (!$validationResult['success']) {
            return response()->json($validationResult['response']);
        }

        // Mendapatkan data pengguna
        $user = Customer::where('uid', $uid)->first();
        if (!$user || $user->status == Inactive::$name) {
            $error_response = $this->generateErrorResponse("ID Belum Terdaftar");
            return response()->json($error_response);
        }



        // Mendapatkan data produk
        $product = Product::find($request->input('product_id'));
        if (!$product || $product->is_enabled == 0) {
            $error_response = $this->generateErrorResponse("Produk Tidak Ditemukan");
            return response()->json($error_response);
        }
        // Memproses transaksi
        return $this->processTransaction($user, $product, $qty_barang);
    }

    private function validateInput($uid, $qty_barang )
    {
        // Memastikan input yang valid
        if (!$uid || !is_numeric($qty_barang)) {
            return [
                'success' => false,
                'response' => ['message' => 'ID Belum Terdaftar atau Jumlah Barang Tidak Valid'],
            ];
        }

        return ['success' => true];
    }

    private function generateErrorResponse($status)
    {
        $errorResponse = [
            "Detail" => [
                "Status" => $status,
            ],
        ];

        $user = Customer::all()->first();

        Notification::make()
            ->title('Transaksi Gagal')
            ->danger()
            ->body('Terjadi kesalahan saat melakukan transaksi dengan pesan: ' . $errorResponse['Detail']['Status'])
            ->actions([
                Action::make('Read')
                    ->markAsRead(),
            ])
            ->send()
            ->sendToDatabase(User::where('is_admin', 1)->get());

        return $errorResponse;
    }

    private function processTransaction($user, $product, $qty_barang)
    {
        DB::beginTransaction();

        try {
            // Pemeriksaan keberadaan diskon aktif
            $discount = Discount::where('status', 'active')->first();
            $discount_amount = 0;

            if ($discount) {
                $discount_amount = ($product->price * $qty_barang) * ($discount->percentage / 100);
            }

            if ($product->stock <= 0 || !$product->is_enabled) {
                DB::rollback();
                $error_response = $this->generateErrorResponse("Produk Tidak Tersedia");
                $this->saveOrder($user, $product, $qty_barang, 'failed', $discount_amount, $error_response);
                return response()->json($error_response);
            }

            if ($qty_barang > $product->stock) {
                DB::rollback();
                $error_response = $this->generateErrorResponse("Jumlah melebihi stok");
                $this->saveOrder($user, $product, $qty_barang, 'failed', $discount_amount, $error_response);
                return response()->json($error_response);
            }

            $product->decrement('stock', $qty_barang);

            // Update saldo user
            $saldo_setelah_transaksi = $user->balance - ($product->price * $qty_barang);

            if ($saldo_setelah_transaksi < 0) {
                DB::rollback();
                $error_response = $this->generateErrorResponse("Saldo Tidak Cukup");
                $this->saveOrder($user, $product, $qty_barang, 'failed', $discount_amount, $error_response);
                return response()->json($error_response);
            }

            // Potong saldo dengan atau tanpa diskon
            $user->balance = $saldo_setelah_transaksi - $discount_amount;
            $user->save();

            if ($product->stock <= 0) {
                $product->update(['is_enabled' => 0]);
            }

            // Simpan data transaksi ke dalam tabel dengan status 'success'
            $this->saveOrder($user, $product, $qty_barang, 'success', $discount_amount, $error_response = null);

            // Discount
            if ($discount) {
                $user->balance -= $discount_amount;
                $user->save();
            }

            DB::commit();

            // Kirim notifikasi
            Notification::make()
                ->title('Transaksi Berhasil')
                ->success()
                ->body($user->name . ' Telah melakukan transaksi untuk produk '. $product->name . ' dengan jumlah pembelian ' . $qty_barang . ' Pcs'. ' total yang dibayarkan sebesar Rp.' . $product->price * $qty_barang - $discount_amount. '.')
                ->actions([
                    Action::make('Read')
                        ->markAsRead(),
                ])
                ->send()
                ->sendToDatabase(User::where('is_admin', 1)->get());

            return response()->json([
                "Detail" => [
                    "Status" => "Transaksi Sukses",
                    "Data User" => $user,
                    "Diskon" => $discount ? $discount->percentage . '%' : 'Tidak ada diskon',
                    "Total Harga" => 'Rp.' . ($product->price * $qty_barang),
                    "Total Diskon" => 'Rp.' . $discount_amount,
                    "Total Bayar" => 'Rp.' . ($product->price * $qty_barang - $discount_amount),
                    "Saldo Akhir" => 'Rp.' . (int)$user->balance,
                    "Total Poin" => (int)$user->point . ' poin',
                ],
            ]);
        } catch (QueryException $e) {
            DB::rollback();
            // Simpan data transaksi ke dalam tabel dengan status 'failed'
            $error_response = $this->generateErrorResponse("Terjadi Kesalahan");
            $this->saveOrder($user, $product, $qty_barang, 'failed', $discount_amount, $error_response);
            return response()->json($error_response);
        }
    }

    private function saveOrder($user, $product, $qty_barang, $status, $discount_amount, $error_response = null)
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber('RFID'),
            'customer_id' => $user->id,
            'product_id' => $product->id,
            'buyer_name' => $user->name,
            'buyer_phone' => $user->phone,
            'rfid_uid' => $user->uid,
            'quantity' => $qty_barang,
            'status' => $status,
            'payment_method' => 'rfid',
            'payment_status' => $status === OrderSuccess::$name ? 'paid' : 'failed',
            'price' => $product->price,
            'price_before_discount' => $product->price * $qty_barang,
            'response' => $error_response ? json_encode($error_response['Detail']['Status']) : null,
            'discount_amount' => $discount_amount ?: 0,
            'total' => $product->price * $qty_barang - $discount_amount,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => $qty_barang,
            'subtotal' => ($product->price * $qty_barang) - $discount_amount,
        ]);

        return $order;
    }
}
