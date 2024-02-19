<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kontak extends Model
{
    use HasFactory;
    protected $table = 'kontak';

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan');
    }

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'id_supplier');
    }
}
