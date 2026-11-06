@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Kategori - ' . config('app.name'))

@section('meta')
<meta name="description" content="Temukan berbagai kategori produk di {{ config('app.name') }}. Pilih kategori yang Anda inginkan.">
<meta name="keywords" content="kategori, produk, {{ config('app.name') }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ request()->url() }}">
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0">Kategori Produk</h1>
                    <p class="text-muted">Pilih kategori yang Anda inginkan</p>
                </div>
                <form class="d-flex gap-2" action="{{ route('categories.index') }}" method="GET" role="search">
                    <input type="search"
                           name="search"
                           class="form-control"
                           placeholder="Cari kategori..."
                           value="{{ request('search') }}"
                           aria-label="Cari kategori">
                    <button type="submit" class="btn btn-primary" aria-label="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Grid Kategori -->
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none" aria-label="Lihat kategori {{ $category->name }}">
                    <div class="card h-100 border-0 shadow-sm category-card">
                        <div class="card-body text-center p-4">
                            <!-- Ikon -->
                            <div class="category-icon mb-3">
                                <i class="bi bi-{{ $category->icon ?? 'tag' }} display-4 text-primary"></i>
                            </div>

                            <!-- Info -->
                            <h5 class="card-title text-dark mb-2">{{ $category->name }}</h5>
                            @if($category->description)
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($category->description, 50) }}
                                </p>
                            @endif

                            <!-- Jumlah Produk -->
                            <div class="d-flex justify-content-center">
                                <span class="badge bg-primary rounded-pill">
                                    <i class="bi bi-box me-1"></i>
                                    {{ $category->products_count }} Produk
                                </span>
                            </div>
                        </div>

                        <!-- Overlay Hover -->
                        <div class="card-overlay">
                            <span class="btn btn-sm btn-light">
                                <i class="bi bi-eye me-1"></i>Lihat Produk
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-tag display-1 text-muted mb-3"></i>
                    <h3>Tidak ada kategori</h3>
                    <p class="text-muted">Belum ada kategori yang tersedia</p>
                </div>
            </div>
            <div class="text-muted small">
                Menampilkan {{ $categories->count() }} kategori
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->count() > 0)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $categories->count() }} kategori
            </div>

            <div class="pagination">
                {{ $categories->links() }}
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-tag display-1 text-muted mb-3"></i>
                <h3>Tidak ada kategori</h3>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.category-card {
    transition: all .3s ease;
    overflow: hidden;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-icon {
    transition: transform .3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .3s ease;
}

.category-card:hover .card-overlay {
    opacity: 1;
}

@media (max-width: 767.98px) {
    .category-card .card-title {
        font-size: 1rem;
    }
    .category-card .display-4 {
        font-size: 2rem;
    }
}
</style>
@endpush
