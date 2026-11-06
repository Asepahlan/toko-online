@php
    use Illuminate\Support\Facades\Request;
    use Illuminate\Support\Facades\Route;
    use Darryldecode\Cart\Facades\CartFacade as Cart;
@endphp

<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name') . ' - Toko Online Terpercaya')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Belanja online mudah, cepat, dan aman di ' . config('app.name'))">
    @yield('meta')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/images/asep.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --secondary-color: #0f172a;
            --font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --card-radius: 14px;
            --transition-smooth: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: var(--font-family);
            background-color: #f8fafc;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Notification Bar */
        .top-bar {
            background: #0f172a;
            color: #94a3b8;
            font-size: 0.825rem;
            padding: 7px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .top-bar a {
            color: #cbd5e1;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .top-bar a:hover {
            color: #fff;
        }

        /* Main Navbar */
        .main-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 0;
            box-shadow: 0 4px 20px -8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.35rem;
            color: #0f172a !important;
            letter-spacing: -0.5px;
        }

        .brand-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 6px;
            text-transform: uppercase;
            margin-left: 6px;
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.925rem;
            color: #475569 !important;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: var(--transition-smooth);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: var(--primary-light);
        }

        /* User Profile Pill in Navbar */
        .user-pill {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 4px 14px 4px 6px;
            font-weight: 600;
            font-size: 0.875rem;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .user-pill:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .avatar-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Cart button */
        .cart-btn {
            position: relative;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #1e293b;
            padding: 7px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition-smooth);
        }

        .cart-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: var(--primary-light);
        }

        .cart-badge {
            background: #ef4444;
            color: white;
            font-size: 0.725rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
        }

        /* Cards and Elements */
        .card {
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
            transition: var(--transition-smooth);
        }

        .btn {
            font-weight: 600;
            border-radius: 10px;
            padding: 9px 18px;
            transition: var(--transition-smooth);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
        }

        /* Footer styling */
        .modern-footer {
            background: #090d16;
            color: #94a3b8;
            margin-top: auto;
            border-top: 1px solid #1e293b;
        }

        .footer-heading {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.65rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-links a:hover {
            color: #ffffff;
            transform: translateX(3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            padding: 20px 0;
            font-size: 0.85rem;
        }

        .social-pill {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #1e293b;
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .social-pill:hover {
            background: var(--primary-color);
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
    @yield('styles')
</head>
<body class="d-flex flex-column h-100">

    <!-- Top Announcement & Quick Contact Bar -->
    <div class="top-bar d-none d-md-block">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <span><i class="bi bi-shield-check text-primary me-1"></i> Garansi Produk 100% Original & Berkualitas</span>
                    <span class="text-secondary">|</span>
                    <span><i class="bi bi-truck text-primary me-1"></i> Pengiriman Cepat ke Seluruh Indonesia</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="https://wa.me/6285215142110" target="_blank" rel="noopener">
                        <i class="bi bi-whatsapp text-success me-1"></i> Hubungi CS: +62 852-1514-2110
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <!-- Brand Logo & Name -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 me-2" style="width: 36px; height: 36px; font-weight: 800;">
                    AB
                </span>
                <span>{{ config('app.name') }}</span>
                <span class="brand-badge d-none d-sm-inline-block">Store</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-3"></i>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Nav Kiri: Navigasi Belanja -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid me-1"></i> Katalog Produk
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i class="bi bi-list-ul me-2 text-primary"></i> Semua Kategori</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @forelse($allCategories ?? [] as $navCat)
                                <li>
                                    <a class="dropdown-item" href="{{ route('categories.show', $navCat) }}">
                                        <i class="bi bi-tag me-2 text-muted"></i> {{ $navCat->name }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">Belum ada kategori</span></li>
                            @endforelse
                        </ul>
                    </li>
                </ul>

                <!-- Nav Kanan: Role-Aware Area (Guest, Customer, Admin) -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="cart-btn" aria-label="Keranjang Belanja">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span class="d-none d-sm-inline">Keranjang</span>
                        <span class="cart-badge">{{ $cartCount ?? 0 }}</span>
                    </a>

                    @guest
                        <!-- ================= GUEST (NON-USER) VIEW ================= -->
                        <div class="d-flex align-items-center gap-2 ms-1">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                                <i class="bi bi-person-plus me-1"></i> Daftar
                            </a>
                        </div>
                    @else
                        <!-- ================= LOGGED IN USER (CUSTOMER / ADMIN) ================= -->
                        <div class="dropdown ms-1">
                            <a class="user-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-circle">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <div class="text-start d-none d-sm-block pe-1">
                                    <span class="d-block lh-1 text-truncate" style="max-width: 120px;">{{ auth()->user()->name }}</span>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        @if(auth()->user()->isAdmin())
                                            <span class="badge bg-danger p-1" style="font-size: 0.65rem;">Administrator</span>
                                        @else
                                            <span class="badge bg-light text-dark border p-1" style="font-size: 0.65rem;">Pelanggan</span>
                                        @endif
                                    </small>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li class="px-3 py-2 border-bottom bg-light rounded-top">
                                    <span class="fw-bold d-block text-dark">{{ auth()->user()->name }}</span>
                                    <small class="text-muted text-break">{{ auth()->user()->email }}</small>
                                </li>

                                @if(auth()->user()->isAdmin())
                                    <!-- Role Marker: Khusus Administrator -->
                                    <li>
                                        <div class="dropdown-header text-uppercase text-danger fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                            Menu Khusus Admin
                                        </div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-primary fw-semibold" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Masuk Panel Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                            <i class="bi bi-receipt me-2"></i> Kelola Pesanan Toko
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                            <i class="bi bi-boxes me-2"></i> Kelola Produk Toko
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif

                                <!-- Menu Pelanggan (User) -->
                                <li>
                                    <div class="dropdown-header text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        Akun Saya
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag-check me-2 text-primary"></i> Pesanan Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-gear me-2 text-secondary"></i> Pengaturan Akun
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Flash Notification Messages -->
    <div class="container pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-5 me-2 text-info"></i>
                <div class="flex-grow-1">{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-shrink-0 mb-5">
        @yield('content')
    </main>

    <!-- Modern & Comprehensive Footer -->
    <footer class="modern-footer pt-5">
        <div class="container pb-4">
            <div class="row g-4">
                <!-- Brand & Deskripsi Toko -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 me-2" style="width: 38px; height: 38px; font-weight: 800; font-size: 1.1rem;">
                            AB
                        </span>
                        <h5 class="text-white fw-bold mb-0">{{ config('app.name') }}</h5>
                    </div>
                    <p class="text-secondary small pe-lg-4 mb-4 lh-base">
                        Toko online terpercaya dengan berbagai pilihan produk berkualitas terbaik. Nikmati kemudahan transaksi yang aman, layanan responsif, dan pengiriman tepat waktu.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/6285215142110" target="_blank" rel="noopener" class="social-pill" title="WhatsApp Customer Care">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="#" class="social-pill" title="Instagram Resmi">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-pill" title="Facebook Page">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-pill" title="TikTok">
                            <i class="bi bi-tiktok"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigasi Belanja Cepat -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right text-primary" style="font-size: 0.75rem;"></i> Beranda</a></li>
                        <li><a href="{{ route('products.index') }}"><i class="bi bi-chevron-right text-primary" style="font-size: 0.75rem;"></i> Semua Produk</a></li>
                        <li><a href="{{ route('categories.index') }}"><i class="bi bi-chevron-right text-primary" style="font-size: 0.75rem;"></i> Kategori</a></li>
                        <li><a href="{{ route('cart.index') }}"><i class="bi bi-chevron-right text-primary" style="font-size: 0.75rem;"></i> Keranjang Belanja</a></li>
                    </ul>
                </div>

                <!-- Kategori Pilihan -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="footer-heading">Kategori Populer</h6>
                    <ul class="footer-links">
                        @forelse($allCategories ?? [] as $footerCat)
                            <li>
                                <a href="{{ route('categories.show', $footerCat) }}">
                                    <i class="bi bi-chevron-right text-primary" style="font-size: 0.75rem;"></i> {{ $footerCat->name }}
                                </a>
                            </li>
                        @empty
                            <li><span class="text-muted small">Kategori belum tersedia</span></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Bantuan & Kontak Layanan -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Layanan Pelanggan</h6>
                    <div class="d-flex flex-column gap-3 small text-secondary">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-whatsapp text-success fs-5"></i>
                            <div>
                                <span class="d-block text-white fw-semibold">Layanan WhatsApp</span>
                                <span>+62 852-1514-2110 (Senin - Minggu)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-clock-history text-primary fs-5"></i>
                            <div>
                                <span class="d-block text-white fw-semibold">Jam Operasional</span>
                                <span>08.00 - 21.00 WIB</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shield-check text-info fs-5"></i>
                            <div>
                                <span class="d-block text-white fw-semibold">Jaminan Kepuasan</span>
                                <span>Kemudahan konfirmasi & pelacakan pesanan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Hak Cipta & Info Peran Pengguna Aktif -->
        <div class="footer-bottom">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-secondary small">
                    &copy; {{ date('Y') }} <span class="text-white fw-semibold">{{ config('app.name') }}</span>. Seluruh Hak Cipta Dilindungi.
                </div>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 0.75rem;">
                            Status Akun: <strong class="text-white">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Pelanggan Terdaftar' }}</strong>
                        </span>
                    @else
                        <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 0.75rem;">
                            Pengunjung (Tamu) - <a href="{{ route('login') }}" class="text-primary text-decoration-none">Masuk untuk transaksi</a>
                        </span>
                    @endauth
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
