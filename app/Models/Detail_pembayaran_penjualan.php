<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Detail_pembayaran_penjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_pembayaran_penjualan';

    public $timestamps = false;

    public function pembayaran_penjualan(): BelongsTo
    {
        return $this->belongsTo(Pembayaran_penjualan::class, 'id_pembayaran_penjualan');
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
}
