<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// --- Publik / Frontend ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{id}', [HomeController::class, 'detail'])->name('produk.detail');
Route::get('/kontak', [HomeController::class, 'contact'])->name('kontak');

// --- Auth Routes ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Customer Area (Membutuhkan Login) ---
Route::middleware('auth')->group(function () {
    // Keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::post('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/proses', [CartController::class, 'processCheckout'])->name('checkout.process');

    // Pesanan Saya
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{kode}', [OrderController::class, 'detail'])->name('pesanan.detail');
    Route::post('/pesanan/{kode}/bukti', [OrderController::class, 'uploadBukti'])->name('pesanan.upload_bukti');
    Route::post('/pesanan/{kode}/demo-bayar', [OrderController::class, 'demoPay'])->name('pesanan.demo_bayar');
    Route::post('/pesanan/{kode}/komplain', [OrderController::class, 'kirimKomplain'])->name('pesanan.komplain');
    Route::get('/pesanan/{kode}/cetak', [OrderController::class, 'printInvoice'])->name('pesanan.print');
});

// --- Role 1: Admin / Owner ---
Route::middleware(['auth', 'role:1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen Produk
    Route::get('/produk', [AdminController::class, 'produkIndex'])->name('produk.index');
    Route::post('/produk/simpan', [AdminController::class, 'produkStore'])->name('produk.store');
    Route::put('/produk/update/{id}', [AdminController::class, 'produkUpdate'])->name('produk.update');
    Route::delete('/produk/hapus/{id}', [AdminController::class, 'produkDestroy'])->name('produk.destroy');

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporanPenjualan'])->name('laporan.index');

    // Pengaturan Toko
    Route::get('/config', [AdminController::class, 'configEdit'])->name('config.edit');
    Route::post('/config/update', [AdminController::class, 'configUpdate'])->name('config.update');
});

// --- Role 2: Kasir ---
Route::middleware(['auth', 'role:2'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
    Route::get('/pesanan', [KasirController::class, 'pesananIndex'])->name('pesanan.index');
    Route::get('/pesanan/{kode}', [KasirController::class, 'pesananDetail'])->name('pesanan.detail');
    Route::post('/pesanan/{kode}/validasi', [KasirController::class, 'validasiPembayaran'])->name('pesanan.validasi');
});

// --- Role 3: Admin Gudang ---
Route::middleware(['auth', 'role:3'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangController::class, 'dashboard'])->name('dashboard');

    // Stok Masuk
    Route::get('/stok-masuk', [GudangController::class, 'stokMasukIndex'])->name('stok.index');
    Route::post('/stok-masuk/simpan', [GudangController::class, 'stokMasukStore'])->name('stok.store');

    // Packing & Pengiriman
    Route::get('/pengiriman', [GudangController::class, 'pengirimanIndex'])->name('pengiriman.index');
    Route::post('/pengiriman/{kode}/proses', [GudangController::class, 'prosesKirim'])->name('pengiriman.proses');
    Route::post('/pengiriman/{kode}/selesai', [GudangController::class, 'konfirmasiSelesai'])->name('pengiriman.selesai');
    Route::get('/pengiriman/{kode}/surat-jalan', [GudangController::class, 'suratJalan'])->name('pengiriman.surat_jalan');
});
