@extends('layouts.admin')

@section('title', 'Pemeriksaan Pembayaran Pesanan #' . $order->kode_invoice)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('kasir.pesanan.index') }}" class="small text-muted text-decoration-none mb-1 d-inline-block">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Kasir
        </a>
        <h3 class="fw-bold mb-0">Pemeriksaan Pesanan <span class="text-primary font-monospace">#{{ $order->kode_invoice }}</span></h3>
    </div>
    <a href="{{ route('pesanan.print', $order->kode_invoice) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2">
        <i class="fa-solid fa-print me-1"></i> Cetak Struk / Nota Kasir
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Verifikasi Bukti Pembayaran -->
    <div class="col-lg-5">
        <div class="card border-0 bg-white shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">
                <i class="fa-solid fa-qrcode text-danger me-2"></i> Bukti Pembayaran QRIS Customer
            </h5>

            @if($order->bukti)
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Aplikasi / E-Wallet:</span>
                        <strong class="text-dark">{{ $order->bukti->bank }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Akun / No. HP Pembayar:</span>
                        <span class="font-monospace fw-bold">{{ $order->bukti->no_rek }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Nominal Dibayarkan:</span>
                        <strong class="text-primary fs-6">Rp {{ number_format($order->bukti->nominal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="text-muted small">Total Tagihan Sistem:</span>
                        <strong class="text-danger fs-6">Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <div class="p-2 border rounded-3 bg-light">
                        @if($order->bukti->file === 'demo_payment.png')
                            {{-- Tampilan khusus pembayaran demo --}}
                            <div class="py-4 px-3">
                                <div class="mb-3">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-flask me-1"></i> PEMBAYARAN DEMO / SIMULASI
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    <i class="fa-solid fa-circle-info me-1 text-info"></i>
                                    Customer menggunakan <strong>mode demo</strong> (simulasi pembayaran untuk keperluan presentasi/skripsi). Tidak ada bukti foto nyata.
                                </div>
                                <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded border border-warning">
                                    <div class="small text-dark fw-bold">Nominal yang diklaim customer:</div>
                                    <div class="fs-5 fw-bold text-primary">Rp {{ number_format($order->bukti->nominal, 0, ',', '.') }}</div>
                                    <div class="small text-muted">Pastikan sesuai dengan tagihan sebelum menerima.</div>
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('uploads/bukti/' . $order->bukti->file) }}" class="img-fluid rounded" alt="Bukti Transfer" style="max-height: 380px; object-fit: contain;">
                        @endif
                    </div>
                    @if($order->bukti->file !== 'demo_payment.png')
                        <a href="{{ asset('uploads/bukti/' . $order->bukti->file) }}" target="_blank" class="small text-primary d-inline-block mt-2">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Gambar Ukuran Penuh
                        </a>
                    @endif
                </div>

                <!-- Form Aksi Validasi Kasir -->
                @if($order->status == 1 || $order->status == 0)
                    <div class="p-3 border rounded-3 bg-white mt-3">
                        <h6 class="fw-bold text-dark mb-3">Tindakan Petugas Kasir:</h6>
                        <form action="{{ route('kasir.pesanan.validasi', $order->kode_invoice) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Catatan / Keterangan (Opsional)</label>
                                <input type="text" name="catatan" class="form-control form-control-sm" placeholder="contoh: Dana telah masuk di rekening BCA toko.">
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="submit" name="aksi" value="terima" class="btn btn-success w-100 py-2 fw-bold">
                                        <i class="fa-solid fa-check me-1"></i> Terima & Valid
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="aksi" value="tolak" class="btn btn-danger w-100 py-2 fw-bold" onclick="return confirm('Tolak bukti pembayaran ini? Stok akan dikembalikan ke produk.');">
                                        <i class="fa-solid fa-xmark me-1"></i> Tolak Bukti
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info small mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i> Pesanan ini sudah diproses dengan status: <strong>{{ $order->status_label }}</strong>.
                    </div>
                @endif
            @else
                <div class="alert alert-warning small mb-0">
                    <i class="fa-solid fa-clock me-1"></i> Customer belum mengunggah bukti transfer untuk pesanan ini.
                </div>
            @endif
        </div>
    </div>

    <!-- Kolom Kanan: Rincian Pesanan & Pembeli -->
    <div class="col-lg-7">
        <div class="card border-0 bg-white shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Pembeli & Pengiriman</h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="small text-muted">Nama Customer:</div>
                    <div class="fw-bold text-dark">{{ $order->nama }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-muted">Nomor WhatsApp:</div>
                    <div class="fw-bold text-dark">{{ $order->no_hp }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-muted">Metode Pengiriman:</div>
                    <div><span class="badge bg-primary">{{ $order->metode_pengiriman }}</span></div>
                </div>
                <div class="col-sm-6">
                    <div class="small text-muted">Biaya Ongkir:</div>
                    <div class="fw-bold text-dark">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</div>
                </div>
                <div class="col-12">
                    <div class="small text-muted">Alamat Tujuan Pengiriman:</div>
                    <div class="fw-semibold text-secondary">{{ $order->alamat }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-white shadow-sm p-4">
            <h5 class="fw-bold mb-3 border-bottom pb-2">Item Barang yang Dibeli</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->invoiceLogs as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item->produk->nama ?? '-' }}</div>
                                    <div class="small text-muted font-monospace">{{ $item->produk->kode ?? '-' }}</div>
                                </td>
                                <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center fw-bold">{{ $item->qty }} {{ $item->produk->satuan ?? 'Pcs' }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-6">Grand Total Lunas:</td>
                            <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

