@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin / Owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Laporan Penjualan Bahan Bangunan</h3>
        <p class="text-muted small mb-0">Rekapitulasi transaksi penjualan lunas berdasarkan periode tanggal</p>
    </div>
    <a href="{{ route('admin.laporan.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'print' => 1]) }}" target="_blank" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-print me-1"></i> Cetak Laporan PDF / Print
    </a>
</div>

<!-- Filter Tanggal -->
<div class="card border-0 bg-white shadow-sm p-4 mb-4">
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-semibold text-secondary">Tanggal Awal</label>
            <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold text-secondary">Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fa-solid fa-filter me-1"></i> Terapkan Filter Tanggal
            </button>
        </div>
    </form>
</div>

<!-- Ringkasan Periode -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 bg-white shadow-sm p-3">
            <div class="text-muted small">Total Transaksi Lunas / Selesai</div>
            <div class="fs-4 fw-bold text-dark">{{ $totalTransaksi }} Transaksi</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 bg-white shadow-sm p-3">
            <div class="text-muted small">Total Omzet Penjualan Periode Ini</div>
            <div class="fs-4 fw-bold text-primary">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="card border-0 bg-white shadow-sm p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>No. Invoice</th>
                    <th>Tanggal</th>
                    <th>Nama Pembeli</th>
                    <th>Metode Kirim</th>
                    <th>Ongkir</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $idx => $row)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-bold font-monospace text-primary">#{{ $row->kode_invoice }}</td>
                        <td class="small">{{ $row->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $row->nama }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $row->metode_pengiriman }}</span></td>
                        <td>Rp {{ number_format($row->ongkir, 0, ',', '.') }}</td>
                        <td class="fw-bold">Rp {{ number_format($row->grandtotal, 0, ',', '.') }}</td>
                        <td><span class="badge bg-success">Lunas / Selesai</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data transaksi lunas pada rentang tanggal ini.</td></tr>
                @endforelse
            </tbody>
            @if($laporan->isNotEmpty())
                <tfoot class="table-light">
                    <tr>
                        <th colspan="6" class="text-end">TOTAL KESELURUHAN OMZET:</th>
                        <th class="text-primary fs-5">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection

