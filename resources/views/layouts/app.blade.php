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
    <title>{{ config('app.name') }} - Ahlan Boys - E-comeerce</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Belanja produk berkualitas di {{ config('app.name') }}. Temukan berbagai kategori dan produk terbaik.">
    <meta name="keywords" content="belanja, produk, kategori, {{ config('app.name') }}">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/images/asep.png') }}">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/asep.png') }}" alt="Logo {{ config('app.name') }}" width="32" class="me-2">
                {{ config('app.name') }}
            </a>

            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Konten Navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Menu Kiri -->
                <ul class="navbar-nav me-auto">
                    <!-- Dropdown Produk -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProduk" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">Semua Produk</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('products.new') }}">Produk Terbaru</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.popular') }}">Produk Populer</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Menu Kanan -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Keranjang -->
                        <li class="nav-item me-2">
                            <a href="{{ route('cart.index') }}" class="nav-link">
                                <i class="bi bi-cart"></i> Keranjang
                                @php
                                    $cartCount = \Cart::getContent()->count();
                                @endphp
                                <span class="badge bg-primary">{{ $cartCount }}</span>
                            </a>
                        </li>

                        <!-- Dropdown User -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-circle me-2"></i> Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag me-2"></i> Pesanan Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-shrink-0">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto bg-gray-900 text-gray-400 py-8">
        <div class="container">
            <div class="row">
                <!-- Brand Section -->
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle p-2 me-2">
                            <span class="text-white fw-bold">AS</span>
                        </div>
                        <span class="text-white fw-semibold">Ahlan Boys</span>
                    </div>
                    <p class="text-gray-400 small mb-4">
                        Ahlan Boys adalah toko online terpercaya yang menyediakan berbagai produk berkualitas dengan harga terbaik. Kami berkomitmen memberikan pelayanan terbaik untuk kepuasan pelanggan.
                    </p>
                    <div class="social-links">
                        <a href="" class="text-gray-400 me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-gray-400 me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-gray-400"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <!-- Menu Section -->
                <div class="col-lg-2 mb-4">
                    <h6 class="text-white mb-3">MENU UTAMA</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-gray-400 text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-gray-400 text-decoration-none">Produk</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 mb-4">
                    <h6 class="text-white mb-3">KATEGORI POPULER</h6>
                    <ul class="list-unstyled">
                        @foreach($allCategories ?? [] as $category)
                            <li class="mb-2">
                                <a href="{{ route('categories.show', $category) }}" class="text-gray-400 text-decoration-none">
                                    {{ $category->name }} ({{ $category->products()->count() }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-lg-3">
                    <h6 class="text-white mb-3">HUBUNGI KAMI</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-whatsapp me-2"></i>
                                <span>+62 852-1514-2110</span>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope me-2"></i>
                                <span>info@example.com</span>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>Jl. Contoh No. 123, Kota, Indonesia</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-top border-gray-800 pt-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="small mb-md-0">© {{ date('Y') }} Ahlan Boys. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-gray-400 text-decoration-none small me-3">Kebijakan Privasi</a>
                        <a href="#" class="text-gray-400 text-decoration-none small">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    <style>
    html, body {
        height: 100%;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    main {
        flex: 1 0 auto;
    }
    .footer {
        flex-shrink: 0;
    }
    .text-gray-400 {
        color: #9ca3af;
    }
    .bg-gray-900 {
        background-color: #111827;
    }
    .border-gray-800 {
        border-color: #1f2937;
    }
    .social-links a:hover,
    .text-gray-400:hover {
        color: #ffffff !important;
        transition: color 0.2s;
    }
    </style>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus item ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
