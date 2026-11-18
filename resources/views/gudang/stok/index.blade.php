@extends('layouts.admin')

@section('title', 'Input Stok Masuk Gudang - PT. Indo Metro Surya Andola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Penerimaan & Pasokan Stok Masuk Gudang</h3>
        <p class="text-muted small mb-0">Catat faktur penerimaan barang dari supplier untuk menambah stok fisik bahan bangunan</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input Stok Masuk -->
    <div class="col-lg-4">
        <div class="card border-0 bg-white shadow-sm p-4 sticky-top" style="top: 90px;">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Form Stok Masuk</h5>
            <form action="{{ route('gudang.stok.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">No. Faktur Surat Jalan Supplier</label>
                    <input type="text" name="faktur" class="form-control" placeholder="contoh: FKT-SPL-2026-001" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Pilih Bahan Bangunan</label>
                    <select name="id_produk" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} (Sisa Stok: {{ $p->stok }} {{ $p->satuan }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Jumlah Kuantitas Masuk</label>
                    <input type="number" name="qty" class="form-control" placeholder="Jumlah pasokan baru" min="0.5" step="0.5" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Simpan Stok Masuk
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat Pasokan Masuk -->
    <div class="col-lg-8">
        <div class="card border-0 bg-white shadow-sm p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Riwayat Pasokan Masuk (Tabel Produk Masuk)</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No. Faktur</th>
                            <th>Nama Bahan Bangunan</th>
                            <th>Kuantitas Masuk</th>
                            <th>Waktu Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatMasuk as $idx => $rm)
                            <tr>
                                <td>{{ $riwayatMasuk->firstItem() + $idx }}</td>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ $rm->faktur }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $rm->produk->nama ?? 'Produk Dihapus' }}</div>
                                    <div class="small text-muted font-monospace">{{ $rm->produk->kode ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">
                                        +{{ $rm->qty }} {{ $rm->produk->satuan ?? 'Pcs' }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $rm->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat penerimaan stok masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $riwayatMasuk->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

