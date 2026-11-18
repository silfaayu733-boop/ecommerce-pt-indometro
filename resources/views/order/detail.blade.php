@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->kode_invoice)

@section('content')
    {{-- Audio notification for successful payment --}}
    <audio id="paymentAudio" src="{{ asset('audio/payment_success.mp3') }}" preload="auto"></audio>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const audio = document.getElementById('paymentAudio');
                if (audio) {
                    audio.play().catch(() => {});
                }
            });
        </script>
    @endif
<div class="container-fluid px-3 px-lg-5 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="{{ route('pesanan.index') }}" class="text-decoration-none small text-muted mb-2 d-inline-block">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Riwayat Pesanan
            </a>
            <h3 class="fw-bold mb-0">Pesanan <span class="text-primary font-monospace">#{{ $order->kode_invoice }}</span></h3>
            <div class="small text-muted">Dibuat pada: {{ $order->created_at->format('d F Y, H:i') }} WIB</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pesanan.print', $order->kode_invoice) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2">
                <i class="fa-solid fa-print me-1"></i> Cetak Faktur Penjualan
            </a>
        </div>
    </div>

    <!-- Status Alert Bar -->
    <div class="card border-0 mb-4 p-4 shadow-sm text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="small text-white-50 text-uppercase fw-bold mb-1">Status Transaksi Saat Ini:</div>
                <h4 class="fw-bold mb-1 text-white">
                    @if($order->status == 0)
                        <i class="fa-solid fa-qrcode me-2 text-warning"></i> Menunggu Pembayaran Scan QRIS
                    @elseif($order->status == 1)
                        <i class="fa-solid fa-hourglass-half me-2 text-info"></i> Menunggu Validasi Petugas Kasir
                    @elseif($order->status == 2)
                        <i class="fa-solid fa-boxes-packing me-2 text-primary"></i> Pembayaran Valid - Barang Sedang Dikemas Gudang
                    @elseif($order->status == 3)
                        <i class="fa-solid fa-truck-fast me-2 text-info"></i> Pesanan Sedang Dikirim ke Alamat Tujuan
                    @elseif($order->status == 4)
                        <i class="fa-solid fa-circle-check me-2 text-success"></i> Pesanan Selesai / Barang Telah Diterima
                    @else
                        <i class="fa-solid fa-circle-xmark me-2 text-danger"></i> Pesanan Dibatalkan / Ditolak
                    @endif
                </h4>
                @if($order->no_resi)
                    <div class="small text-white-50 mt-2">
                        <i class="fa-solid fa-truck me-1"></i> Nomor Resi / Sopir Pengantar: <strong class="text-white">{{ $order->no_resi }}</strong>
                    </div>
                @endif
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-light text-dark px-3 py-2 fs-6 rounded-pill">
                    Total: Rp {{ number_format($order->grandtotal, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Rincian Produk & Pengiriman -->
        <div class="col-lg-8">
            <div class="card border-0 bg-white shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Barang yang Dipesan</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk Material</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->invoiceLogs as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->produk->nama ?? 'Produk Bahan Bangunan' }}</div>
                                        <div class="small text-muted font-monospace">SKU: {{ $item->produk->kode ?? '-' }}</div>
                                    </td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold">{{ $item->qty }} {{ $item->produk->satuan ?? 'Pcs' }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal Produk:</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->grandtotal - $order->ongkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Ongkos Kirim ({{ $order->metode_pengiriman }}):</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Data Penerima & Pengiriman -->
            <div class="card border-0 bg-white shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Alamat Pengiriman</h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Nama Penerima:</div>
                        <div class="fw-bold text-dark">{{ $order->nama }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Nomor Kontak / WhatsApp:</div>
                        <div class="fw-bold text-dark">{{ $order->no_hp }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Alamat Lengkap Tujuan:</div>
                        <div class="fw-semibold text-secondary">{{ $order->alamat }}</div>
                    </div>
                    @if($order->catatan)
                        <div class="col-12">
                            <div class="text-muted small">Catatan Pesanan:</div>
                            <div class="text-secondary fst-italic">"{{ $order->catatan }}"</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bagian Komplain / Pengaduan -->
            @if(in_array($order->status, [3, 4]))
                <div class="card border-0 bg-white shadow-sm p-4">
                    <h5 class="fw-bold mb-2"><i class="fa-solid fa-headset me-2 text-warning"></i> Layanan Komplain Pesanan</h5>
                    <p class="small text-muted mb-4">Apabila ada barang cacat, jumlah kurang, atau rusak selama pengiriman, Anda dapat mengirimkan keluhan di bawah ini.</p>

                    @if($order->komplens->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="fw-bold small text-uppercase text-secondary">Riwayat Komplain Anda:</h6>
                            @foreach($order->komplens as $komp)
                                <div class="p-3 bg-light rounded-3 mb-2 border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-secondary">{{ $komp->created_at->format('d M Y, H:i') }}</span>
                                        <span class="badge {{ $komp->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $komp->status }}</span>
                                    </div>
                                    <p class="mb-2 text-dark">{{ $komp->isi }}</p>
                                    @if($komp->tanggapan)
                                        <div class="p-2 bg-white rounded border border-success text-success small">
                                            <strong>Tanggapan Toko:</strong> {{ $komp->tanggapan }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('pesanan.komplain', $order->kode_invoice) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tuliskan Keluhan Anda</label>
                            <textarea name="isi" rows="3" class="form-control" placeholder="Jelaskan kendala atau kerusakan barang secara spesifik..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Unggah Foto Bukti Kerusakan (Opsional)</label>
                            <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm px-4 rounded-pill fw-bold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Keluhan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Upload Bukti Bayar QRIS / Tanpa Rekening -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white shadow-sm p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h5 class="fw-bold mb-0">Pembayaran QRIS</h5>
                    <span class="badge bg-danger px-2 py-1 fw-bold">QRIS BI</span>
                </div>

                @if($order->bukti)
                    <div class="mb-3 text-center">
                        <div class="p-2 border rounded-3 bg-light mb-2">
                            <span class="badge {{ $order->bukti->status_verifikasi === 'Valid' ? 'bg-success' : ($order->bukti->status_verifikasi === 'Ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} mb-2">
                                Status Verifikasi: {{ $order->bukti->status_verifikasi }}
                            </span>
                            <div class="small text-muted">Aplikasi / E-Wallet: <strong>{{ $order->bukti->bank }}</strong></div>
                            <div class="small text-muted">Akun / No. HP: <strong>{{ $order->bukti->no_rek }}</strong></div>
                            <div class="small text-muted">Nominal: <strong>Rp {{ number_format($order->bukti->nominal, 0, ',', '.') }}</strong></div>
                        </div>

                        @if(file_exists(public_path('uploads/bukti/' . $order->bukti->file)))
                            <a href="{{ asset('uploads/bukti/' . $order->bukti->file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 rounded-pill mb-2">
                                <i class="fa-solid fa-eye me-1"></i> Lihat Foto Bukti Bayar QRIS
                            </a>
                        @endif

                        @if($order->bukti->catatan_kasir)
                            <div class="alert alert-info small text-start p-2 mb-0">
                                <strong>Catatan Kasir:</strong> {{ $order->bukti->catatan_kasir }}
                            </div>
                        @endif
                    </div>
                @endif

                @if($order->status == 0 || ($order->bukti && $order->bukti->status_verifikasi === 'Ditolak'))
                    <div class="alert alert-warning small mb-3">
                        <i class="fa-solid fa-bolt text-danger me-1"></i> Silakan bayar sebesar <strong>Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</strong> dengan scan barcode QRIS di bawah ini:
                    </div>

                    {{-- ===== TOMBOL DEMO PAYMENT ===== --}}
                    <div class="card border-warning border-2 bg-warning bg-opacity-10 p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-flask me-1"></i> MODE DEMO</span>
                            <span class="fw-bold small text-dark">Simulasi Pembayaran Instan</span>
                        </div>
                        <p class="small text-muted mb-2">
                            Khusus untuk keperluan <strong>demonstrasi / presentasi skripsi</strong>. Klik tombol di bawah untuk langsung mensimulasikan pembayaran berhasil tanpa perlu scan QRIS atau upload file.
                        </p>
                        <form action="{{ route('pesanan.demo_bayar', $order->kode_invoice) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill py-2 shadow-sm"
                                onclick="return confirm('Konfirmasi pembayaran demo sebesar Rp {{ number_format($order->grandtotal, 0, ',', '.') }}?')">
                                <i class="fa-solid fa-bolt me-1"></i> Bayar Sekarang (Demo — Tanpa Upload)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <hr class="flex-grow-1">
                        <span class="text-muted small px-2">atau upload bukti nyata di bawah</span>
                        <hr class="flex-grow-1">
                    </div>

                    <!-- QRIS Barcode Image -->
                    <div class="p-2 bg-light rounded-3 mb-3 border text-center">
                        <img src="{{ asset('uploads/' . ($config->qris ?? 'qris_indometro.svg')) }}" alt="Barcode QRIS Toko" class="img-fluid rounded border shadow-sm mb-2" style="max-height: 280px; width: 100%; object-fit: contain;">
                        <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 mb-1">
                            <i class="fa-solid fa-shield-halved me-1"></i> Satu QR untuk Semua E-Wallet & Bank
                        </div>
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            Bisa scan pakai <strong>GoPay, OVO, DANA, ShopeePay, LinkAja</strong> atau m-banking apa saja (tanpa perlu rekening bank).
                        </div>
                    </div>

                    <form action="{{ route('pesanan.upload_bukti', $order->kode_invoice) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Aplikasi / E-Wallet yang Digunakan</label>
                            <select name="bank" class="form-select form-select-sm" required>
                                <option value="" disabled selected>-- Pilih Aplikasi Pembayaran --</option>
                                <option value="QRIS - DANA">DANA (E-Wallet)</option>
                                <option value="QRIS - GoPay">GoPay (Gojek)</option>
                                <option value="QRIS - OVO">OVO (E-Wallet)</option>
                                <option value="QRIS - ShopeePay">ShopeePay</option>
                                <option value="QRIS - LinkAja">LinkAja!</option>
                                <option value="QRIS - BCA Mobile">BCA Mobile / myBCA</option>
                                <option value="QRIS - Livin Mandiri">Livin' by Mandiri</option>
                                <option value="QRIS - BRImo">BRImo (BRI)</option>
                                <option value="QRIS - BNI Mobile">BNI Mobile Banking</option>
                                <option value="QRIS - SeaBank / Lainnya">SeaBank / Bank Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Nama Akun / No. HP E-Wallet Pembayar</label>
                            <input type="text" name="no_rek" class="form-control form-control-sm" placeholder="contoh: 081234567890 (Budi)" required>
                            <div class="form-text" style="font-size: 0.7rem;">Tidak butuh nomor rekening bank</div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary">Nominal yang Dibayarkan (Rp)</label>
                            <input type="number" name="nominal" class="form-control form-control-sm" value="{{ $order->grandtotal }}" required readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">File Screenshot / Struk Bukti Bayar QRIS</label>
                            <input type="file" name="file" class="form-control form-control-sm" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                            <i class="fa-solid fa-upload me-1"></i> Unggah Bukti Bayar QRIS
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

