@extends('layouts.admin')

@section('title', 'Dashboard Owner / Administrator - PT. Indo Metro Surya Andola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Administrator / Owner</h3>
        <p class="text-muted small mb-0">Pemantauan data operasional, penjualan bahan bangunan, dan arus kas toko</p>
    </div>
    <div class="text-end">
        <span class="badge bg-white text-dark border px-3 py-2">
            <i class="fa-regular fa-calendar me-1 text-primary"></i> {{ date('d F Y') }}
        </span>
    </div>
</div>

<!-- Kartu Ringkasan (Sesuai Konsep Wireframe Halaman 75 Skripsi) -->
<div class="row g-3 mb-4">
    <!-- Baris 1: Status Pesanan -->
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-white p-3 border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Pesanan Masuk</div>
                    <div class="fs-3 fw-bold text-dark">{{ $pesananMasuk }}</div>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-4">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Menunggu konfirmasi kasir</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-white p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Pesanan Diproses</div>
                    <div class="fs-3 fw-bold text-dark">{{ $pesananDiproses }}</div>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-4">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Packing gudang & pengiriman</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-white p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Pesanan Selesai</div>
                    <div class="fs-3 fw-bold text-dark">{{ $pesananSelesai }}</div>
                </div>
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-4">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Transaksi berhasil & lunas</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card stat-card bg-white p-3 border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Stok Menipis (&le; 10)</div>
                    <div class="fs-3 fw-bold text-danger">{{ $stokMenipis }}</div>
                </div>
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3 fs-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Perlu pasokan gudang baru</div>
        </div>
    </div>
</div>

<!-- Baris 2: Total Pendapatan & Stok Banner (Halaman 75 Skripsi) -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 bg-primary bg-gradient text-white p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-bold mb-3">
                    <i class="fa-solid fa-chart-line me-1"></i> Rekapitulasi Keuangan
                </span>
                <div class="text-white-50 small text-uppercase fw-semibold">Total Pendapatan Transaksi Selesai</div>
                <h1 class="display-5 fw-extrabold text-white mt-1 mb-3">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h1>
                <p class="text-white-50 mb-0">
                    Akumulasi pendapatan kotor dari seluruh transaksi penjualan bahan bangunan yang telah berstatus lunas dan terkirim ke customer.
                </p>
            </div>
            <div class="mt-4 pt-3 border-top border-white border-opacity-25 d-flex gap-3">
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-primary">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i> Buka Laporan Lengkap
                </a>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                    <i class="fa-solid fa-boxes-stacked me-1"></i> Kelola Data Produk
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 bg-white p-4 shadow-sm h-100">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Akses Cepat Pengelola</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-primary text-start p-3 rounded-3 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-square-plus fs-4"></i>
                    <div>
                        <div class="fw-bold">Tambah Produk Baru</div>
                        <div class="small text-muted">Input material & harga baru</div>
                    </div>
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-success text-start p-3 rounded-3 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-print fs-4"></i>
                    <div>
                        <div class="fw-bold">Cetak Laporan Penjualan</div>
                        <div class="small text-muted">Rekap transaksi per periode</div>
                    </div>
                </a>
                <a href="{{ route('admin.config.edit') }}" class="btn btn-outline-secondary text-start p-3 rounded-3 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-gear fs-4"></i>
                    <div>
                        <div class="fw-bold">Pengaturan QRIS & Toko</div>
                        <div class="small text-muted">Kelola barcode QRIS & profil toko</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="card border-0 bg-white shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Transaksi Pesanan Terbaru</h5>
        <a href="{{ route('admin.laporan.index') }}" class="small text-primary fw-semibold text-decoration-none">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Metode Kirim</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru as $p)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">#{{ $p->kode_invoice }}</td>
                        <td>{{ $p->nama }}</td>
                        <td class="small text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $p->metode_pengiriman }}</span></td>
                        <td class="fw-bold">Rp {{ number_format($p->grandtotal, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status == 0)<span class="badge bg-warning text-dark">Menunggu Bayar</span>
                            @elseif($p->status == 1)<span class="badge bg-info text-dark">Validasi Kasir</span>
                            @elseif($p->status == 2)<span class="badge bg-primary">Packing Gudang</span>
                            @elseif($p->status == 3)<span class="badge bg-info text-white">Dikirim</span>
                            @elseif($p->status == 4)<span class="badge bg-success">Selesai</span>
                            @else<span class="badge bg-danger">Batal</span>@endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

