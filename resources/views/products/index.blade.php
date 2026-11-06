@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
<div class="container-fluid py-3">
    <!-- Search & Filter -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="search-box">
                <form action="{{ route('products.index') }}" method="GET" role="search">
                    <div class="input-group">
                        <input type="text"
                               class="form-control"
                               name="search"
                               placeholder="Cari produk..."
                               aria-label="Cari produk"
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit" aria-label="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" onchange="window.location.href=this.value" aria-label="Pilih kategori">
                <option value="{{ route('products.index') }}">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ route('products.index', ['category' => $category->slug]) }}"
                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm product-card">
                    <!-- Gambar Produk -->
                    <div style="height: 200px; overflow: hidden;">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-100 h-100 product-img"
                                 style="object-fit: cover;"
                                 alt="{{ $product->name }}"
                                 onerror="this.src='{{ asset('images/default.jpg') }}'">
                        @else
                            <img src="{{ asset('images/default.jpg') }}"
                                 class="w-100 h-100 product-img"
                                 style="object-fit: cover;"
                                 alt="{{ $product->name }}">
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title product-name mb-2">{{ $product->name }}</h6>
                        <div class="product-details mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="stock-badge">Stok: {{ $product->stock }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="btn btn-sm btn-outline-primary flex-grow-1" aria-label="Lihat produk">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button"
                                class="btn btn-primary flex-grow-1 cart-button"
                                data-product-id="{{ $product->id }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus"></i>
                        </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h4>Tidak ada produk</h4>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>

@push('styles')
<style>
/* Base Styles */
.product-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,.1);
}

.product-img {
    height: 160px;
    object-fit: cover;
}

.product-name {
    font-size: 0.9rem;
    line-height: 1.3;
    height: 2.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.price {
    font-weight: 600;
    color: #0d6efd;
    font-size: 0.95rem;
}

.stock-badge {
    font-size: 0.8rem;
    color: #6c757d;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .product-img {
        height: 120px;
    }

    .product-name {
        font-size: 0.8rem;
        height: 2.4em;
    }

    .price {
        font-size: 0.85rem;
    }

    .card-body {
        padding: 0.5rem;
    }

    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
}

@media (min-width: 768px) {
    .product-img {
        height: 180px;
    }
}

@media (min-width: 992px) {
    .product-img {
        height: 200px;
    }

    .product-name {
        font-size: 0.95rem;
    }
}

/* Search Box */
.search-box .form-control {
    border-radius: 50rem 0 0 50rem;
}

.search-box .btn {
    border-radius: 0 50rem 50rem 0;
}

/* Category Select */
.form-select {
    border-radius: 50rem;
    padding: 0.375rem 1rem;
}

/* Animasi update keranjang */
.cart-update {
    animation: cartBounce 0.3s ease-in-out;
}

@keyframes cartBounce {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartButtons = document.querySelectorAll('.cart-button');

    cartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            addToCart(productId, this);
        });
    });
});

function addToCart(productId, button) {
    button.disabled = true;

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
            // Tampilkan notifikasi dengan SweetAlert2
            Swal.fire({
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan ke keranjang',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            // Update cart count secara real-time
            const cartCount = document.getElementById('cart-count');
            if(cartCount) {
                cartCount.textContent = data.cartCount;
                // Tambahkan animasi sederhana
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
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endpush
@endsection
