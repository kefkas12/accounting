<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_penawaran extends Model
{
    use HasFactory;
    protected $table = 'produk_penawaran';

    public function produk()
    {
        return $this->hasOne(Produk::class, 'id_produk_penawaran', 'id');
    }
}
