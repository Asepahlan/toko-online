@extends('layouts.app')

@section('title', 'Kategori: ' . $category->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}" class="text-decoration-none">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Header Kategori -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                <i class="bi bi-{{ $category->icon ?? 'tag' }} fs-3"></i>
            </div>
            <div>
                <h1 class="h3 fw-bold mb-1">{{ $category->name }}</h1>
                <p class="text-muted small mb-0">{{ $category->description ?: 'Menampilkan seluruh produk dalam kategori ' . $category->name }}</p>
            </div>
        </div>
    </div>

    <!-- Grid Produk Kategori -->
    <div class="row g-4 mb-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border shadow-sm" style="border-radius: 14px; overflow: hidden;">
                    <div class="position-relative" style="height: 190px; background-color: #f8fafc;">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                        @elseif($product->image_url)
                            <img src="{{ $product->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif

                        @if($product->stock <= 0)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Habis</span>
                        @endif
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold mb-2 text-truncate" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
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
                <h4 class="fw-bold text-dark">Belum Ada Produk di Kategori Ini</h4>
                <p class="text-muted">Koleksi untuk kategori {{ $category->name }} akan segera diperbarui.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-grid me-1"></i> Lihat Semua Produk
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
