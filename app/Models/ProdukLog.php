<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukLog extends Model
{
    protected $table = 'produk_log';

    protected $fillable = [
        'id_user',
        'nama',
        'kode',
        'stok_awal',
        'harga_awal',
        'keterangan',
    ];

    protected $casts = [
        'stok_awal' => 'float',
        'harga_awal' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

