@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))

@section('meta')
<meta name="description" content="Temukan berbagai produk {{ $category->name }} di {{ config('app.name') }}. {{ Str::limit($category->description, 150) }}">
<meta name="keywords" content="{{ $category->name }}, produk, {{ config('app.name') }}">
<meta name="robots" content="index, follow">
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row align-items-center mb-4">
        <div class="col-auto">
            <div class="category-icon bg-primary bg-opacity-10 text-primary rounded p-3">
                <i class="bi bi-{{ $category->icon ?? 'tag' }} display-6"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="h2 mb-2">{{ $category->name }}</h1>
            <p class="text-muted mb-0">{{ $category->description }}</p>
        </div>
    </div>

    <!-- Filter & Sort -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('categories.show', $category) }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Produk</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Nama produk..."
                           value="{{ request('search') }}"
                           aria-label="Cari produk">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>Stok Tersedia</option>
                        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
            <h3>Tidak ada produk</h3>
            <p class="text-muted">Belum ada produk dalam kategori ini</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Lihat Semua Produk
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}"
                                     alt="{{ $product->name }} - Gambar Produk"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="ratio ratio-1x1 bg-light">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                            @endif

                            <!-- Product Labels -->
                            <div class="position-absolute top-0 start-0 p-2">
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="badge bg-warning">Stok Terbatas</span>
                                @elseif($product->stock == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                <h5 class="card-title text-dark">{{ $product->name }}</h5>
                            </a>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.category-icon {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-card {
    transition: transform .2s;
}

.product-card:hover {
    transform: translateY(-5px);
}

@media (max-width: 767.98px) {
    .product-card .card-title {
        font-size: 1rem;
    }
    .product-card .h5 {
        font-size: 1rem;
    }
}
</style>
@endpush
