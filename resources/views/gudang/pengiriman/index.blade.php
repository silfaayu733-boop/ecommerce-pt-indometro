@extends('layouts.admin')

@section('title', 'Packing & Pengiriman Barang - Staff Gudang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Packing & Pengiriman Armada Gudang</h3>
        <p class="text-muted small mb-0">Siapkan muatan bahan bangunan, cetak surat jalan, dan tugaskan sopir armada toko</p>
    </div>
</div>

<!-- Bagian 1: Pesanan Siap Packing (Status = 2) -->
<div class="card border-0 bg-white shadow-sm p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-warning d-flex align-items-center gap-2">
            <i class="fa-solid fa-boxes-packing"></i> Antrean Perlu Dipacking (Tervalidasi Kasir)
        </h5>
        <span class="badge bg-warning text-dark">{{ $pesananPacking->count() }} Pesanan</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Penerima & Alamat</th>
                    <th>Rincian Material Bangunan</th>
                    <th>Metode Kirim</th>
                    <th>Tindakan Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananPacking as $pk)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">#{{ $pk->kode_invoice }}</td>
                        <td>
                            <div class="fw-bold">{{ $pk->nama }} ({{ $pk->no_hp }})</div>
                            <div class="small text-secondary text-truncate" style="max-width: 200px;">{{ $pk->alamat }}</div>
                        </td>
                        <td>
                            <ul class="list-unstyled small mb-0">
                                @foreach($pk->invoiceLogs as $log)
                                    <li>&bull; <strong>{{ $log->qty }} {{ $log->produk->satuan ?? 'Pcs' }}</strong> - {{ $log->produk->nama ?? '-' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td><span class="badge bg-primary">{{ $pk->metode_pengiriman }}</span></td>
                        <td>
                            <div class="d-flex flex-column gap-2" style="min-width: 220px;">
                                <a href="{{ route('gudang.pengiriman.surat_jalan', $pk->kode_invoice) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-print me-1"></i> Cetak Surat Jalan
                                </a>

                                <form action="{{ route('gudang.pengiriman.proses', $pk->kode_invoice) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="no_resi" class="form-control" placeholder="No Resi / Sopir Pick-up" required>
                                        <button class="btn btn-primary" type="submit" title="Proses Pengiriman">
                                            <i class="fa-solid fa-truck-fast"></i> Kirim
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada antrean pesanan yang perlu dipacking saat ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bagian 2: Pesanan Sedang Dikirim (Status = 3) -->
<div class="card border-0 bg-white shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
            <i class="fa-solid fa-truck-fast"></i> Pesanan Sedang dalam Pengiriman Armada
        </h5>
        <span class="badge bg-primary">{{ $pesananDikirim->count() }} Sedang Jalan</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Penerima</th>
                    <th>Tujuan Pengiriman</th>
                    <th>No. Resi / Sopir</th>
                    <th>Waktu Berangkat</th>
                    <th>Konfirmasi Sampai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananDikirim as $dk)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">#{{ $dk->kode_invoice }}</td>
                        <td>{{ $dk->nama }} ({{ $dk->no_hp }})</td>
                        <td class="small text-secondary">{{ $dk->alamat }}</td>
                        <td><span class="badge bg-dark font-monospace">{{ $dk->no_resi }}</span></td>
                        <td class="small text-muted">{{ $dk->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('gudang.pengiriman.selesai', $dk->kode_invoice) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3" onclick="return confirm('Tandai bahwa barang sudah diterima pembeli di lokasi?');">
                                    <i class="fa-solid fa-check me-1"></i> Barang Telah Sampai
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada armada yang sedang jalan saat ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

