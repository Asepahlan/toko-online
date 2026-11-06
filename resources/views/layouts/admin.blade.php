@php
use Illuminate\Http\Request;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    <meta name="description" content="Panel Admin untuk mengelola {{ config('app.name') }}.">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/images/asep.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('storage/images/asep.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('storage/images/asep.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--primary-color);
            transition: transform 0.3s ease;
            z-index: 1000;
            transform: translateX(0); /* Menampilkan sidebar */
        }

        #sidebar.active {
            transform: translateX(-100%); /* Menyembunyikan sidebar */
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 16px;
            transition: all 0.3s;
        }

        #sidebar .nav-link:hover {
            color: white;
            background: var(--secondary-color);
            transform: translateX(5px);
        }

        #sidebar .nav-link.active {
            color: white;
            background: var(--accent-color);
        }

        #sidebar .nav-link i {
            width: 24px;
            margin-right: 8px;
        }

        /* Main Content */
        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
            padding: 20px;
        }

        /* Header */
        .content-header {
            margin-bottom: 24px;
        }

        /* Alerts */
        .alert {
            margin-bottom: 20px;
        }

        /* Footer */
        footer {
            margin-top: auto;
            padding: 20px 0;
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border-radius: 8px; /* Membuat sudut dropdown lebih halus */
            min-width: 200px; /* Lebar minimum dropdown */
            z-index: 1001; /* Pastikan dropdown muncul di atas konten lainnya */
        }

        .dropdown-item {
            padding: 10px 15px; /* Menambah padding untuk item dropdown */
            transition: background-color 0.2s; /* Efek transisi saat hover */
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Warna latar belakang saat hover */
        }

        .dropdown-divider {
            background-color: rgba(255, 255, 255, 0.2); /* Warna pemisah */
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%); /* Tersembunyi secara default */
            }

            #sidebar.active {
                transform: translateX(0); /* Menampilkan sidebar */
            }

            #content {
                margin-left: 0; /* Menghilangkan margin untuk konten */
            }

            .dropdown-menu {
                position: absolute; /* Pastikan dropdown muncul di atas konten */
                left: 0; /* Sesuaikan posisi dropdown */
                right: 0; /* Sesuaikan posisi dropdown */
            }
        }
    </style>

    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar Toggle -->
    <button class="btn btn-link text-white" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <nav id="sidebar" aria-label="Sidebar Navigation">
        <div class="p-4">
            <!-- Logo -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-2" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary d-flex align-items-center justify-content-center" style="font-size: 1.2rem;">
                                {{ strtoupper(substr(config('app.name'), 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ config('app.name') }}</h4>
                            <small class="text-muted">Admin Panel</small>
                        </div>
                    </div>
                </a>
                <button class="btn btn-link text-white d-md-none" id="sidebarClose" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Menu -->
            <div class="nav flex-column">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>Produk
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>Kategori
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i>Pesanan
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>Pengguna
                </a>
            </div>

            <!-- User Profile -->
            <div class="mt-auto pt-4 border-top border-secondary">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; background-color: {{ '#' . substr(md5(auth()->user()->name), 0, 6) }};">
                            <span class="text-white fw-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <small class="d-block text-muted">Administrator</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}">
                                <i class="bi bi-house me-2"></i>Halaman Utama
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div id="content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="mt-auto py-4">
            <div class="container-fluid">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted">
                        <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
                    </div>
                    <div class="d-flex gap-3 mt-3 mt-md-0">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <small>Halaman Utama</small>
                        </a>
                        <a href="https://wa.me/6285215142110" class="text-muted text-decoration-none" target="_blank" rel="noopener noreferrer">
                            <small>Bantuan</small>
                        </a>
                        <a href="mailto:support@example.com" class="text-muted text-decoration-none">
                            <small>Kontak</small>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let lastScrollTop = 0;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');

        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        document.getElementById('sidebarClose')?.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        overlay?.addEventListener('click', function() {
            sidebar.classList.remove('active');
            this.classList.remove('active');
        });

        // Auto-hide sidebar on scroll
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop) {
                // Scroll Down
                if (window.innerWidth <= 768) { // Hanya untuk mobile
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            } else {
                // Scroll Up
                if (window.innerWidth <= 768) { // Hanya untuk mobile
                    sidebar.classList.add('active');
                }
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Untuk scrolling negatif
        });
    </script>
    @stack('scripts')
</body>
</html>
