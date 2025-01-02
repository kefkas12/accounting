<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail_penerimaan extends Model
{
    use HasFactory;
    protected $table = 'detail_penerimaan';

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'id_penerimaan');
    }
}
