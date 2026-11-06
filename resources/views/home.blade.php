@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', config('app.name') . ' - Toko Online Terpercaya')

@section('meta')
<meta name="description" content="Belanja online mudah dan aman di {{ config('app.name') }}. Temukan berbagai produk berkualitas dengan harga terbaik.">
<meta name="keywords" content="toko online, belanja online, {{ config('app.name') }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero bg-light py-5" aria-label="Selamat Datang di {{ config('app.name') }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang di {{ config('app.name') }}</h1>
                <p class="lead mb-4">Desain kaosmu sendiri! Buat kaos unik sesuai keinginanmu dengan harga terjangkau. Kualitas terbaik, pengiriman cepat.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg" aria-label="Mulai Belanja">
                        <i class="bi bi-cart me-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="position-relative">
                    <img src="{{ asset('storage/images/asep.png') }}"
                         alt="Gambar Hero Toko Online"
                         class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary rounded-3"
                         style="opacity: 0.1; z-index: -1; transform: translate(20px, 20px);"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories py-5" aria-label="Kategori Produk">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Kategori Produk</h2>
            <p class="text-muted">Pilih kategori produk yang Anda inginkan</p>
        </div>

        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none" aria-label="Kategori {{ $category->name }}">
                        <div class="card h-100 border-0 shadow-sm category-card">
                            <div class="card-body text-center p-4">
                                <div class="category-icon mb-3">
                                    <i class="bi bi-{{ $category->icon ?? 'tag' }} display-4 text-primary"></i>
                                </div>
                                <h5 class="card-title text-dark mb-2">{{ $category->name }}</h5>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $category->products_count }} Produk
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products py-5 bg-light" aria-label="Produk Terlaris">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Produk Terlaris</h2>
            <p class="text-muted">Produk paling diminati oleh pelanggan kami</p>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
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

                        <div class="card-body text-center">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none" aria-label="Detail {{ $product->name }}">
                                <h5 class="card-title text-dark fw-bold">{{ $product->name }}</h5>
                            </a>
                            <p class="card-text text-muted small">{{ $product->category->name }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 mb-0 text-primary fw-bold">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($product->stock > 0)
                                    <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})" aria-label="Tambah {{ $product->name }} ke keranjang">
                                        <i class="bi bi-cart-plus"></i> Tambah
                                    </button>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg" aria-label="Lihat Semua Produk">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features py-5" aria-label="Fitur Kami">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3 mx-auto" style="width: 64px; height: 64px;">
                        <i class="bi bi-truck display-6"></i>
                    </div>
                    <h4>Pengiriman Cepat</h4>
                    <p class="text-muted">Pesanan Anda akan segera dikirim setelah pembayaran dikonfirmasi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3 mx-auto" style="width: 64px; height: 64px;">
                        <i class="bi bi-shield-check display-6"></i>
                    </div>
                    <h4>Pembayaran Aman</h4>
                    <p class="text-muted">Transaksi Anda aman dengan konfirmasi pembayaran melalui WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3 mx-auto" style="width: 64px; height: 64px;">
                        <i class="bi bi-headset display-6"></i>
                    </div>
                    <h4>Layanan Pelanggan</h4>
                    <p class="text-muted">Tim kami siap membantu Anda dengan pertanyaan seputar produk dan pesanan.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan ke keranjang',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            const cartCount = document.getElementById('cart-count');
            if(cartCount) {
                cartCount.textContent = data.cartCount;
                cartCount.classList.add('cart-update');
                setTimeout(() => {
                    cartCount.classList.remove('cart-update');
                }, 300);
            }
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Gagal menambahkan ke keranjang',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat menambahkan ke keranjang',
            icon: 'error'
        });
        console.error('Error:', error);
    });
}
</script>
@endpush

<style>
.modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
</style>
