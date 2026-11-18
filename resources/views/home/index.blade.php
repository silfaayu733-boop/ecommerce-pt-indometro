@extends('layouts.app')

@section('title', 'Katalog Bahan Bangunan - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 pb-5">

    <!-- Hero Banner -->
    <div class="card border-0 text-white rounded-4 overflow-hidden mb-4 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3">
                        <i class="fa-solid fa-certificate me-1"></i> Supplier Bahan Bangunan Terpercaya
                    </span>
                    <h1 class="display-6 fw-extrabold text-white mb-2">PT. INDO METRO SURYA ANDOLA</h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 800px;">
                        {{ $config->header ?? 'Pusat belanja semen, besi beton, baja ringan, pipa, cat, dan kebutuhan renovasi rumah terlengkap di Kota Metro, Lampung.' }}
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#katalog" class="btn btn-light px-4 py-2 rounded-pill fw-bold text-primary">
                            <i class="fa-solid fa-cart-shopping me-1"></i> Belanja Sekarang
                        </a>
                        <a href="{{ route('kontak') }}" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-map-location-dot me-1"></i> Lokasi & Kontak Toko
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="fa-solid fa-truck-moving text-white-50" style="font-size: 9rem; opacity: 0.25;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur Unggulan -->
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card border-0 bg-white p-3 d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Armada Toko Siap Kirim</h6>
                    <p class="small text-muted mb-0">Pengiriman langsung dengan pick-up atau truk toko</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-white p-3 d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-3">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Produk Standar SNI</h6>
                    <p class="small text-muted mb-0">Jaminan mutu material bangunan asli & bergaransi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-white p-3 d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-3">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Layanan Komplain Cepat</h6>
                    <p class="small text-muted mb-0">Respon cepat dan penanganan langsung dari manajemen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Katalog Section -->
    <div id="katalog" class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Katalog Produk Bahan Bangunan</h3>
            <p class="text-muted small mb-0">Pilih kebutuhan proyek bangunan atau renovasi rumah Anda</p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('home') }}" method="GET" class="d-flex gap-2" style="max-width: 380px;">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari semen, besi, pipa..." value="{{ request('q') }}">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            @if(request('q') || request('kategori'))
                <a href="{{ route('home') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </form>
    </div>

    <!-- Kategori Filter Tabs -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('home', ['q' => request('q')]) }}" class="btn btn-sm {{ !request('kategori') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3 py-2">
            Semua Kategori
        </a>
        @foreach($kategoriList as $kat)
            <a href="{{ route('home', ['kategori' => $kat->id, 'q' => request('q')]) }}" class="btn btn-sm {{ request('kategori') == $kat->id ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3 py-2">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>

    <!-- Grid Produk -->
    @if($produks->isEmpty())
        <div class="card p-5 text-center border-0 bg-white">
            <i class="fa-solid fa-box-open fs-1 text-muted mb-3"></i>
            <h5 class="fw-bold">Tidak ada produk yang ditemukan</h5>
            <p class="text-muted small">Coba cari dengan kata kunci lain atau pilih kategori berbeda.</p>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary mx-auto rounded-pill px-4">Tampilkan Semua Produk</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4">
            @foreach($produks as $p)
                <div class="col">
                    <div class="card h-100 border-0 bg-white shadow-sm overflow-hidden d-flex flex-column">
                        <div class="position-relative bg-light overflow-hidden" style="height: 200px;">
                            @if($p->image && file_exists(public_path('uploads/produk/' . $p->image)))
                                <img src="{{ asset('uploads/produk/' . $p->image) }}" alt="{{ $p->nama }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                    <i class="fa-solid fa-boxes-stacked text-secondary opacity-25" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 rounded-pill">
                                {{ $p->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            @if($p->stok <= 0)
                                <span class="position-absolute top-0 end-0 m-2 badge bg-danger rounded-pill">Habis</span>
                            @else
                                <span class="position-absolute top-0 end-0 m-2 badge bg-success rounded-pill">Tersedia {{ $p->stok }} {{ $p->satuan }}</span>
                            @endif
                        </div>

                        <div class="card-body p-3 d-flex flex-column flex-grow-1">
                            <div class="small text-muted mb-1 font-monospace">#{{ $p->kode }}</div>
                            <h6 class="fw-bold text-dark mb-2 text-truncate-2" style="min-height: 42px;">{{ $p->nama }}</h6>
                            <p class="small text-muted mb-3 text-truncate-2" style="min-height: 38px;">{{ Str::limit($p->deskripsi, 65) }}</p>

                            <div class="mt-auto pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">Harga per {{ $p->satuan }}</div>
                                        <div class="fs-5 fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    @if($p->stok > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill py-2 fw-semibold">
                                                <i class="fa-solid fa-cart-plus me-1"></i> Beli Sekarang
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100 rounded-pill py-2 disabled">Stok Habis</button>
                                    @endif
                                    <a href="{{ route('produk.detail', $p->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill py-1">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $produks->links() }}
        </div>
    @endif

</div>
@endsection

