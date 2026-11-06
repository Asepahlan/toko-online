@extends('layouts.app')

@section('title', 'Kategori Produk - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Kategori Produk</h1>
            <p class="text-muted small mb-0">Temukan barang yang Anda cari berdasarkan kelompok kategori produk</p>
        </div>
        <div class="mt-2 mt-md-0">
            <span class="badge bg-light text-dark border px-3 py-2">
                {{ method_exists($categories, 'total') ? $categories->total() : $categories->count() }} Kategori Tersedia
            </span>
        </div>
    </div>

    <!-- Grid Kategori -->
    <div class="row g-4 mb-4">
        @forelse($categories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                    <div class="card h-100 border text-center p-4 category-box" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 68px; height: 68px;">
                            <i class="bi bi-{{ $category->icon ?? 'tag' }} fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $category->name }}</h5>
                        <p class="text-muted small mb-3 text-truncate">
                            {{ $category->description ?: 'Koleksi pilihan kategori ' . $category->name }}
                        </p>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small mt-auto">
                            {{ $category->products_count }} Produk
                        </span>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-tags display-1 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold text-dark">Belum Ada Kategori</h4>
                <p class="text-muted">Kategori produk akan segera hadir.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
</div>

<style>
    .category-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.06);
        border-color: #3b82f6 !important;
    }
</style>
@endsection
