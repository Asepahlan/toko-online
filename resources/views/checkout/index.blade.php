@extends('layouts.app')

@section('title', 'Checkout Pembayaran - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Keranjang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout Pesanan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold mb-1">Konfirmasi & Checkout</h1>
            <p class="text-muted small mb-0">Lengkapi data pengiriman barang Anda dengan benar</p>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">
            <i class="bi bi-shield-check me-1"></i> Transaksi Aman
        </span>
    </div>

    <div class="row g-4">
        <!-- Form Pengiriman -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-geo-alt text-primary me-2"></i>Alamat & Data Penerima
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="recipient_name">Nama Lengkap Penerima <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('recipient_name') is-invalid @enderror"
                                   id="recipient_name"
                                   name="recipient_name"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   placeholder="Contoh: Budi Santoso"
                                   required>
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="phone">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', auth()->user()->phone ?? auth()->user()->phone_number) }}"
                                   placeholder="Contoh: 081234567890"
                                   required>
                            <small class="text-muted">Nomor ini digunakan untuk konfirmasi status dan pengiriman.</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="address">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, kode pos"
                                      required>{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="notes">Catatan Tambahan untuk Penjual (Opsional)</label>
                            <textarea class="form-control"
                                      id="notes"
                                      name="notes"
                                      rows="2"
                                      placeholder="Contoh: Titipkan di satpam perumahan / warna alternatif">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow">
                            <i class="bi bi-bag-check-fill me-2"></i> Proses Pesanan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ringkasan Item Pesanan -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-receipt text-primary me-2"></i>Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3" style="max-height: 280px; overflow-y: auto;">
                        @foreach(Cart::getContent() as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-semibold mb-0 small text-truncate" style="max-width: 200px;">
                                        {{ $item->name }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </small>
                                </div>
                                <span class="fw-bold text-dark small">
                                    Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Total Jumlah Barang</span>
                        <span>{{ Cart::getTotalQuantity() }} pcs</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom small text-secondary">
                        <span>Biaya Pengiriman</span>
                        <span class="text-success fw-semibold">Gratis / Sesuai Kurir</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
                        <span class="fw-bold text-primary fs-4">
                            Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="bg-light p-3 rounded-3 small text-muted">
                        <i class="bi bi-info-circle text-primary me-1"></i> Setelah klik <strong>Proses Pesanan</strong>, Anda akan diarahkan ke halaman rincian pesanan dan dapat langsung mengonfirmasi lewat WhatsApp.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
