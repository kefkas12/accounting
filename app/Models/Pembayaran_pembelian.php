<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran_pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_pembelian';

    function no()
    {
        $no = Pembayaran_pembelian::select('no')->orderBy('id', 'DESC')->first();
        if ($no) {
            $no = $no->no;
            $no++;
        } else {
            $no = 10001;
        }
        return $no;
    }

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}
