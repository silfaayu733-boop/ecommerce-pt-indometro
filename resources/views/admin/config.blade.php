@extends('layouts.admin')

@section('title', 'Pengaturan Toko & Rekening - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Pengaturan Toko & Informasi Rekening</h3>
        <p class="text-muted small mb-0">Ubah identitas toko, barcode QRIS pembayaran, dan kontak website</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 bg-white shadow-sm p-4">
            <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Perusahaan / Toko</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $config->nama) }}" required>
                </div>

                <!-- QRIS Pembayaran Digital -->
                <div class="card border border-danger border-opacity-25 bg-danger bg-opacity-10 p-3 mb-4 rounded-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-danger">QRIS NASIONAL</span>
                        <h6 class="fw-bold mb-0 text-dark">Barcode QRIS Pembayaran (Tanpa Rekening)</h6>
                    </div>
                    <p class="small text-muted mb-3">Pelanggan dapat melakukan scan barcode ini dari berbagai e-wallet (DANA, GoPay, OVO, ShopeePay, LinkAja) dan Mobile Banking tanpa perlu nomor rekening bank.</p>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="{{ asset('uploads/' . ($config->qris ?? 'qris_indometro.svg')) }}" alt="QRIS Toko" class="img-fluid rounded border bg-white p-2 shadow-sm" style="max-height: 180px;">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Unggah / Ganti Gambar Barcode QRIS Toko</label>
                            <input type="file" name="qris" class="form-control" accept="image/*">
                            <div class="form-text small">Mendukung format PNG, JPG, atau SVG. Rekomendasi rasio vertikal standar QRIS Bank Indonesia.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama Bank / Lembaga QRIS</label>
                        <input type="text" name="bank" class="form-control" value="{{ old('bank', $config->bank) }}" placeholder="contoh: QRIS BCA / Merchant QRIS" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">NMID / Nomor Identifikasi Merchant</label>
                        <input type="text" name="no_rek" class="form-control font-monospace fw-bold" value="{{ old('no_rek', $config->no_rek) }}" placeholder="contoh: ID1024398711090" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="telp" class="form-control" value="{{ old('telp', $config->telp) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-semibold text-secondary">Alamat Email Toko</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $config->email) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Alamat Fisik Toko</label>
                    <textarea name="alamat" rows="2" class="form-control" required>{{ old('alamat', $config->alamat) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Teks Header / Slogan Banner Web</label>
                    <input type="text" name="header" class="form-control" value="{{ old('header', $config->header) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Google Maps Iframe Embed Code</label>
                    <textarea name="map" rows="3" class="form-control font-monospace small" placeholder='<iframe src="https://maps.google.com/..." ...></iframe>'>{{ old('map', $config->map) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

