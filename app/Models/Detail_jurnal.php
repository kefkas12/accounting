<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail_jurnal extends Model
{
    use HasFactory;
    protected $table = 'detail_jurnal';

    public $timestamps = false;

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'id_jurnal');
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function akun_company(): BelongsTo
    {
        return $this->belongsTo(Akun_company::class, 'id_akun');
    }
}
