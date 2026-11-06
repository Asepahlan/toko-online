@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', $product->category) }}" class="text-decoration-none">
                        {{ $product->category->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 200px;">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail Container -->
    <div class="card border-0 shadow-sm p-3 p-md-4 mb-5" style="border-radius: 16px;">
        <div class="row g-4">
            <!-- Product Image -->
            <div class="col-md-5">
                <div class="rounded-3 overflow-hidden border bg-light position-relative" style="min-height: 350px;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="w-100 h-100 object-fit-cover"
                             alt="{{ $product->name }}">
                    @elseif($product->image_url)
                        <img src="{{ $product->image_url }}"
                             class="w-100 h-100 object-fit-cover"
                             alt="{{ $product->name }}">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="min-height: 350px;">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif

                    @if($product->stock <= 0)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2 fs-6">Stok Habis</span>
                    @endif
                </div>
            </div>

            <!-- Product Info & Actions -->
            <div class="col-md-7 d-flex flex-column">
                <div class="mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold">
                        {{ $product->category->name ?? 'Kategori Umum' }}
                    </span>
                </div>

                <h1 class="h2 fw-bold text-dark mb-2">{{ $product->name }}</h1>

                <!-- Price and Stock -->
                <div class="bg-light p-3 rounded-3 mb-4">
                    <div class="d-flex align-items-baseline gap-3">
                        <span class="h2 fw-bold text-primary mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="text-muted small">/ pcs</span>
                    </div>
                    <div class="mt-2 d-flex align-items-center gap-3">
                        <span class="small">
                            Status Stok:
                            @if($product->stock > 0)
                                <strong class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Tersedia ({{ $product->stock }} unit)</strong>
                            @else
                                <strong class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Habis</strong>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4 flex-grow-1">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Produk</h6>
                    <p class="text-secondary lh-base mb-0" style="white-space: pre-line;">
                        {{ $product->description ?: 'Tidak ada deskripsi rinci untuk produk ini.' }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="pt-3 border-top">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex flex-wrap gap-2">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-4 flex-grow-1 shadow-sm">
                                <i class="bi bi-cart-plus me-2"></i> Tambahkan ke Keranjang
                            </button>
                            <a href="https://wa.me/6285215142110?text=Halo%20Admin,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}"
                               target="_blank" rel="noopener"
                               class="btn btn-outline-success btn-lg px-3"
                               title="Tanya CS via WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="bi bi-slash-circle me-2"></i> Produk Sedang Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-5">
            <h4 class="fw-bold mb-3">Produk Terkait Lainnya</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <div style="height: 160px; background: #f8fafc;">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $related->name }}">
                                @elseif($related->image_url)
                                    <img src="{{ $related->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $related->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image fs-2"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-2 p-md-3 d-flex flex-column">
                                <h6 class="fw-bold mb-1 text-truncate">
                                    <a href="{{ route('products.show', $related->slug) }}" class="text-dark text-decoration-none">
                                        {{ $related->name }}
                                    </a>
                                </h6>
                                <span class="fw-bold text-primary small mb-2">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $related->slug) }}" class="btn btn-sm btn-outline-primary mt-auto">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
