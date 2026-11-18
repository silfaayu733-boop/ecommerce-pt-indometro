@extends('layouts.admin')

@section('title', 'Manajemen Produk Bahan Bangunan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Produk Bahan Bangunan</h3>
        <p class="text-muted small mb-0">Kelola katalog barang, satuan, stok master, dan harga jual bahan bangunan</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
        <i class="fa-solid fa-plus me-1"></i> Tambah Produk Baru
    </button>
</div>

<div class="card border-0 bg-white shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Bahan Bangunan</h5>
        <form action="{{ route('admin.produk.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama / kode..." value="{{ request('q') }}">
            <button class="btn btn-sm btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Kode SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok Fisik</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $idx => $p)
                    <tr>
                        <td>{{ $produks->firstItem() + $idx }}</td>
                        <td><span class="badge bg-light text-dark border font-monospace">{{ $p->kode }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($p->image && file_exists(public_path('uploads/produk/' . $p->image)))
                                    <img src="{{ asset('uploads/produk/' . $p->image) }}" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;" alt="{{ $p->nama }}">
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $p->nama }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $p->deskripsi }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $p->kategori->nama_kategori ?? 'Umum' }}</span></td>
                        <td>{{ $p->satuan }}</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $p->stok <= 10 ? 'bg-danger' : 'bg-success' }} fs-6">
                                {{ $p->stok }} {{ $p->satuan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id }}" title="Edit Data">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Produk -->
                    <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('admin.produk.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Produk: {{ $p->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Kode SKU</label>
                                                <input type="text" name="kode" class="form-control" value="{{ $p->kode }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Kategori</label>
                                                <select name="id_kategori" class="form-select">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach($kategoris as $k)
                                                        <option value="{{ $k->id }}" {{ $p->id_kategori == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Nama Produk Bahan Bangunan</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Satuan (Sak, Batang, M3, Dus, dll.)</label>
                                                <input type="text" name="satuan" class="form-control" value="{{ $p->satuan }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Harga Jual Satuan (Rp)</label>
                                                <input type="number" name="harga" class="form-control" value="{{ $p->harga }}" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Deskripsi Bahan Bangunan</label>
                                                <textarea name="deskripsi" rows="3" class="form-control" required>{{ $p->deskripsi }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data produk bahan bangunan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $produks->links() }}
    </div>
</div>

<!-- Modal Tambah Produk Baru -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Produk Bahan Bangunan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kode SKU (Unik)</label>
                            <input type="text" name="kode" class="form-control" placeholder="contoh: SMN-TR-50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select name="id_kategori" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nama Bahan Bangunan</label>
                            <input type="text" name="nama" class="form-control" placeholder="contoh: Semen Holcim 40 Kg" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="Sak / Batang / Dus / M3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Stok Awal</label>
                            <input type="number" name="stok" class="form-control" value="0" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Harga Jual Satuan (Rp)</label>
                            <input type="number" name="harga" class="form-control" placeholder="50000" min="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Deskripsi & Spesifikasi Material</label>
                            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Penjelasan kegunaan, spesifikasi teknis, atau standar SNI..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

