@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Checkout - {{ config('app.name') }}</h2>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pengiriman</h5>
                    <form action="{{ route('checkout.process') }}" method="POST" aria-label="Form Checkout">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="recipient_name">Nama Penerima</label>
                            <input type="text"
                                   class="form-control @error('recipient_name') is-invalid @enderror"
                                   id="recipient_name"
                                   name="recipient_name"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   required
                                   placeholder="Masukkan nama penerima">
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', auth()->user()->phone ?? auth()->user()->phone_number) }}"
                                   required
                                   placeholder="Contoh: 08123456789">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="address">Alamat Lengkap Pengiriman</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      required
                                      placeholder="Masukkan alamat lengkap penerima (Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos)">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="notes">Catatan Pesanan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="2"
                                      placeholder="Catatan khusus untuk penjual / kurir">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-shield-check me-2"></i>Konfirmasi & Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->name }} ({{ $item->quantity }}x)</span>
                            <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Setelah pesanan dibuat, Anda dapat langsung mengonfirmasi pesanan ke WhatsApp admin.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
