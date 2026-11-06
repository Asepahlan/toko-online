@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Keranjang Belanja - ' . config('app.name'))

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <h1 class="h2 mb-4">Keranjang Belanja</h1>
            <p class="text-muted">{{ Cart::getContent()->count() }} item dalam keranjang</p>
        </div>
    </div>

    @if(Cart::isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
            <h3>Keranjang Kosong</h3>
            <p class="text-muted mb-4">Belum ada produk di keranjang belanja Anda</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
            </a>
        </div>
    @else
        <div class="row">
            <!-- Daftar Produk -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        @foreach(Cart::getContent() as $item)
                            <div class="row align-items-center mb-4">
                                <!-- Gambar Produk -->
                                <div class="col-3 col-md-2">
                                    <div class="ratio ratio-1x1">
                                        <img src="{{ $item->attributes->has('image') ? asset('storage/' . $item->attributes->image) : asset('images/default.jpg') }}"
                                             alt="{{ $item->name }} - Gambar Produk"
                                             class="rounded w-100 h-100"
                                             style="object-fit: cover;"
                                             onerror="this.src='{{ asset('images/default.jpg') }}'">
                                    </div>
                                </div>

                                <!-- Info Produk -->
                                <div class="col-9 col-md-4">
                                    <h5 class="mb-1">{{ $item->name }}</h5>
                                    <p class="text-muted mb-0">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div class="col-6 col-md-3">
                                    <div class="input-group">
                                        <button type="button"
                                                class="btn btn-outline-secondary update-quantity"
                                                data-id="{{ $item->id }}"
                                                data-type="decrease">-</button>
                                        <input type="number"
                                               class="form-control text-center"
                                               value="{{ $item->quantity }}"
                                               data-id="{{ $item->id }}"
                                               min="1" aria-label="Jumlah produk">
                                        <button type="button"
                                                class="btn btn-outline-secondary update-quantity"
                                                data-id="{{ $item->id }}"
                                                data-type="increase">+</button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="col-4 col-md-2 text-end">
                                    <span id="subtotal-{{ $item->id }}">
                                        Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Hapus -->
                                <div class="col-2 col-md-1 text-end">
                                    <button onclick="removeItem({{ $item->id }})" class="btn btn-link text-danger p-0" aria-label="Hapus item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form Checkout -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5>Total Belanja</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <span id="cart-total">Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-bag-check me-2"></i>Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.update-quantity').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const type = this.dataset.type;
        const input = document.querySelector(`input[data-id="${id}"]`);
        let value = parseInt(input.value);

        if (type === 'decrease' && value > 1) {
            value--;
        } else if (type === 'increase') {
            value++;
        }

        input.value = value;
        updateCart(id, value);
    });
});

function updateCart(id, quantity) {
    fetch(`/cart/update/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const subtotalEl = document.querySelector(`#subtotal-${id}`);
            if (subtotalEl) {
                subtotalEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.subtotal);
            }
            const totalEl = document.querySelector('#cart-total');
            if (totalEl) {
                totalEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
            }
        }
    });
}

function removeItem(id) {
    if (confirm("Apakah Anda yakin ingin menghapus item ini dari keranjang?")) {
        fetch(`/cart/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus item');
            }
        })
        .catch(() => {
            location.reload();
        });
    }
}
</script>
@endpush
