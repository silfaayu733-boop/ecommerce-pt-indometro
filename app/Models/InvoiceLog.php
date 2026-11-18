<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLog extends Model
{
    protected $table = 'invoice_log';

    protected $fillable = [
        'id_invoice',
        'kode_invoice',
        'id_produk',
        'qty',
        'harga',
    ];

    protected $casts = [
        'qty' => 'float',
        'harga' => 'float',
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

