@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id . ' - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan #{{ $order->id }}</li>
        </ol>
    </nav>

    <!-- Header Status Order -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h1 class="h3 fw-bold mb-0">Pesanan #{{ $order->id }}</h1>
                    @if($order->status === 'pending')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Konfirmasi</span>
                    @elseif($order->status === 'processing')
                        <span class="badge bg-info text-white px-3 py-2 rounded-pill">Sedang Diproses</span>
                    @elseif($order->status === 'completed')
                        <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                    @else
                        <span class="badge bg-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                    @endif
                </div>
                <p class="text-muted small mb-0">
                    Nomor Invoice: <strong>{{ $order->invoice_number ?: '-' }}</strong> | Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>

            <div class="d-flex gap-2">
                @if($order->status === 'pending')
                    <a href="{{ route('orders.resend.confirmation', $order->id) }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp me-1"></i> Konfirmasi Pembayaran via WhatsApp
                    </a>
                @endif
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Rincian Produk Pesanan -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-box-seam text-primary me-2"></i>Daftar Barang yang Dipesan
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderItems as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded border overflow-hidden flex-shrink-0" style="width: 50px; height: 50px; background: #f8fafc;">
                                                @php
                                                    $productImage = $item->product->image ?? null;
                                                    $productImageUrl = $item->product->image_url ?? null;
                                                @endphp
                                                @if($productImage)
                                                    <img src="{{ asset('storage/' . $productImage) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product->name ?? 'Produk' }}">
                                                @elseif($productImageUrl)
                                                    <img src="{{ $productImageUrl }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product->name ?? 'Produk' }}">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="d-block text-dark">{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</strong>
                                                <small class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }} pcs</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4 fw-bold text-dark">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada rincian item.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-4">Total Pembayaran:</td>
                                <td class="text-end pe-4 fw-bold text-primary fs-5">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informasi Penerima & Pengiriman -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Data Pengiriman
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Penerima</small>
                        <strong class="text-dark">{{ $order->user->name ?? 'Pelanggan' }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Nomor Telepon / WhatsApp</small>
                        <strong class="text-dark">{{ $order->contact_number ?: ($order->user->phone ?? '-') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat Pengiriman</small>
                        <p class="text-dark mb-0">{{ $order->shipping_address ?: ($order->user->address ?? 'Alamat belum diatur.') }}</p>
                    </div>

                    @if($order->status === 'pending')
                        <hr>
                        <div class="alert alert-warning small mb-0">
                            <i class="bi bi-info-circle-fill me-1"></i> Pesanan Anda menunggu konfirmasi penjual. Silakan klik tombol hijau di atas untuk menghubungi admin via WhatsApp agar pesanan segera dikirim.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
