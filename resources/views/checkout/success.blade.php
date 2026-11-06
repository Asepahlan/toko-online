@extends('layouts.app')

@section('title', 'Pesanan Berhasil - ' . config('app.name'))

@section('meta')
<meta name="description" content="Pesanan Anda telah berhasil dibuat. Silakan lanjutkan ke WhatsApp untuk proses pembayaran.">
<meta name="robots" content="noindex, nofollow">
<meta property="og:title" content="Pesanan Berhasil - {{ config('app.name') }}">
<meta property="og:description" content="Pesanan Anda telah berhasil dibuat. Silakan lanjutkan ke WhatsApp untuk proses pembayaran.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:image" content="{{ asset('path/to/image.jpg') }}"> <!-- Ganti dengan path gambar yang sesuai -->
@endsection

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
            <li class="breadcrumb-item"><a href="{{ route('checkout.index') }}">Checkout</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan Berhasil</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-check fa-3x"></i>
                        </div>
                    </div>

                    <h1 class="h3 mb-3">Pesanan Berhasil Dibuat!</h1>
                    <p class="text-muted mb-4">
                        Terima kasih telah berbelanja di {{ config('app.name') }}.<br>
                        Nomor Pesanan Anda: <strong>#{{ $order->id }}</strong>
                    </p>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Detail Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start text-muted">Total Pembayaran:</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start text-muted">Status:</td>
                                            <td class="text-end">
                                                <span class="badge bg-warning">Menunggu Pembayaran</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start text-muted">Alamat Pengiriman:</td>
                                            <td class="text-end">{{ $order->shipping_address }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(session('whatsapp_url'))
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                Silakan klik tombol di bawah untuk melanjutkan proses pembayaran melalui WhatsApp
                            </div>
                        </div>

                        <a href="{{ session('whatsapp_url') }}"
                           target="_blank"
                           class="btn btn-success btn-lg mb-3">
                            <i class="fab fa-whatsapp me-2"></i>
                            Lanjutkan ke WhatsApp
                        </a>
                    @endif

                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('products') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-clock text-primary me-2"></i>
                                Langkah Selanjutnya
                            </h5>
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Klik tombol WhatsApp di atas</li>
                                <li class="mb-2">Konfirmasi pesanan Anda</li>
                                <li class="mb-2">Lakukan pembayaran sesuai instruksi</li>
                                <li>Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-question-circle text-primary me-2"></i>
                                Butuh Bantuan?
                            </h5>
                            <p class="card-text mb-3">
                                Jika Anda memiliki pertanyaan atau kendala, silakan hubungi customer service kami:
                            </p>
                            <div class="d-grid">
                                <a href="tel:6285215142110" class="btn btn-outline-primary">
                                    <i class="fas fa-phone me-2"></i>
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Beranda",
        "item": "{{ route('home') }}"
    },{
        "@type": "ListItem",
        "position": 2,
        "name": "Keranjang",
        "item": "{{ route('cart.index') }}"
    },{
        "@type": "ListItem",
        "position": 3,
        "name": "Checkout",
        "item": "{{ route('checkout.index') }}"
    },{
        "@type": "ListItem",
        "position": 4,
        "name": "Pesanan Berhasil"
    }]
}
</script>
@endpush
@endsection
