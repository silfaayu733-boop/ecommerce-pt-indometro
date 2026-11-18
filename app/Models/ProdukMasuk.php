<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukMasuk extends Model
{
    protected $table = 'produk_masuk';

    protected $fillable = [
        'faktur',
        'id_produk',
        'qty',
    ];

    protected $casts = [
        'qty' => 'float',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}

