<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kategori Produk
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });

        // 2. Produk Bahan Bangunan
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->nullable()->constrained('kategori')->nullOnDelete()->cascadeOnUpdate();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->string('satuan', 50)->default('Pcs');
            $table->string('image', 255)->default('default.png');
            $table->text('deskripsi');
            $table->decimal('stok', 10, 2)->default(0);
            $table->decimal('harga', 12, 2)->default(0);
            $table->timestamps();
        });

        // 3. Produk Masuk (Pasokan Stok Gudang)
        Schema::create('produk_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('faktur', 255);
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnUpdate();
            $table->decimal('qty', 10, 2);
            $table->timestamps();
        });

        // 4. Produk Log (Audit Trail Perubahan Produk)
        Schema::create('produk_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnUpdate();
            $table->string('nama', 255);
            $table->string('kode', 50);
            $table->decimal('stok_awal', 10, 2);
            $table->decimal('harga_awal', 12, 2);
            $table->string('keterangan', 50);
            $table->timestamps();
        });

        // 5. Invoice (Pesanan Utama)
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice', 255)->unique();
            $table->foreignId('id_user')->constrained('users')->cascadeOnUpdate();
            $table->string('nama', 255);
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->string('metode_pengiriman', 50)->default('Ambil Sendiri');
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('grandtotal', 12, 2);
            $table->integer('status')->default(0)->comment('0: Menunggu Bayar, 1: Menunggu Validasi Kasir, 2: Diproses/Packing Gudang, 3: Dikirim, 4: Selesai, 5: Batal');
            $table->string('no_resi', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 6. Invoice Log (Rincian Item Pembelian)
        Schema::create('invoice_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->constrained('invoice')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_invoice', 255);
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnUpdate();
            $table->decimal('qty', 10, 2);
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });

        // 7. Produk Keluar (Sinkronisasi Barang Keluar)
        Schema::create('produk_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->nullable()->constrained('invoice')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_invoice', 255);
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnUpdate();
            $table->decimal('harga', 12, 2);
            $table->decimal('qty', 10, 2);
            $table->timestamps();
        });

        // 8. Bukti Pembayaran
        Schema::create('bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->nullable()->constrained('invoice')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_invoice', 255);
            $table->string('no_rek', 50);
            $table->string('bank', 50);
            $table->decimal('nominal', 12, 2)->nullable();
            $table->string('file', 255);
            $table->enum('status_verifikasi', ['Menunggu', 'Valid', 'Ditolak'])->default('Menunggu');
            $table->string('catatan_kasir', 255)->nullable();
            $table->timestamps();
        });

        // 9. Komplen (Pengaduan)
        Schema::create('komplen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_invoice')->nullable()->constrained('invoice')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_invoice', 255);
            $table->foreignId('id_user')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->text('isi');
            $table->string('foto_bukti', 255)->nullable();
            $table->enum('status', ['Pending', 'Diproses', 'Selesai'])->default('Pending');
            $table->text('tanggapan')->nullable();
            $table->timestamps();
        });

        // 10. Config (Informasi Toko, Banner, Rekening)
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('logo', 255)->default('logo.png');
            $table->text('header')->nullable();
            $table->text('map')->nullable();
            $table->string('banner', 255)->default('banner.jpg');
            $table->string('nama', 255)->default('PT. Indo Metro Surya Andola');
            $table->text('alamat');
            $table->string('email', 255);
            $table->string('telp', 100);
            $table->string('no_rek', 100);
            $table->string('bank', 100);
            $table->timestamps();
        });

        // 11. Keranjang Belanja Pelanggan (Carts)
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->decimal('qty', 10, 2)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('config');
        Schema::dropIfExists('komplen');
        Schema::dropIfExists('bukti');
        Schema::dropIfExists('produk_keluar');
        Schema::dropIfExists('invoice_log');
        Schema::dropIfExists('invoice');
        Schema::dropIfExists('produk_log');
        Schema::dropIfExists('produk_masuk');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
    }
};
