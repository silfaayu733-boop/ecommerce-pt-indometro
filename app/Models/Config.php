<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    protected $fillable = [
        'logo',
        'header',
        'map',
        'banner',
        'nama',
        'alamat',
        'email',
        'telp',
        'no_rek',
        'bank',
        'qris',
    ];
}

