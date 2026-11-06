@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('meta')
<meta name="description" content="{{ Str::limit($product->description, 160) }}">
<meta name="keywords" content="{{ $product->name }}, {{ $product->category->name }}, {{ config('app.name') }}">
<meta property="og:title" content="{{ $product->name }} - {{ config('app.name') }}">
<meta property="og:description" content="{{ Str::limit($product->description, 160) }}">
<meta property="og:image" content="{{ asset('storage/' . $product->image) }}">
<meta property="og:url" content="{{ request()->url() }}">
@endsection

@section('content')
<div class="container py-4">
    <!-- Modal untuk Peringatan Login -->
    <div class="modal fade" id="loginAlertModal" tabindex="-1" aria-labelledby="loginAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginAlertModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda harus login untuk menambahkan produk ke keranjang.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gambar Produk -->
        <div class="col-md-6 mb-4">
            <img src="{{ asset('storage/' . $product->image) }}"
            class="w-100 h-100"
            style="object-fit: cover;"
            alt="{{ $product->name }}"
            onerror="this.src='{{ asset('images/default.jpg') }}'">
        </div>

        <!-- Detail Produk -->
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>

            <h1 class="h2 mb-3">{{ $product->name }}</h1>

            <div class="mb-4">
                <h2 class="h4 text-primary mb-3">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </h2>
                <p class="mb-2">
                    <strong>Stok:</strong>
                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->stock }}
                    </span>
                </p>
                <p class="mb-4">
                    <strong>Kategori:</strong>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">
                        {{ $product->category->name }}
                    </a>
                </p>
            </div>

            <div class="mb-4">
                <h3 class="h5 mb-3">Deskripsi</h3>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            @if($product->stock > 0)
                <button class="btn btn-primary add-to-cart" data-id="{{ $product->id }}">
                    <i class="bi bi-cart-plus me-2"></i>
                    Tambah ke Keranjang
                </button>
            @else
                <button class="btn btn-secondary" disabled>
                    <i class="bi bi-x-circle me-2"></i>
                    Stok Habis
                </button>
            @endif
        </div>
    </div>

    <!-- Produk Terkait -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="h4 mb-4">Produk Terkait</h3>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="card product-card h-100">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <img src="{{ asset('storage/' . $related->image) }}"
                                class="w-100 h-100 related-product-image"
                                alt="{{ $related->name }}"
                                onerror="this.src='{{ asset('images/default.jpg') }}'">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title h6">{{ $related->name }}</h5>
                                <p class="card-text text-primary mb-2">
                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                </p>
                                <small class="text-muted">Stok: {{ $related->stock }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function(e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data('id');

        // Cek apakah pengguna sudah login
        @if(Auth::check())
            button.prop('disabled', true);

            $.ajax({
                url: '/cart/add/' + productId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#cart-count').text(response.cartCount);
                        button.html('<i class="bi bi-check me-2"></i>Ditambahkan');
                        setTimeout(function() {
                            button.html('<i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang');
                            button.prop('disabled', false);
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false);
                    let message = 'Gagal menambahkan ke keranjang';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                }
            });
        @else
            // Tampilkan modal jika pengguna belum login
            $('#loginAlertModal').modal('show');
        @endif
    });
});
</script>
@endpush
@endsection

@push('styles')
<style>
.product-card {
    transition: transform .2s;
}
.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.related-product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.row {
    margin: 0;
}

.col-6, .col-md-3 {
    padding: 0;
}

@media (max-width: 767.98px) {
    .product-card .card-title {
        font-size: 1rem;
    }
}
</style>
@endpush
