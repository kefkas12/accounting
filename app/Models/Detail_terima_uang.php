<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Detail_terima_uang extends Model
{
    use HasFactory;
    protected $table = 'detail_terima_uang';

    public $timestamps = false;

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }
}
