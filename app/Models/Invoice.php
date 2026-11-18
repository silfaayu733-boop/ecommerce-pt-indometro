<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'kode_invoice',
        'id_user',
        'nama',
        'alamat',
        'no_hp',
        'metode_pengiriman',
        'ongkir',
        'grandtotal',
        'status',
        'no_resi',
        'catatan',
    ];

    protected $casts = [
        'ongkir' => 'float',
        'grandtotal' => 'float',
        'status' => 'integer',
    ];

    // Status label helper
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Menunggu Pembayaran',
            1 => 'Menunggu Validasi Kasir',
            2 => 'Diproses Gudang (Packing)',
            3 => 'Sedang Dikirim',
            4 => 'Selesai',
            5 => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function invoiceLogs(): HasMany
    {
        return $this->hasMany(InvoiceLog::class, 'id_invoice');
    }

    public function produkKeluars(): HasMany
    {
        return $this->hasMany(ProdukKeluar::class, 'id_invoice');
    }

    public function bukti(): HasOne
    {
        return $this->hasOne(Bukti::class, 'id_invoice');
    }

    public function komplens(): HasMany
    {
        return $this->hasMany(Komplen::class, 'id_invoice');
    }
}

