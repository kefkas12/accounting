<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penerimaan extends Model
{
    use HasFactory;
    protected $table = 'penerimaan';

    public function detail_penerimaan(): HasMany
    {
        return $this->hasMany(Detail_penerimaan::class, 'id_penerimaan');
    }
}
