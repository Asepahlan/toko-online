@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan - ' . config('app.name'))

@section('meta')
<meta name="description" content="Konfirmasi pesanan Anda di {{ config('app.name') }}">
<meta name="robots" content="index, follow"> <!-- Mengubah menjadi index, follow untuk SEO -->
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Alert -->
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill display-6 me-3"></i>
                </div>
                <div>
                    <h4 class="alert-heading mb-2">Pesanan Berhasil Dibuat!</h4>
                    <p class="mb-0">Nomor Invoice: <strong>{{ $order->invoice_number }}</strong></p>
                    <hr>
                    <p class="mb-0">
                        Silakan lakukan pembayaran dan konfirmasi melalui WhatsApp dengan klik tombol di bawah ini.
                        <br>
                        <small class="text-muted">
                            Pesanan akan otomatis dibatalkan jika tidak ada konfirmasi pembayaran dalam 24 jam.
                        </small>
                    </p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                        <span class="badge bg-primary">{{ $order->invoice_number }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="mb-3">Informasi Pengiriman</h6>
                                <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                                <p class="mb-0">{{ $order->contact_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="mb-3">Informasi Pesanan</h6>
                                <p class="mb-1">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1">Status: <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></p>
                                @if($order->notes)
                                    <p class="mb-0">Catatan: {{ $order->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                    <img src="{{ Storage::url($item->product->image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="rounded me-3"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-secondary"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ $item->quantity }}x @ Rp {{ number_format($item->price) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-medium">Rp {{ number_format($item->price * $item->quantity) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="text-end"><strong>Total</strong></td>
                                    <td class="text-end">
                                        <strong class="text-primary">Rp {{ number_format($order->total_amount) }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-3">
                <a href="{{ $whatsappUrl }}" class="btn btn-success btn-lg" target="_blank" rel="noopener noreferrer"> <!-- Menambahkan rel untuk keamanan -->
                    <i class="bi bi-whatsapp me-2"></i>Konfirmasi via WhatsApp
                </a>
                <div class="row g-2">
                    <div class="col">
                        <a href="{{ route('orders.track', $order) }}" class="btn btn-primary w-100">
                            <i class="bi bi-truck me-2"></i>Lacak Pesanan
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('products') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-cart me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media (max-width: 767.98px) {
    .alert i.display-6 {
        font-size: 2rem !important;
    }
}
</style>
@endpush
