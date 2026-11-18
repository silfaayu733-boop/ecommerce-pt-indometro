@extends('layouts.app')

@section('title', $produk->nama . ' - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home', ['kategori' => $produk->id_kategori]) }}" class="text-decoration-none">{{ $produk->kategori->nama_kategori ?? 'Kategori' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $produk->nama }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm p-4 p-md-5 mb-5 bg-white">
        <div class="row g-5">
            <div class="col-lg-5 text-center">
                <div class="bg-light rounded-4 overflow-hidden mb-3 shadow-sm" style="min-height: 350px; max-height: 420px;">
                    @if($produk->image && file_exists(public_path('uploads/produk/' . $produk->image)))
                        <img src="{{ asset('uploads/produk/' . $produk->image) }}" alt="{{ $produk->nama }}" class="w-100 h-100" style="object-fit: cover; min-height: 350px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center p-5" style="min-height: 350px;">
                            <i class="fa-solid fa-boxes-stacked text-secondary opacity-25" style="font-size: 7rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-barcode me-1"></i> Kode SKU: {{ $produk->kode }}
                </div>
            </div>

            <div class="col-lg-7">
                <span class="badge bg-secondary mb-2">{{ $produk->kategori->nama_kategori ?? 'Umum' }}</span>
                <h2 class="fw-bold text-dark mb-2">{{ $produk->nama }}</h2>

                <div class="d-flex align-items-baseline gap-3 mb-4">
                    <h3 class="fw-extrabold text-primary mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h3>
                    <span class="text-muted">/ {{ $produk->satuan }}</span>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="row text-center g-2">
                        <div class="col-4 border-end">
                            <div class="small text-muted">Status Stok</div>
                            <div class="fw-bold {{ $produk->stok > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $produk->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="small text-muted">Jumlah Stok</div>
                            <div class="fw-bold text-dark">{{ $produk->stok }} {{ $produk->satuan }}</div>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted">Standar Mutu</div>
                            <div class="fw-bold text-primary">SNI Resmi</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Material:</h6>
                    <p class="text-secondary leading-relaxed">{{ $produk->deskripsi }}</p>
                </div>

                @if($produk->stok > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="border-top pt-4">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <label class="fw-semibold small text-muted">Kuantitas ({{ $produk->satuan }}):</label>
                                <div class="input-group" style="width: 140px;">
                                    <input type="number" name="qty" class="form-control text-center fw-bold" value="1" min="1" max="{{ $produk->stok }}" step="1" required>
                                </div>
                            </div>
                            <div class="col-auto flex-grow-1">
                                <label class="d-none d-md-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Tambahkan ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger mb-0">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Produk ini sedang habis di gudang kami.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Produk Terkait -->
    @if($terkait->isNotEmpty())
        <h4 class="fw-bold mb-3">Produk Terkait Lainnya</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            @foreach($terkait as $t)
                <div class="col">
                    <div class="card h-100 border-0 bg-white p-3">
                        <div class="fw-bold text-truncate mb-1">{{ $t->nama }}</div>
                        <div class="text-primary fw-bold mb-2">Rp {{ number_format($t->harga, 0, ',', '.') }} / {{ $t->satuan }}</div>
                        <a href="{{ route('produk.detail', $t->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill mt-auto">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

