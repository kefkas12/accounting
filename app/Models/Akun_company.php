<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Akun_company extends Model
{
    use HasFactory;
    protected $table = 'akun_company';

    protected $fillable = [
        'saldo'
    ];

    public function detail_jurnal(): HasMany
    {
        return $this->hasMany(Detail_jurnal::class, 'id_akun');
    }

}
