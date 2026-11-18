<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bukti extends Model
{
    protected $table = 'bukti';

    protected $fillable = [
        'id_invoice',
        'kode_invoice',
        'no_rek',
        'bank',
        'nominal',
        'file',
        'status_verifikasi',
        'catatan_kasir',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }
}

