<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail_pembayaran_pembelian extends Model
{
    use HasFactory;
    protected $table = 'detail_pembayaran_pembelian';

    public $timestamps = false;

    public function pembayaran_pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembayaran_pembelian::class);
    }

    // public function pembelian(): BelongsTo
    // {
    //     return $this->belongsTo(Pembelian::class);
    // }

    // public function produk(): BelongsTo
    // {
    //     return $this->belongsTo(Produk::class, 'id_produk');
    // }
}
