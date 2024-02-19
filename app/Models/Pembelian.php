<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelian';

    function no(){
        $no = Pembelian::select('no')->orderBy('id','DESC')->first();
        if($no){
            $no = $no->no;
            $no++; 
        }else{
            $no = 10001;
        }
        return $no;
    }

    public function detail_pembelian(): HasMany
    {
        return $this->hasMany(Detail_pembelian::class, 'id_pembelian');
    }

    public function kontak(): BelongsTo
    {
        return $this->belongsTo(Kontak::class, 'id_supplier');
    }

    public function pembayaran_pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembayaran_pembelian::class, 'id_pembelian');
    }
}
