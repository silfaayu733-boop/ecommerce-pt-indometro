<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Config;
use App\Models\Invoice;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function dashboard()
    {
        $menungguValidasi = Invoice::where('status', 1)->count();
        $hariIni = Invoice::whereDate('created_at', date('Y-m-d'))->count();
        $totalTervalidasi = Invoice::whereIn('status', [2, 3, 4])->count();
        $pesananTerbaru = Invoice::with(['user', 'bukti'])->latest()->take(10)->get();

        return view('kasir.dashboard', compact('menungguValidasi', 'hariIni', 'totalTervalidasi', 'pesananTerbaru'));
    }

    public function pesananIndex(Request $request)
    {
        $status = $request->status;
        $query = Invoice::with(['user', 'bukti', 'invoiceLogs.produk']);

        if ($status !== null && $status !== '') {
            $query->where('status', (int)$status);
        }

        $pesanan = $query->latest()->paginate(15)->withQueryString();

        return view('kasir.pesanan.index', compact('pesanan', 'status'));
    }

    public function pesananDetail(string $kodeInvoice)
    {
        $order = Invoice::with(['user', 'bukti', 'invoiceLogs.produk'])->where('kode_invoice', $kodeInvoice)->firstOrFail();
        $config = Config::first();

        return view('kasir.pesanan.detail', compact('order', 'config'));
    }

    public function validasiPembayaran(Request $request, string $kodeInvoice)
    {
        $request->validate([
            'aksi' => 'required|in:terima,tolak',
            'catatan' => 'nullable|string|max:255',
        ]);

        $order = Invoice::with('bukti')->where('kode_invoice', $kodeInvoice)->firstOrFail();

        if ($request->aksi === 'terima') {
            $order->update(['status' => 2]); // 2: Siap Diproses / Packing Gudang
            if ($order->bukti) {
                $order->bukti->update([
                    'status_verifikasi' => 'Valid',
                    'catatan_kasir' => $request->catatan ?? 'Pembayaran berhasil divalidasi kasir.',
                ]);
            }
            return back()->with('success', 'Pembayaran pesanan berhasil divalidasi! Status dialihkan ke Staff Gudang untuk packing.');
        } else {
            $order->update(['status' => 5]); // 5: Ditolak/Batal
            if ($order->bukti) {
                $order->bukti->update([
                    'status_verifikasi' => 'Ditolak',
                    'catatan_kasir' => $request->catatan ?? 'Bukti pembayaran ditolak oleh kasir.',
                ]);
            }

            // Kembalikan stok produk jika dibatalkan
            foreach ($order->invoiceLogs as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->qty);
                }
            }

            return back()->with('warning', 'Pesanan ditolak dan stok telah dikembalikan.');
        }
    }
}

