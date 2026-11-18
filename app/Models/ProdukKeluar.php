<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukKeluar extends Model
{
    protected $table = 'produk_keluar';

    protected $fillable = [
        'id_invoice',
        'kode_invoice',
        'id_produk',
        'harga',
        'qty',
    ];

    protected $casts = [
        'harga' => 'float',
        'qty' => 'float',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}

