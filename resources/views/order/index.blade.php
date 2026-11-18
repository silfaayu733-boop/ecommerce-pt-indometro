@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-receipt me-2 text-primary"></i> Riwayat Pesanan Saya</h3>
            <p class="text-muted small mb-0">Daftar transaksi dan pemantauan status pengiriman bahan bangunan Anda</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-cart-plus me-1"></i> Belanja Lagi
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card p-5 text-center border-0 bg-white shadow-sm">
            <i class="fa-solid fa-file-invoice fs-1 text-muted mb-3 opacity-50"></i>
            <h5 class="fw-bold">Belum Ada Riwayat Pesanan</h5>
            <p class="text-muted small">Anda belum melakukan pemesanan produk bahan bangunan apapun.</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-sm mx-auto rounded-pill px-4 py-2 mt-2">Mulai Belanja</a>
        </div>
    @else
        <div class="card border-0 bg-white shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Item Pembelian</th>
                            <th>Metode Kirim</th>
                            <th>Total Tagihan</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $ord)
                            <tr>
                                <td>
                                    <span class="fw-bold font-monospace text-primary">#{{ $ord->kode_invoice }}</span>
                                </td>
                                <td class="small text-secondary">{{ $ord->created_at->format('d M Y, H:i') }}</td>
                                <td class="small">
                                    {{ $ord->invoiceLogs->count() }} macam item
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $ord->metode_pengiriman }}</span>
                                </td>
                                <td class="fw-bold text-dark">
                                    Rp {{ number_format($ord->grandtotal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($ord->status == 0)
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Menunggu Bayar</span>
                                    @elseif($ord->status == 1)
                                        <span class="badge bg-info text-dark"><i class="fa-solid fa-hourglass-half me-1"></i> Verifikasi Kasir</span>
                                    @elseif($ord->status == 2)
                                        <span class="badge bg-primary"><i class="fa-solid fa-boxes-packing me-1"></i> Packing Gudang</span>
                                    @elseif($ord->status == 3)
                                        <span class="badge bg-info text-white"><i class="fa-solid fa-truck-fast me-1"></i> Sedang Dikirim</span>
                                    @elseif($ord->status == 4)
                                        <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Selesai</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak/Batal</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pesanan.detail', $ord->kode_invoice) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

