<?php

namespace App\Http\Controllers;

use App\Models\Bukti;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\Komplen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Invoice::with(['invoiceLogs.produk', 'bukti'])
            ->where('id_user', Auth::id())
            ->latest()
            ->paginate(10);
        $config = Config::first();

        return view('order.index', compact('orders', 'config'));
    }

    public function detail(string $kodeInvoice)
    {
        $order = Invoice::with(['invoiceLogs.produk', 'bukti', 'komplens.user'])
            ->where('kode_invoice', $kodeInvoice)
            ->where(function ($q) {
                // Customer hanya bisa lihat miliknya, admin/kasir/gudang bisa lihat semua
                if (Auth::user()->role_id == 4) {
                    $q->where('id_user', Auth::id());
                }
            })
            ->firstOrFail();

        $config = Config::first();

        return view('order.detail', compact('order', 'config'));
    }

    public function uploadBukti(Request $request, string $kodeInvoice)
    {
        $request->validate([
            'no_rek' => 'required|string|max:50',
            'bank' => 'required|string|max:50',
            'nominal' => 'required|numeric|min:1000',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $order = Invoice::where('kode_invoice', $kodeInvoice)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $fileName = 'bukti_' . $order->kode_invoice . '_' . time() . '.' . $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move(public_path('uploads/bukti'), $fileName);

        Bukti::updateOrCreate(
            ['id_invoice' => $order->id],
            [
                'kode_invoice' => $order->kode_invoice,
                'no_rek' => $request->no_rek,
                'bank' => $request->bank,
                'nominal' => $request->nominal,
                'file' => $fileName,
                'status_verifikasi' => 'Menunggu',
            ]
        );

        // Update status order: 1 = Menunggu Validasi Kasir
        $order->update(['status' => 1]);

        return back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi dari petugas kasir.');
    }

    public function kirimKomplain(Request $request, string $kodeInvoice)
    {
        $request->validate([
            'isi' => 'required|string|max:1000',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $order = Invoice::where('kode_invoice', $kodeInvoice)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $fotoNama = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoNama = 'komplain_' . $order->kode_invoice . '_' . time() . '.' . $request->file('foto_bukti')->getClientOriginalExtension();
            $request->file('foto_bukti')->move(public_path('uploads/komplain'), $fotoNama);
        }

        Komplen::create([
            'id_invoice' => $order->id,
            'kode_invoice' => $order->kode_invoice,
            'id_user' => Auth::id(),
            'isi' => $request->isi,
            'foto_bukti' => $fotoNama,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Keluhan/komplain Anda telah dikirim dan akan segera ditinjau oleh pihak toko.');
    }

    public function demoPay(Request $request, string $kodeInvoice)
    {
        $order = Invoice::where('kode_invoice', $kodeInvoice)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($order->status != 0) {
            return back()->with('error', 'Pesanan ini sudah diproses, tidak bisa dibayar ulang.');
        }

        // Buat bukti dummy tanpa file upload
        Bukti::updateOrCreate(
            ['id_invoice' => $order->id],
            [
                'kode_invoice'      => $order->kode_invoice,
                'no_rek'            => 'DEMO-081234567890',
                'bank'              => 'DEMO - Simulasi Pembayaran QRIS',
                'nominal'           => $order->grandtotal,
                'file'              => 'demo_payment.png',
                'status_verifikasi' => 'Menunggu',
            ]
        );

        // Ubah status ke 1 = Menunggu Validasi Kasir
        $order->update(['status' => 1]);

        return back()->with('success', '✅ Pembayaran demo berhasil! Pesanan Anda sedang menunggu verifikasi kasir.');
    }

    public function printInvoice(string $kodeInvoice)
    {
        $order = Invoice::with(['invoiceLogs.produk', 'bukti', 'user'])
            ->where('kode_invoice', $kodeInvoice)
            ->firstOrFail();
        $config = Config::first();

        return view('order.print', compact('order', 'config'));
    }
}

