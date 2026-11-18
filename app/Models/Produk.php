<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'id_kategori',
        'kode',
        'nama',
        'satuan',
        'image',
        'deskripsi',
        'stok',
        'harga',
    ];

    protected $casts = [
        'stok' => 'float',
        'harga' => 'float',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function produkMasuk(): HasMany
    {
        return $this->hasMany(ProdukMasuk::class, 'id_produk');
    }

    public function produkKeluar(): HasMany
    {
        return $this->hasMany(ProdukKeluar::class, 'id_produk');
    }

    public function invoiceLogs(): HasMany
    {
        return $this->hasMany(InvoiceLog::class, 'id_produk');
    }
}

