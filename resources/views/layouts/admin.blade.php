<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Internal - PT. Indo Metro Surya Andola')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #0f172a;
            color: #f8fafc;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            font-weight: 800;
            font-size: 1.15rem;
            border-bottom: 1px solid #1e293b;
        }
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
        }
        .sidebar-menu .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }
        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active {
            color: #ffffff;
            background: #1e293b;
        }
        .sidebar-menu .nav-link.active {
            background: #0284c7;
            color: #ffffff;
        }
        .main-wrapper {
            margin-left: 260px;
            padding: 1.5rem 2rem;
            min-height: 100vh;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .stat-card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar d-flex flex-column">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <span class="p-2 bg-primary text-white rounded-3">
                <i class="fa-solid fa-trowel-bricks"></i>
            </span>
            <span>INDO METRO</span>
        </div>

        <div class="px-3 py-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-user-shield fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-truncate text-white" style="max-width: 150px;">{{ Auth::user()->name }}</div>
                    <span class="badge bg-secondary text-white" style="font-size: 0.7rem;">
                        {{ Auth::user()->role->role_name ?? 'Staff' }}
                    </span>
                </div>
            </div>
        </div>

        <ul class="sidebar-menu flex-grow-1">
            @if(Auth::user()->role_id == 1)
                <!-- Admin Links -->
                <li><span class="text-uppercase text-secondary fw-bold px-3" style="font-size: 0.7rem;">Menu Owner / Admin</span></li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.produk.index') }}" class="nav-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i> <span>Data Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> <span>Laporan Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.config.edit') }}" class="nav-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear"></i> <span>Pengaturan Toko</span>
                    </a>
                </li>
            @elseif(Auth::user()->role_id == 2)
                <!-- Kasir Links -->
                <li><span class="text-uppercase text-secondary fw-bold px-3" style="font-size: 0.7rem;">Menu Petugas Kasir</span></li>
                <li>
                    <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-cash-register"></i> <span>Dashboard Kasir</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kasir.pesanan.index') }}" class="nav-link {{ request()->routeIs('kasir.pesanan.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i> <span>Validasi Pesanan</span>
                    </a>
                </li>
            @elseif(Auth::user()->role_id == 3)
                <!-- Gudang Links -->
                <li><span class="text-uppercase text-secondary fw-bold px-3" style="font-size: 0.7rem;">Menu Staff Gudang</span></li>
                <li>
                    <a href="{{ route('gudang.dashboard') }}" class="nav-link {{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-warehouse"></i> <span>Dashboard Gudang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gudang.stok.index') }}" class="nav-link {{ request()->routeIs('gudang.stok.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-dolly"></i> <span>Input Stok Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gudang.pengiriman.index') }}" class="nav-link {{ request()->routeIs('gudang.pengiriman.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i> <span>Packing & Kirim</span>
                    </a>
                </li>
            @endif

            <li class="mt-4"><span class="text-uppercase text-secondary fw-bold px-3" style="font-size: 0.7rem;">Akses Lain</span></li>
            <li>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>Buka Halaman Web</span>
                </a>
            </li>
        </ul>

        <div class="p-3 border-top border-secondary border-opacity-25">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Body Area -->
    <div class="main-wrapper">
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-xmark fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

