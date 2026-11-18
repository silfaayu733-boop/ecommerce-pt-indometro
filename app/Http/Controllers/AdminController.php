<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Invoice;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\ProdukLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPendapatan = Invoice::where('status', 4)->sum('grandtotal');
        $pesananMasuk = Invoice::where('status', 1)->count();
        $pesananDiproses = Invoice::whereIn('status', [2, 3])->count();
        $pesananSelesai = Invoice::where('status', 4)->count();
        $totalProduk = Produk::count();
        $stokMenipis = Produk::where('stok', '<=', 10)->count();

        $pesananTerbaru = Invoice::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalPendapatan',
            'pesananMasuk',
            'pesananDiproses',
            'pesananSelesai',
            'totalProduk',
            'stokMenipis',
            'pesananTerbaru'
        ));
    }

    // --- Produk Management ---
    public function produkIndex(Request $request)
    {
        $query = Produk::with('kategori');
        if ($request->filled('q')) {
            $query->where('nama', 'like', "%{$request->q}%")->orWhere('kode', 'like', "%{$request->q}%");
        }
        $produks = $query->latest()->paginate(15);
        $kategoris = Kategori::all();

        return view('admin.produk.index', compact('produks', 'kategoris'));
    }

    public function produkStore(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:produk,kode',
            'nama' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = 'default.png';
        if ($request->hasFile('image')) {
            $imageName = 'produk_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/produk'), $imageName);
        }

        $produk = Produk::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'id_kategori' => $request->id_kategori,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'image' => $imageName,
        ]);

        // Catat ke produk_log
        ProdukLog::create([
            'id_user' => Auth::id(),
            'nama' => $produk->nama,
            'kode' => $produk->kode,
            'stok_awal' => $produk->stok,
            'harga_awal' => $produk->harga,
            'keterangan' => 'Tambah Produk Baru',
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function produkUpdate(Request $request, int $id)
    {
        $produk = Produk::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:50|unique:produk,kode,' . $produk->id,
            'nama' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'id_kategori' => $request->id_kategori,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('image')) {
            $imageName = 'produk_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/produk'), $imageName);
            $data['image'] = $imageName;
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function produkDestroy(int $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    // --- Laporan Penjualan ---
    public function laporanPenjualan(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');

        $laporan = Invoice::with(['user', 'invoiceLogs.produk'])
            ->whereDate('created_at', '>=', $tglAwal)
            ->whereDate('created_at', '<=', $tglAkhir)
            ->where('status', 4) // Lunas/Selesai
            ->latest()
            ->get();

        $totalOmzet = $laporan->sum('grandtotal');
        $totalTransaksi = $laporan->count();

        if ($request->has('print')) {
            $config = Config::first();
            return view('admin.laporan.print', compact('laporan', 'tglAwal', 'tglAkhir', 'totalOmzet', 'totalTransaksi', 'config'));
        }

        return view('admin.laporan.index', compact('laporan', 'tglAwal', 'tglAkhir', 'totalOmzet', 'totalTransaksi'));
    }

    // --- Config Pengaturan Toko ---
    public function configEdit()
    {
        $config = Config::firstOrCreate(['id' => 1]);
        return view('admin.config', compact('config'));
    }

    public function configUpdate(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'telp' => 'required|string|max:50',
            'no_rek' => 'required|string|max:100',
            'bank' => 'required|string|max:100',
            'header' => 'nullable|string',
            'map' => 'nullable|string',
            'qris' => 'nullable|file|mimes:jpeg,png,jpg,svg|max:3072',
        ]);

        $config = Config::firstOrCreate(['id' => 1]);
        $config->update($request->only('nama', 'alamat', 'email', 'telp', 'no_rek', 'bank', 'header', 'map'));

        if ($request->hasFile('qris')) {
            $qrisName = 'qris_' . time() . '.' . $request->file('qris')->getClientOriginalExtension();
            $request->file('qris')->move(public_path('uploads'), $qrisName);
            $config->update(['qris' => $qrisName]);
        }

        return back()->with('success', 'Pengaturan toko dan QRIS pembayaran berhasil diperbarui.');
    }
}

