<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail_penjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_penjualan';

    public $timestamps = false;

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
