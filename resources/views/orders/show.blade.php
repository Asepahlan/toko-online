@php
    use Illuminate\Support\Facades\Storage;

    // Format pesan WhatsApp
    $message = urlencode(
        "Halo Admin AhlanBoys, saya ingin konfirmasi pesanan:\n\n" .
        "No. Pesanan: #" . $order->id . "\n" .
        "Nama Penerima: " . auth()->user()->name . "\n" . // Menambahkan nama akun
        "Tanggal Pesanan: " . $order->created_at->format('d F Y H:i') . "\n" .
        "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n" .
        "Mohon diproses, terima kasih."
    );
    $waUrl = "https://wa.me/6285215142110?text=" . $message;
@endphp

@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id . ' - ' . config('app.name'))

@section('meta')
<meta name="description" content="Detail pesanan #{{ $order->id }} di {{ config('app.name') }}. Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}">
<meta name="robots" content="index, follow">
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-muted mb-0">{{ $order->created_at->format('d F Y H:i') }}</p>
                </div>
                <span class="badge bg-{{ $order->status_color }} fs-6">
                    {{ $order->status_label }}
                </span>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px; background-color: {{ '#' . substr(md5($item->product->name), 0, 6) }};">
                                                    <span class="text-white fw-bold">
                                                        {{ strtoupper(substr($item->product->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                    <span class="badge bg-info">{{ $item->product->category->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end pe-4">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end pe-4 fw-bold text-primary">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pengiriman</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Alamat Pengiriman</label>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <p class="mb-0">{{ $order->contact_number }}</p>
                        </div>
                        @if($order->notes)
                            <div class="col-12">
                                <label class="form-label text-muted">Catatan</label>
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>

                        <!-- Tombol WhatsApp -->
                        @if($order->status === 'pending')
                            <a href="{{ $waUrl }}"
                               target="_blank"
                               class="btn btn-success">
                                <i class="bi bi-whatsapp me-2"></i>Konfirmasi via WhatsApp
                            </a>
                        @endif

                        @if($order->status === 'shipped')
                            <form action="{{ route('orders.confirm', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Konfirmasi Terima
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush
