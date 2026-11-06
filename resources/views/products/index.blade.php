@extends('layouts.app')

@section('title', 'Katalog Produk - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Header Breadcrumb & Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Katalog Produk</h1>
            <p class="text-muted small mb-0">Temukan barang berkualitas sesuai dengan kebutuhan Anda</p>
        </div>
        <div class="mt-2 mt-md-0">
            <span class="badge bg-light text-dark border px-3 py-2">
                Total: <strong>{{ $products->total() }}</strong> Produk Ditemukan
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body p-3">
            <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search input -->
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 ps-0"
                               name="search"
                               placeholder="Cari nama produk yang Anda inginkan..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category filter -->
                <div class="col-md-3">
                    <select class="form-select" name="category" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4 mb-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border shadow-sm product-card" style="border-radius: 14px; overflow: hidden;">
                    <!-- Image -->
                    <div class="position-relative" style="height: 200px; background-color: #f8fafc;">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-100 h-100 object-fit-cover"
                                 alt="{{ $product->name }}">
                        @elseif($product->image_url)
                            <img src="{{ $product->image_url }}"
                                 class="w-100 h-100 object-fit-cover"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif

                        @if($product->stock <= 0)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Habis</span>
                        @else
                            <span class="badge bg-success bg-opacity-90 position-absolute top-0 end-0 m-2">Stok: {{ $product->stock }}</span>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="card-body p-3 d-flex flex-column">
                        <small class="text-muted mb-1 text-truncate">
                            <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'Umum' }}
                        </small>
                        <h6 class="fw-bold mb-2 text-truncate" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.725rem;">Harga</small>
                                <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary px-3">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold text-dark">Tidak Ada Produk Ditemukan</h4>
                <p class="text-muted">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Pencarian
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
