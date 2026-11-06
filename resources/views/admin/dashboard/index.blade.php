@extends('layouts.admin')

@section('title', 'Dashboard Admin - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Dashboard</h1>
            <p class="text-muted">Berikut adalah ringkasan data toko Anda</p>
        </div>
        <div class="text-end">
            <p class="mb-0">{{ now()->format('l, d F Y') }}</p>
            <small class="text-muted">{{ now()->format('H:i') }} WIB</small>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Pendapatan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Pendapatan</div>
                            <h3 class="mb-0">Rp {{ number_format($totalRevenue) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-wallet2 text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-0">
                        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                            Lihat Detail <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Pesanan</div>
                            <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-cart-check text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-0">
                        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                            Lihat Detail <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Produk</div>
                            <h3 class="mb-0">{{ number_format($totalProducts) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-box-seam text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-0">
                        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                            Lihat Detail <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Pengguna</div>
                            <h3 class="mb-0">{{ number_format($totalUsers) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-0">
                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                            Lihat Detail <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Grafik Penjualan -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">Grafik Penjualan</h5>
                            <p class="text-muted small mb-0">Data penjualan tahun {{ date('Y') }}</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.reports.sales') }}">
                                        <i class="bi bi-download me-2"></i>Export Data
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Pesanan Terbaru -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0">Pesanan Terbaru</h5>
                            <p class="text-muted small mb-0">5 pesanan terakhir</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">#{{ $order->id }} - {{ $order->user->name }}</h6>
                                        <div class="d-flex align-items-center text-muted small">
                                            <span class="me-3">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $order->created_at->diffForHumans(null, true, false, 2) }} yang lalu
                                            </span>
                                            <span>
                                                Rp {{ number_format($order->total_amount) }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted display-6 mb-3"></i>
                                <p class="text-muted mb-0">Belum ada pesanan</p>
                            </div>
                        @endforelse
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
    transition: transform .2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
.list-group-item-action:hover {
    background-color: #f8f9fa;
}
.badge {
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d6efd',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    usePointStyle: true,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    },
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Update waktu real-time
    setInterval(() => {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.querySelector('.text-end').innerHTML = `
            <p class="mb-0">${now.toLocaleDateString('id-ID', options)}</p>
            <small class="text-muted">${now.toLocaleTimeString('id-ID')} WIB</small>
        `;
    }, 1000);
</script>
@endpush
