@php
    $total = formatCurrency($order->total ?? 0, 'Rp. ', 0);
@endphp

<x-mail::message>
# Terima kasih, {{ $order->buyer_name ?? 'Pelanggan' }}

Pembayaran untuk pesanan **{{ $order->order_number }}** telah kami terima. Terlampir invoice resmi dalam format PDF untuk arsip Anda.

<x-mail::panel>
**Tanggal:** {{ optional($order->updated_at)->format('d M Y H:i') ?? now()->format('d M Y H:i') }}

**Total:** {{ $total }}

**Status:** {{ ucfirst($order->payment_status) }}
</x-mail::panel>

Silakan hubungi tim Zarly Petshop apabila membutuhkan bantuan lanjutan.

Salam hangat,

**{{ config('app.name', 'Zarly Petshop') }}**
</x-mail::message>
