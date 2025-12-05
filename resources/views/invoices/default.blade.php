@php
    $total = formatCurrency($order->total ?? 0, 'Rp. ', 0);
    $siteName = $siteSettings['name'] ?? config('app.name', 'Zarly Petshop');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; color: #0f172a; margin: 0; padding: 32px; font-size: 12px; }
        .invoice { max-width: 760px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 20px; padding: 32px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .brand { font-size: 20px; font-weight: 700; }
        .meta { text-align: right; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; }
        .badge-paid { background: #d1fae5; color: #047857; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #475569; }
        .totals { margin-top: 24px; width: 100%; }
        .totals td { border: none; padding: 6px 0; }
        .totals .label { color: #475569; }
        .totals .value { font-weight: 600; text-align: right; }
        .footer { margin-top: 32px; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div>
                <div class="brand">{{ $siteName }}</div>
                <p style="margin:4px 0 0; color:#475569;">Invoice resmi</p>
            </div>
            <div class="meta">
                <div style="font-size:24px; font-weight:700;">Invoice</div>
                <p style="margin:4px 0 0;">#{{ $order->order_number }}</p>
                <div style="margin-top:8px;">
                    <span class="badge badge-paid">{{ strtoupper($order->payment_status ?? 'pending') }}</span>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:32px; margin-bottom:16px;">
            <div style="flex:1;">
                <p style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#94a3b8;">Diterbitkan untuk</p>
                <p style="margin:6px 0 2px; font-weight:600;">{{ $order->buyer_name }}</p>
                <p style="margin:0; color:#475569;">{{ $order->buyer_email }}</p>
                <p style="margin:0; color:#475569;">{{ $order->buyer_phone }}</p>
            </div>
            <div style="flex:1;">
                <p style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#94a3b8;">Detail invoice</p>
                <p style="margin:6px 0 2px;">Tanggal: <strong>{{ optional($order->updated_at ?? $order->created_at)->format('d M Y') }}</strong></p>
                <p style="margin:0;">Metode: <strong>{{ ucfirst($order->payment_method ?? '—') }}</strong></p>
                @if($order->buyer_note)
                    <p style="margin:0;">Catatan: <strong>{{ $order->buyer_note }}</strong></p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ formatCurrency($item->price ?? 0, 'Rp. ', 0) }}</td>
                        <td>{{ formatCurrency($item->subtotal ?? 0, 'Rp. ', 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">{{ formatCurrency($order->price_before_discount ?? 0, 'Rp. ', 0) }}</td>
            </tr>
            <tr>
                <td class="label">Diskon</td>
                <td class="value">-{{ formatCurrency($order->discount_amount ?? 0, 'Rp. ', 0) }}</td>
            </tr>
            <tr>
                <td class="label" style="font-size:14px;">Total</td>
                <td class="value" style="font-size:16px;">{{ $total }}</td>
            </tr>
        </table>

        <div class="footer">
            Invoice ini sah tanpa tanda tangan basah. Simpan dokumen ini sebagai bukti pembayaran resmi.
        </div>
    </div>
</body>
</html>
