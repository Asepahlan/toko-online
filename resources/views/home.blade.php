@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', config('app.name') . ' - Toko Online Terpercaya & Berkualitas')

@section('content')
<!-- Hero Banner Modern -->
<section class="py-5" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-radius: 0 0 32px 32px;">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary bg-opacity-25 border border-primary border-opacity-50 text-white mb-3 small">
                    <span class="badge bg-primary rounded-pill">Baru</span>
                    <span>Koleksi Terbaru Musim Ini Telah Hadir!</span>
                </div>
                <h1 class="display-4 fw-extrabold mb-3 tracking-tight" style="font-weight: 800; line-height: 1.15;">
                    Belanja Mudah, Nyaman & <span class="text-primary" style="color: #60a5fa !important;">Terpercaya</span>
                </h1>
                <p class="lead text-secondary mb-4" style="color: #cbd5e1 !important; font-size: 1.1rem;">
                    Temukan ratusan produk pilihan berkualitas tinggi dengan harga ramah kantong. Kami menjamin keaslian barang serta kecepatan pengiriman langsung ke alamat Anda.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 shadow">
                        <i class="bi bi-bag-check me-2"></i> Jelajahi Produk
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-grid me-2"></i> Lihat Kategori
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="row g-3 mt-4 pt-2 border-top border-secondary border-opacity-25">
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary fs-4"></i>
                            <div>
                                <strong class="d-block text-white small">100% Original</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Garansi uang kembali</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge text-warning fs-4"></i>
                            <div>
                                <strong class="d-block text-white small">Pengiriman Cepat</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Proses langsung</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-headset text-success fs-4"></i>
                            <div>
                                <strong class="d-block text-white small">CS 24/7</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Siap melayani</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ilustrasi Hero -->
            <div class="col-lg-5 text-center">
                <div class="card border-0 shadow-lg p-3 bg-white text-dark rounded-4" style="transform: rotate(1deg);">
                    <div class="position-relative overflow-hidden rounded-3 mb-3" style="max-height: 280px;">
                        <img src="{{ asset('storage/images/asep.png') }}"
                             alt="{{ config('app.name') }}"
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.src='https://placehold.co/600x400/2563eb/ffffff?text=Ahlan+Boys+Store'">
                    </div>
                    <div class="text-start">
                        <span class="badge bg-primary-subtle text-primary mb-1">Official Store</span>
                        <h5 class="fw-bold mb-1">{{ config('app.name') }} Collection</h5>
                        <p class="text-muted small mb-0">Nikmati diskon spesial dan kemudahan bertransaksi hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Kategori Pilihan -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 0.5px;">Kategori</span>
                <h2 class="h3 fw-bold mb-0">Pilih Berdasarkan Kategori</h2>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-sm">
                Semua Kategori <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="card h-100 border text-center p-3 hover-shadow" style="border-radius: 14px;">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                <i class="bi bi-{{ $category->icon ?? 'tag' }} fs-3"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count ?? $category->products()->count() }} Produk</small>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center border">Belum ada kategori yang ditambahkan.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section Produk Terpopuler -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;"><i class="bi bi-fire me-1"></i>Paling Diminati</span>
                <h2 class="h3 fw-bold mb-0">Produk Terlaris Minggu Ini</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                Lihat Semua Produk <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border shadow-sm" style="border-radius: 14px; overflow: hidden;">
                        <div class="position-relative" style="height: 200px; background: #f1f5f9;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                            @elseif($product->image_url)
                                <img src="{{ $product->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Terlaris</span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <small class="text-muted mb-1">{{ $product->category->name ?? 'Kategori' }}</small>
                            <h6 class="fw-bold mb-2 text-truncate">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary p-1 px-2" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center border">Belum ada produk terlaris.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call To Action (WhatsApp Order Support) -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="card border-0 shadow-sm p-4 p-md-5" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; border-radius: 20px;">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">Butuh Bantuan atau Mau Pesan Khusus?</h3>
                    <p class="mb-0 text-white-50" style="font-size: 1.05rem;">
                        Tim Customer Service kami siap melayani pertanyaan seputar ketersediaan stok, pemesanan kustom, atau konfirmasi pembayaran langsung lewat WhatsApp.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="https://wa.me/6285215142110?text=Halo%20Admin%20AhlanBoys,%20saya%20ingin%20bertanya%20tentang%20produk" target="_blank" rel="noopener" class="btn btn-light btn-lg px-4 fw-bold text-primary shadow">
                        <i class="bi bi-whatsapp text-success me-2"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
