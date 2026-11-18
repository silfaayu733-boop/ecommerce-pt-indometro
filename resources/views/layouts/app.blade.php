<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Indo Metro Surya Andola - Toko Bahan Bangunan Online')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 800;
            color: #0f172a !important;
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 500;
            color: #334155 !important;
        }
        .nav-link:hover, .nav-link.active {
            color: #0284c7 !important;
        }
        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
        }
        .btn-primary:hover {
            background-color: #0369a1;
            border-color: #0369a1;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }
        .badge-status {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
            border-radius: 9999px;
        }
        footer {
            margin-top: auto;
            background-color: #0f172a;
            color: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3">
        <div class="container-fluid px-3 px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="p-2 bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-trowel-bricks"></i>
                </span>
                <span>INDO METRO</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-house me-1"></i> Katalog Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kontak') ? 'active fw-bold' : '' }}" href="{{ route('kontak') }}">
                            <i class="fa-solid fa-address-book me-1"></i> Kontak & Toko
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-lg-center gap-2">
                    @auth
                        @if(Auth::user()->role_id == 4)
                            <li class="nav-item">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative px-3 rounded-pill">
                                    <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
                                    @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                                    @if($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pesanan.index') }}" class="nav-link">
                                    <i class="fa-solid fa-receipt me-1"></i> Pesanan Saya
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user fs-5 text-primary"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                @if(Auth::user()->role_id == 1)
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-2 text-primary"></i> Dashboard Admin</a></li>
                                @elseif(Auth::user()->role_id == 2)
                                    <li><a class="dropdown-item py-2" href="{{ route('kasir.dashboard') }}"><i class="fa-solid fa-cash-register me-2 text-success"></i> Dashboard Kasir</a></li>
                                @elseif(Auth::user()->role_id == 3)
                                    <li><a class="dropdown-item py-2" href="{{ route('gudang.dashboard') }}"><i class="fa-solid fa-warehouse me-2 text-warning"></i> Dashboard Gudang</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item py-2 text-danger" type="submit">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 rounded-pill">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary px-4 rounded-pill">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notifikasi Flash Message -->
    <div class="container-fluid px-3 px-lg-5 mt-3">
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
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container-fluid px-3 px-lg-5">
            <div class="row g-4">
                <div class="col-lg-5">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-trowel-bricks text-primary"></i> PT. INDO METRO SURYA ANDOLA
                    </h5>
                    <p class="small text-secondary mb-3">
                        Pusat perbelanjaan bahan bangunan terpercaya di Kota Metro, Lampung. Melayani pemesanan aneka semen, besi beton SNI, baja ringan, cat berkualitas, pipa, hingga granit lantai dengan jaminan pengiriman armada toko cepat dan aman.
                    </p>
                    <div class="text-secondary small">
                        <i class="fa-solid fa-location-dot me-2 text-primary"></i> Jl. Jenderal Sudirman No. 88, Metro Pusat, Kota Metro, Lampung
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="text-white fw-semibold mb-3">Layanan Toko</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-secondary">
                        <li><i class="fa-solid fa-truck me-2 text-primary"></i> Pengiriman Armada Toko</li>
                        <li><i class="fa-solid fa-shield-halved me-2 text-primary"></i> Bahan Bangunan Standar SNI</li>
                        <li><i class="fa-solid fa-qrcode me-2 text-primary"></i> Pembayaran Digital QRIS (Tanpa Rekening)</li>
                        <li><i class="fa-solid fa-headset me-2 text-primary"></i> Layanan Komplain Pelanggan</li>
                    </ul>
                </div>
                <div class="col-lg-4 col-6">
                    <h6 class="text-white fw-semibold mb-3">Jam Operasional</h6>
                    <p class="small text-secondary mb-2">
                        Senin - Sabtu: 07.30 - 17.00 WIB<br>
                        Minggu: 08.00 - 14.00 WIB
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('kontak') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fa-brands fa-whatsapp me-1 text-success"></i> Hubungi Toko
                        </a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center small text-secondary">
                &copy; {{ date('Y') }} PT. Indo Metro Surya Andola - Sistem Informasi E-Commerce Bahan Bangunan Berbasis Web.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

