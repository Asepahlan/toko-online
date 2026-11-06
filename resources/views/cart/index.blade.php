@extends('layouts.app')

@section('title', 'Keranjang Belanja - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Keranjang Belanja</h1>
            <p class="text-muted small mb-0">Periksa kembali daftar barang pesanan Anda sebelum checkout</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
        </a>
    </div>

    @if(Cart::isEmpty())
        <!-- State: Keranjang Kosong -->
        <div class="card border-0 shadow-sm text-center py-5 my-3" style="border-radius: 16px;">
            <div class="card-body py-5">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center p-4 mb-3 text-muted">
                    <i class="bi bi-cart-x display-3"></i>
                </div>
                <h4 class="fw-bold mb-2">Keranjang Belanja Anda Kosong</h4>
                <p class="text-muted mb-4">Anda belum memasukkan produk apapun ke dalam keranjang belanja.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-bag-plus me-1"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <!-- Daftar Item Keranjang -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">
                            Daftar Produk ({{ Cart::getContent()->count() }} Item)
                        </span>
                        <form id="clearCartForm" action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-link text-danger text-decoration-none p-0 small" onclick="confirmClearCart()">
                                <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        @foreach(Cart::getContent() as $item)
                            <div class="p-3 border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                <!-- Info Produk & Thumbnail -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded border overflow-hidden flex-shrink-0" style="width: 70px; height: 70px; background: #f8fafc;">
                                        @php
                                            $img = $item->attributes->image ?? null;
                                            $imgUrl = $item->attributes->image_url ?? null;
                                        @endphp
                                        @if($img)
                                            <img src="{{ asset('storage/' . $img) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
                                        @elseif($imgUrl)
                                            <img src="{{ $imgUrl }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image fs-4"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
                                        <div class="text-primary fw-semibold small">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontrol Quantity & Hapus -->
                                <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 w-100 w-sm-auto">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="submit" name="quantity" value="{{ $item->quantity - 1 }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>
                                            <input type="text" class="form-control text-center bg-white" value="{{ $item->quantity }}" readonly>
                                            <button class="btn btn-outline-secondary" type="submit" name="quantity" value="{{ $item->quantity + 1 }}">
                                                +
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Subtotal Item -->
                                    <div class="text-end" style="min-width: 110px;">
                                        <small class="text-muted d-block" style="font-size: 0.725rem;">Subtotal</small>
                                        <strong class="text-dark">
                                            Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}
                                        </strong>
                                    </div>

                                    <!-- Delete Item -->
                                    <form id="removeItemForm{{ $item->id }}" action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger p-1 px-2" title="Hapus dari keranjang" onclick="confirmRemoveItem('{{ $item->id }}', '{{ addslashes($item->name) }}')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ringkasan Belanja & Checkout -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 90px; border-radius: 14px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-2 small text-secondary">
                            <span>Total Item</span>
                            <span>{{ Cart::getTotalQuantity() }} Barang</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom small text-secondary">
                            <span>Estimasi Biaya Pengiriman</span>
                            <span class="text-success fw-semibold">Gratis / Hubungi CS</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold text-dark fs-5">Total Tagihan</span>
                            <span class="fw-bold text-primary fs-4">
                                Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}
                            </span>
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 shadow-sm mb-2">
                                <i class="bi bi-shield-lock me-1"></i> Lanjut ke Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 shadow-sm mb-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Checkout
                            </a>
                            <small class="text-muted text-center d-block">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Daftar sekarang</a>
                            </small>
                        @endauth

                        <div class="mt-3 pt-3 border-top small text-muted text-center">
                            <i class="bi bi-shield-check text-success me-1"></i> Transaksi dijamin aman & terpercaya
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@section('scripts')
<script>
function confirmClearCart() {
    window.confirmAction({
        title: 'Kosongkan Keranjang?',
        text: 'Semua produk yang ada di keranjang belanja Anda akan dihapus.',
        icon: 'warning',
        confirmText: 'Ya, Kosongkan',
        cancelText: 'Batal',
        confirmColor: '#dc2626'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('clearCartForm').submit();
        }
    });
}

function confirmRemoveItem(id, name) {
    window.confirmAction({
        title: 'Hapus Produk?',
        text: 'Produk "' + name + '" akan dikeluarkan dari keranjang.',
        icon: 'question',
        confirmText: 'Ya, Hapus',
        cancelText: 'Batal',
        confirmColor: '#dc2626'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('removeItemForm' + id).submit();
        }
    });
}
</script>
@endsection
@endsection
