@php
use Illuminate\Http\Request;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    <meta name="description" content="Panel Administrasi Resmi {{ config('app.name') }}.">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/images/asep.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
            --admin-dark: #0f172a;
            --admin-nav-hover: #1e293b;
            --primary-accent: #3b82f6;
            --font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --card-radius: 12px;
            --transition-smooth: all 0.2s ease;
        }

        body {
            font-family: var(--font-family);
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--admin-dark);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            transition: var(--transition-smooth);
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.15rem;
            text-decoration: none;
        }

        .sidebar-nav {
            list-style: none;
            padding: 16px 12px;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            padding: 10px 12px 4px 12px;
        }

        .sidebar-nav .nav-link {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background: var(--admin-nav-hover);
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: var(--primary-accent);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.15rem;
        }

        /* Top Bar */
        .admin-header {
            height: var(--header-height);
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1020;
        }

        /* Content Area */
        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
        }

        .content-body {
            padding: 24px;
            flex-grow: 1;
        }

        /* Admin Footer */
        .admin-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .card {
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Admin -->
    <aside id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2 me-2" style="width: 32px; height: 32px; font-weight: 800; font-size: 0.95rem;">
                AB
            </span>
            <div class="lh-1">
                <span>{{ config('app.name') }}</span>
                <small class="d-block text-danger fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">ADMIN PANEL</small>
            </div>
        </a>

        <ul class="sidebar-nav">
            <li class="nav-section-title">Menu Utama</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-section-title">Transaksi & Penjualan</li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Kelola Pesanan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>

            <li class="nav-section-title">Manajemen Katalog</li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-boxes"></i>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>

            <li class="nav-section-title">Sistem & Pengguna</li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Pengguna Toko</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}" target="_blank" class="nav-link text-info">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>Buka Toko Depan</span>
                </a>
            </li>
        </ul>

        <!-- Bottom Sidebar Info -->
        <div class="p-3 border-top border-secondary border-opacity-25 bg-black bg-opacity-25">
            <div class="d-flex align-items-center justify-content-between">
                <div class="text-truncate me-2">
                    <span class="d-block text-white fw-semibold small text-truncate">{{ auth()->user()->name }}</span>
                    <small class="text-danger" style="font-size: 0.7rem;">Super Administrator</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div id="content">
        <!-- Top Admin Header -->
        <header class="admin-header shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                        <i class="bi bi-shield-lock me-1"></i> Mode Administrator
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex align-items-center gap-1">
                    <i class="bi bi-shop"></i> Lihat Toko
                </a>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-weight: 700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start lh-1">
                        <span class="d-block fw-semibold text-dark small">{{ auth()->user()->name }}</span>
                        <small class="text-muted" style="font-size: 0.7rem;">admin</small>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Main Body -->
        <main class="content-body">
            @yield('content')
        </main>

        <!-- Admin Footer -->
        <footer class="admin-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div>
                &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong> Panel Admin.
            </div>
            <div class="text-muted">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 Modern Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.confirmAction = function(options) {
            return Swal.fire({
                title: options.title || 'Konfirmasi Tindakan',
                text: options.text || '',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || '#dc2626',
                cancelButtonColor: options.cancelColor || '#64748b',
                confirmButtonText: options.confirmText || 'Ya, Hapus',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0',
                    confirmButton: 'btn btn-danger px-4 py-2 rounded-3 fw-semibold',
                    cancelButton: 'btn btn-outline-secondary px-4 py-2 rounded-3 fw-semibold me-2'
                },
                buttonsStyling: false
            });
        };

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: { popup: 'rounded-4 shadow-lg border-0' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0',
                    confirmButton: 'btn btn-danger px-4 py-2 rounded-3 fw-semibold'
                },
                buttonsStyling: false
            });
        @endif
    </script>
    @yield('scripts')
</body>
</html>
