@extends('layouts.admin')

@section('title', 'Daftar Pesanan - Kasir')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Daftar Validasi Pesanan Kasir</h3>
        <p class="text-muted small mb-0">Verifikasi keaslian bukti pembayaran QRIS customer sebelum pesanan disiapkan gudang</p>
    </div>
</div>

<!-- Filter Tabs Status -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('kasir.pesanan.index') }}" class="btn btn-sm {{ $status === null || $status === '' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">Semua Status</a>
    <a href="{{ route('kasir.pesanan.index', ['status' => 1]) }}" class="btn btn-sm {{ $status === '1' ? 'btn-warning text-dark' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">
        <i class="fa-solid fa-hourglass-half me-1"></i> Perlu Dicek (Upload Bukti)
    </a>
    <a href="{{ route('kasir.pesanan.index', ['status' => 0]) }}" class="btn btn-sm {{ $status === '0' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">Menunggu Bayar</a>
    <a href="{{ route('kasir.pesanan.index', ['status' => 2]) }}" class="btn btn-sm {{ $status === '2' ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">Telah Divalidasi / Packing</a>
    <a href="{{ route('kasir.pesanan.index', ['status' => 4]) }}" class="btn btn-sm {{ $status === '4' ? 'btn-success' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">Selesai</a>
    <a href="{{ route('kasir.pesanan.index', ['status' => 5]) }}" class="btn btn-sm {{ $status === '5' ? 'btn-danger' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">Ditolak / Batal</a>
</div>

<div class="card border-0 bg-white shadow-sm p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Nama Customer</th>
                    <th>Telepon</th>
                    <th>Total Tagihan</th>
                    <th>Metode Kirim</th>
                    <th>Status Bukti</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">#{{ $p->kode_invoice }}</td>
                        <td>{{ $p->nama }}</td>
                        <td class="small text-muted">{{ $p->no_hp }}</td>
                        <td class="fw-bold">Rp {{ number_format($p->grandtotal, 0, ',', '.') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $p->metode_pengiriman }}</span></td>
                        <td>
                            @if($p->bukti)
                                <span class="badge {{ $p->bukti->status_verifikasi === 'Valid' ? 'bg-success' : ($p->bukti->status_verifikasi === 'Ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ $p->bukti->status_verifikasi }} ({{ $p->bukti->bank }})
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">Belum Upload</span>
                            @endif
                        </td>
                        <td>
                            @if($p->status == 0)<span class="badge bg-warning text-dark">Menunggu Bayar</span>
                            @elseif($p->status == 1)<span class="badge bg-info text-dark">Perlu Dicek</span>
                            @elseif($p->status == 2)<span class="badge bg-primary">Packing Gudang</span>
                            @elseif($p->status == 3)<span class="badge bg-info text-white">Dikirim</span>
                            @elseif($p->status == 4)<span class="badge bg-success">Selesai</span>
                            @else<span class="badge bg-danger">Batal</span>@endif
                        </td>
                        <td>
                            <a href="{{ route('kasir.pesanan.detail', $p->kode_invoice) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Detail & Validasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data pesanan pada kategori status ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pesanan->links() }}
    </div>
</div>
@endsection

