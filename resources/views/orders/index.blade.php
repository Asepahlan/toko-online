@extends('layouts.app')

@section('title', 'Pesanan Saya - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Daftar Pesanan Saya</h1>
            <p class="text-muted small mb-0">Pantau status pembayaran dan pengiriman barang belanjaan Anda</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">
            <i class="bi bi-cart-plus me-1"></i> Belanja Lagi
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 16px;">
            <div class="card-body py-5">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center p-4 mb-3 text-muted">
                    <i class="bi bi-bag-x display-3"></i>
                </div>
                <h4 class="fw-bold mb-2">Belum Ada Riwayat Pesanan</h4>
                <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan di {{ config('app.name') }}.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-grid me-1"></i> Jelajahi Produk Kami
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No. Invoice / ID</th>
                            <th>Tanggal Pesanan</th>
                            <th>Total Tagihan</th>
                            <th>Status Pesanan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark d-block">
                                        {{ $order->invoice_number ?: '#' . $order->id }}
                                    </strong>
                                    <small class="text-muted">Order ID: #{{ $order->id }}</small>
                                </td>
                                <td>
                                    <span>{{ $order->created_at->format('d M Y') }}</span>
                                    <small class="text-muted d-block">{{ $order->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td>
                                    <strong class="text-primary">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                            <i class="bi bi-clock me-1"></i> Menunggu Konfirmasi
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                            <i class="bi bi-box-seam me-1"></i> Sedang Diproses
                                        </span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">
                                            <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> Rincian
                                        </a>
                                        @if($order->status === 'pending')
                                            <a href="{{ route('orders.resend.confirmation', $order->id) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-success"
                                               title="Konfirmasi ulang via WhatsApp">
                                                <i class="bi bi-whatsapp me-1"></i> Konfirmasi WA
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($orders, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
