@extends('layouts.app')

@section('title', 'Formulir Pemesanan & Checkout - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 bg-white shadow-sm p-4">
                <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <span class="p-2 bg-primary text-white rounded-circle fs-6"><i class="fa-solid fa-location-dot"></i></span>
                    Data Pengiriman & Pemesan
                </h4>
                <p class="text-muted small mb-4">Mohon pastikan nama penerima, nomor kontak, dan alamat tujuan proyek Anda sudah akurat.</p>

                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Penerima / Pemesan</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nomor Telepon / WhatsApp Aktif</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->telepon) }}" placeholder="08xxxxxxxxxx" required>
                        <div class="form-text small">Petugas armada toko atau kasir akan mengonfirmasi pengiriman via nomor ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Alamat Lengkap Lokasi Pengiriman</label>
                        <textarea name="alamat" rows="3" class="form-control" placeholder="Nama Jalan, Nomor, Patokan Lokasi Bangunan, Kelurahan, Kecamatan, Kota Metro" required>{{ old('alamat', $user->alamat) }}</textarea>
                    </div>

                    <!-- Pilihan Pengiriman Bahan Bangunan -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">Pilihan Metode Pengiriman</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="card p-3 border cursor-pointer h-100 option-card" for="kirim_armada">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pengiriman" id="kirim_armada" value="Armada Toko" checked onchange="updateTotal()">
                                        <label class="form-check-label fw-bold" for="kirim_armada">
                                            <i class="fa-solid fa-truck text-primary me-1"></i> Armada Toko
                                        </label>
                                    </div>
                                    <p class="small text-muted mb-0 mt-2">Diantar dengan pick-up / truk toko ke lokasi proyek (Wilayah Kota Metro & sekitarnya).</p>
                                    <span class="badge bg-primary mt-2">Ongkir: Rp 50.000</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="card p-3 border cursor-pointer h-100 option-card" for="ambil_sendiri">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metode_pengiriman" id="ambil_sendiri" value="Ambil Sendiri" onchange="updateTotal()">
                                        <label class="form-check-label fw-bold" for="ambil_sendiri">
                                            <i class="fa-solid fa-store text-success me-1"></i> Ambil Sendiri
                                        </label>
                                    </div>
                                    <p class="small text-muted mb-0 mt-2">Ambil langsung di toko fisik PT. Indo Metro Surya Andola setelah lunas.</p>
                                    <span class="badge bg-success mt-2">Gratis Ongkir (Rp 0)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">Catatan Khusus Pesanan (Opsional)</label>
                        <input type="text" name="catatan" class="form-control" placeholder="contoh: Tolong dikirim pagi jam 9 / taruh di samping garasi">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-lock me-1"></i> Buat Pesanan & Dapatkan Kode Invoice
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 bg-white shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Item Pembelian</h5>
                <ul class="list-group list-group-flush mb-3">
                    @foreach($carts as $item)
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between">
                            <div>
                                <div class="fw-semibold small text-dark">{{ $item->produk->nama }}</div>
                                <div class="text-muted small">{{ $item->qty }} {{ $item->produk->satuan }} &times; Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
                            </div>
                            <span class="fw-bold text-dark">Rp {{ number_format($item->qty * $item->produk->harga, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary small">Subtotal Produk</span>
                    <span class="fw-semibold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary small">Biaya Pengiriman</span>
                    <span class="fw-semibold text-primary" id="ongkirText">Rp 50.000</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top">
                    <span class="fw-bold fs-6">Total Pembayaran</span>
                    <span class="fw-bold fs-5 text-primary" id="grandtotalText">Rp {{ number_format($subtotal + 50000, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Instruksi Pembayaran QRIS Tanpa Rekening -->
            <div class="card border-0 bg-light p-3">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="fa-solid fa-qrcode me-1 text-danger"></i> Pembayaran QRIS (Tanpa Rekening Bank):
                </h6>
                <div class="p-3 bg-white rounded-3 border mb-2 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                        <span class="badge bg-danger px-2 py-1 fs-6 fw-bold">QRIS</span>
                        <span class="small fw-semibold text-secondary">Standar Nasional Pembayaran Digital</span>
                    </div>
                    <img src="{{ asset('uploads/' . ($config->qris ?? 'qris_indometro.svg')) }}" alt="QRIS Toko" class="img-fluid rounded border mb-2" style="max-height: 180px;">
                    <div class="small text-muted">Merchant: <strong>{{ $config->nama ?? 'PT. Indo Metro Surya Andola' }}</strong></div>
                    <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mt-2 px-3 py-1">
                        <i class="fa-solid fa-bolt me-1"></i> Tanpa perlu nomor rekening bank
                    </div>
                </div>
                <div class="small text-muted">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    Bisa di-scan dari <strong>GoPay, OVO, DANA, ShopeePay, LinkAja</strong> atau m-banking apa saja. Cukup screenshot bukti bayarnya untuk diunggah setelah checkout.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    function updateTotal() {
        const isArmada = document.getElementById('kirim_armada').checked;
        const ongkir = isArmada ? 50000 : 0;
        const total = subtotal + ongkir;

        document.getElementById('ongkirText').innerText = isArmada ? 'Rp 50.000' : 'Rp 0 (Gratis)';
        document.getElementById('grandtotalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush
@endsection

