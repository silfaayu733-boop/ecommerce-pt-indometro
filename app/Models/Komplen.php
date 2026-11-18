<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Komplen extends Model
{
    protected $table = 'komplen';

    protected $fillable = [
        'id_invoice',
        'kode_invoice',
        'id_user',
        'isi',
        'foto_bukti',
        'status',
        'tanggapan',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id_invoice');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

