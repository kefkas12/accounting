<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Detail_pembelian extends Model
{
    use HasFactory;
    protected $table = 'detail_pembelian';

    protected $fillable = [
        'id_company', 
        'id_pembelian',
        'id_produk',
        'deskripsi',
        'kuantitas',
        'harga_satuan',
        'pajak',
        'jumlah'
    ];

    public $timestamps = false;

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function stok_gudang(): HasMany
    {
        return $this->hasMany(Stok_gudang::class, 'id_detail_transaksi');
    }
}
