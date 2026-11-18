@extends('layouts.admin')

@section('title', 'Dashboard Petugas Kasir - PT. Indo Metro Surya Andola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Petugas Kasir</h3>
        <p class="text-muted small mb-0">Verifikasi bukti transfer pembayaran pesanan customer dan validasi pesanan masuk</p>
    </div>
    <span class="badge bg-white text-dark border px-3 py-2">
        <i class="fa-solid fa-cash-register me-1 text-success"></i> Meja Kasir Online
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Menunggu Validasi Bukti</div>
                    <div class="fs-2 fw-bold text-warning">{{ $menungguValidasi }}</div>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-3">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <a href="{{ route('kasir.pesanan.index', ['status' => 1]) }}" class="small text-warning fw-bold text-decoration-none mt-2 d-inline-block">
                Lihat yang Perlu Divalidasi &rarr;
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Pesanan Hari Ini</div>
                    <div class="fs-2 fw-bold text-dark">{{ $hariIni }}</div>
                </div>
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-3">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Dibuat customer per hari ini</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-white p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small fw-semibold">Total Pembayaran Valid</div>
                    <div class="fs-2 fw-bold text-success">{{ $totalTervalidasi }}</div>
                </div>
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="small text-muted mt-2">Telah disetujui kasir</div>
        </div>
    </div>
</div>

<div class="card border-0 bg-white shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Pesanan Masuk Terbaru</h5>
        <a href="{{ route('kasir.pesanan.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            Kelola Semua Pesanan
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Nama Customer</th>
                    <th>Waktu Pesan</th>
                    <th>Grand Total</th>
                    <th>Bukti Transfer</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananTerbaru as $ord)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">#{{ $ord->kode_invoice }}</td>
                        <td>{{ $ord->nama }}</td>
                        <td class="small">{{ $ord->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">Rp {{ number_format($ord->grandtotal, 0, ',', '.') }}</td>
                        <td>
                            @if($ord->bukti)
                                <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Sudah Upload</span>
                            @else
                                <span class="badge bg-light text-muted border">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            @if($ord->status == 0)<span class="badge bg-warning text-dark">Menunggu Bayar</span>
                            @elseif($ord->status == 1)<span class="badge bg-info text-dark">Perlu Dicek</span>
                            @elseif($ord->status == 2)<span class="badge bg-primary">Packing Gudang</span>
                            @elseif($ord->status == 3)<span class="badge bg-info text-white">Dikirim</span>
                            @elseif($ord->status == 4)<span class="badge bg-success">Selesai</span>
                            @else<span class="badge bg-danger">Batal</span>@endif
                        </td>
                        <td>
                            <a href="{{ route('kasir.pesanan.detail', $ord->kode_invoice) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Periksa Bukti
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

