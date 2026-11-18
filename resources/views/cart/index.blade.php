<!-- @extends('layouts.app') -->

@section('title', 'Keranjang Belanja - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">
    <h3 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping me-2 text-primary"></i> Keranjang Belanja</h3>

    @if($carts->isEmpty())
        <div class="card p-5 text-center border-0 bg-white shadow-sm">
            <i class="fa-solid fa-cart-arrow-down fs-1 text-muted mb-3 opacity-50"></i>
            <h5 class="fw-bold">Keranjang Belanja Anda Masih Kosong</h5>
            <p class="text-muted small">Silakan pilih produk bahan bangunan yang Anda butuhkan di katalog kami.</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-sm mx-auto rounded-pill px-4 py-2 mt-2">
                <i class="fa-solid fa-magnifying-glass me-1"></i> Jelajah Katalog Produk
            </a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 bg-white shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th style="width: 150px;">Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->produk->image && file_exists(public_path('uploads/produk/' . $item->produk->image)))
                                                    <img src="{{ asset('uploads/produk/' . $item->produk->image) }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;" alt="{{ $item->produk->nama }}">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->produk->nama }}</div>
                                                    <div class="small text-muted">Kode: {{ $item->produk->kode }} | Satuan: {{ $item->produk->satuan }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="number" name="qty" class="form-control form-control-sm text-center fw-bold" value="{{ $item->qty }}" min="1" max="{{ $item->produk->stok }}" style="width: 70px;">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Jumlah"><i class="fa-solid fa-check"></i></button>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format($item->qty * $item->produk->harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 bg-white shadow-sm p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Total Harga Barang</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="small text-muted mb-3">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i> Ongkos kirim armada toko akan dikalkulasi di langkah checkout berikutnya.
                    </div>
                    <div class="d-grid gap-2 pt-2 border-top">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary py-2 rounded-pill fw-bold shadow-sm">
                            <i class="fa-solid fa-cash-register me-1"></i> Lanjut ke Checkout
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill py-2">
                            Tambah Produk Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

