@extends('layouts.admin')

@section('title', 'Dashboard Staff Gudang - PT. Indo Metro Surya Andola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Staff Gudang</h3>
        <p class="text-muted small mb-0">Manajemen pasokan stok masuk fisik bahan bangunan dan pemrosesan packing barang</p>
    </div>
    <span class="badge bg-white text-dark border px-3 py-2">
        <i class="fa-solid fa-warehouse me-1 text-warning"></i> Pergudangan Metro
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Perlu Dipacking (Lunas)</div>
                    <div class="fs-2 fw-bold text-warning">{{ $perluPacking }}</div>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-3">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
            </div>
            <a href="{{ route('gudang.pengiriman.index') }}" class="small text-warning fw-bold text-decoration-none mt-2 d-inline-block">
                Lihat Antrean Packing &rarr;
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Sedang Dikirim Armada</div>
                    <div class="fs-2 fw-bold text-primary">{{ $sedangDikirim }}</div>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
            </div>
            <a href="{{ route('gudang.pengiriman.index') }}" class="small text-primary fw-bold text-decoration-none mt-2 d-inline-block">
                Pantau Pengiriman &rarr;
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Produk Stok Menipis</div>
                    <div class="fs-2 fw-bold text-danger">{{ $stokMenipis->count() }}</div>
                </div>
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3 fs-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <a href="{{ route('gudang.stok.index') }}" class="small text-danger fw-bold text-decoration-none mt-2 d-inline-block">
                Input Pasokan Baru &rarr;
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 bg-white shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Antrean Pesanan Gudang</h5>
                <a href="{{ route('gudang.pengiriman.index') }}" class="small text-primary fw-semibold text-decoration-none">Kelola Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Penerima</th>
                            <th>Metode Kirim</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananGudang as $p)
                            <tr>
                                <td class="fw-bold font-monospace text-primary">#{{ $p->kode_invoice }}</td>
                                <td>{{ $p->nama }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $p->metode_pengiriman }}</span></td>
                                <td>
                                    @if($p->status == 2)
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-box me-1"></i> Perlu Packing</span>
                                    @else
                                        <span class="badge bg-info text-white"><i class="fa-solid fa-truck me-1"></i> Dalam Pengiriman</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('gudang.pengiriman.surat_jalan', $p->kode_invoice) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Surat Jalan">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada antrean pesanan packing saat ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 bg-white shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i> Peringatan Stok Menipis</h5>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($stokMenipis as $sm)
                    <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">{{ $sm->nama }}</div>
                            <div class="small text-muted font-monospace">{{ $sm->kode }}</div>
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $sm->stok }} {{ $sm->satuan }}</span>
                    </li>
                @empty
                    <li class="list-group-item px-0 py-3 text-center text-muted">Semua stok bahan bangunan dalam kondisi aman.</li>
                @endforelse
            </ul>
            <div class="mt-3">
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-primary btn-sm w-100 rounded-pill py-2">
                    <i class="fa-solid fa-plus me-1"></i> Input Pasokan Stok Masuk Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

