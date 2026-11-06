@extends('layouts.admin')

@section('title', 'Laporan Penjualan - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Laporan Penjualan</h1>
            <p class="text-muted mb-0">Ringkasan performa penjualan dan statistik produk</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.sales') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Laporan (CSV)
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pendapatan (Selesai)</div>
                    <h4 class="text-success mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pesanan</div>
                    <h4 class="text-primary mb-0">{{ number_format($totalOrders) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pesanan Selesai</div>
                    <h4 class="text-info mb-0">{{ number_format($completedOrdersCount) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pesanan Menunggu</div>
                    <h4 class="text-warning mb-0">{{ number_format($pendingOrdersCount) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Produk Terlaris -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy text-warning me-2"></i>Produk Terlaris
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Terjual (Item)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produkTerlaris as $index => $produk)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $produk->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $produk->category ? $produk->category->name : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ $produk->order_items_count }}x
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data penjualan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Terakhir -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history text-primary me-2"></i>Pesanan Terakhir
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                        <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada pesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
