<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use App\Models\Produk;
use App\Models\ProdukKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $carts = Cart::with('produk')->where('user_id', $userId)->get();
        $subtotal = $carts->sum(fn($c) => $c->qty * $c->produk->harga);
        $config = Config::first();

        return view('cart.index', compact('carts', 'subtotal', 'config'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'qty' => 'required|numeric|min:0.1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $qty = (float) $request->qty;

        if ($produk->stok < $qty) {
            return back()->with('error', 'Mohon maaf, stok tersedia hanya ' . $produk->stok . ' ' . $produk->satuan);
        }

        $cart = Cart::firstOrNew([
            'user_id' => Auth::id(),
            'produk_id' => $produk->id,
        ]);

        $newQty = ($cart->exists ? $cart->qty : 0) + $qty;
        if ($produk->stok < $newQty) {
            return back()->with('error', 'Total di keranjang melebihi stok yang tersedia (' . $produk->stok . ' ' . $produk->satuan . ')');
        }

        $cart->qty = $newQty;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang belanja.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'qty' => 'required|numeric|min:0.1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        if ($cart->produk->stok < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
        }

        $cart->qty = $request->qty;
        $cart->save();

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function remove(int $id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function checkout()
    {
        $user = Auth::user();
        $carts = Cart::with('produk')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = $carts->sum(fn($c) => $c->qty * $c->produk->harga);
        $config = Config::first();

        return view('cart.checkout', compact('user', 'carts', 'subtotal', 'config'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pengiriman' => 'required|in:Ambil Sendiri,Armada Toko',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $carts = Cart::with('produk')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Cek stok validasi
        foreach ($carts as $item) {
            if ($item->produk->stok < $item->qty) {
                return redirect()->route('cart.index')->with('error', "Stok {$item->produk->nama} tidak mencukupi.");
            }
        }

        $subtotal = $carts->sum(fn($c) => $c->qty * $c->produk->harga);
        // Ongkir flat armada toko untuk Metro & sekitar: Rp 50.000 (jika armada toko), Rp 0 jika ambil sendiri
        $ongkir = $request->metode_pengiriman === 'Armada Toko' ? 50000 : 0;
        $grandtotal = $subtotal + $ongkir;

        $kodeInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        DB::beginTransaction();
        try {
            // 1. Simpan Invoice
            $invoice = Invoice::create([
                'kode_invoice' => $kodeInvoice,
                'id_user' => $user->id,
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'metode_pengiriman' => $request->metode_pengiriman,
                'ongkir' => $ongkir,
                'grandtotal' => $grandtotal,
                'status' => 0, // 0: Menunggu Bayar
                'catatan' => $request->catatan,
            ]);

            // 2. Simpan Detail Item ke invoice_log & produk_keluar
            foreach ($carts as $item) {
                InvoiceLog::create([
                    'id_invoice' => $invoice->id,
                    'kode_invoice' => $kodeInvoice,
                    'id_produk' => $item->produk_id,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga,
                ]);

                ProdukKeluar::create([
                    'id_invoice' => $invoice->id,
                    'kode_invoice' => $kodeInvoice,
                    'id_produk' => $item->produk_id,
                    'harga' => $item->produk->harga,
                    'qty' => $item->qty,
                ]);

                // Kurangi stok barang
                $item->produk->decrement('stok', $item->qty);
            }

            // 3. Bersihkan keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanan.detail', $invoice->kode_invoice)
                ->with('success', 'Pesanan Anda berhasil dibuat! Silakan lakukan transfer pembayaran dan unggah bukti transfer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan pemesanan: ' . $e->getMessage());
        }
    }
}

