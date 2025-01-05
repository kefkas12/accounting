<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen_penjualan extends Model
{
    use HasFactory;
    protected $table = 'dokumen_penjualan';

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Pengaturan_dokumen::class, 'id_dokumen');
    }
}
