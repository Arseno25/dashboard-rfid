<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function show(Request $request, Order $order, InvoiceService $invoiceService)
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403);
        }

        $order->loadMissing('items');

        $path = $order->invoice_path;

        if (!$path || !Storage::disk('local')->exists($path)) {
            $path = $invoiceService->generate($order);
            $order->forceFill(['invoice_path' => $path])->save();
        }

        $absolutePath = Storage::disk('local')->path($path);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';
        $filename = sprintf('%s.pdf', $order->order_number);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
        ]);
    }
}
