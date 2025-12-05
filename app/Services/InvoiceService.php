<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Order $order): string
    {
        $order->loadMissing('items');

        $pdf = Pdf::loadView('invoices.default', [
            'order' => $order,
        ]);

        $directory = 'invoices';
        $filename = sprintf('%s.pdf', $order->order_number);
        $path = $directory . '/' . $filename;

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }
}
