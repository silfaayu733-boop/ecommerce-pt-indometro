<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Invoice;
use App\Models\Produk;
use App\Models\ProdukLog;
use App\Models\ProdukMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function dashboard()
    {
        $perluPacking = Invoice::where('status', 2)->count();
        $sedangDikirim = Invoice::where('status', 3)->count();
        $stokMenipis = Produk::where('stok', '<=', 10)->get();
        $pesananGudang = Invoice::with(['user', 'invoiceLogs.produk'])->whereIn('status', [2, 3])->latest()->get();

        return view('gudang.dashboard', compact('perluPacking', 'sedangDikirim', 'stokMenipis', 'pesananGudang'));
    }

    // --- Input Stok Masuk Gudang ---
    public function stokMasukIndex()
    {
        $riwayatMasuk = ProdukMasuk::with('produk')->latest()->paginate(15);
        $produks = Produk::orderBy('nama')->get();

        return view('gudang.stok.index', compact('riwayatMasuk', 'produks'));
    }

    public function stokMasukStore(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'faktur' => 'required|string|max:255',
            'qty' => 'required|numeric|min:0.5',
        ]);

        $produk = Produk::findOrFail($request->id_produk);
        $stokAwal = $produk->stok;

        DB::beginTransaction();
        try {
            ProdukMasuk::create([
                'faktur' => $request->faktur,
                'id_produk' => $produk->id,
                'qty' => $request->qty,
            ]);

            // Tambah stok fisik di tabel produk
            $produk->increment('stok', $request->qty);

            // Catat log
            ProdukLog::create([
                'id_user' => Auth::id(),
                'nama' => $produk->nama,
                'kode' => $produk->kode,
                'stok_awal' => $stokAwal,
                'harga_awal' => $produk->harga,
                'keterangan' => 'Pasokan Masuk Faktur #' . $request->faktur,
            ]);

            DB::commit();

            return back()->with('success', "Stok {$produk->nama} berhasil ditambahkan sebanyak {$request->qty} {$produk->satuan}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }

    // --- Pengiriman & Packing ---
    public function pengirimanIndex()
    {
        $pesananPacking = Invoice::with(['user', 'invoiceLogs.produk'])->where('status', 2)->latest()->get();
        $pesananDikirim = Invoice::with(['user', 'invoiceLogs.produk'])->where('status', 3)->latest()->get();

        return view('gudang.pengiriman.index', compact('pesananPacking', 'pesananDikirim'));
    }

    public function prosesKirim(Request $request, string $kodeInvoice)
    {
        $request->validate([
            'no_resi' => 'required|string|max:100',
        ]);

        $order = Invoice::where('kode_invoice', $kodeInvoice)->firstOrFail();
        $order->update([
            'status' => 3, // 3: Sedang Dikirim
            'no_resi' => $request->no_resi,
        ]);

        return back()->with('success', "Pesanan #{$kodeInvoice} berhasil diperbarui menjadi Sedang Dikirim dengan No Resi/Armada: {$request->no_resi}");
    }

    public function konfirmasiSelesai(string $kodeInvoice)
    {
        $order = Invoice::where('kode_invoice', $kodeInvoice)->firstOrFail();
        $order->update(['status' => 4]); // 4: Selesai

        return back()->with('success', "Pesanan #{$kodeInvoice} telah berhasil ditandai Selesai / Diterima.");
    }

    public function suratJalan(string $kodeInvoice)
    {
        $order = Invoice::with(['user', 'invoiceLogs.produk'])->where('kode_invoice', $kodeInvoice)->firstOrFail();
        $config = Config::first();

        return view('gudang.pengiriman.surat_jalan', compact('order', 'config'));
    }
}

