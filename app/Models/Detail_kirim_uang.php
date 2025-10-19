<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Detail_kirim_uang extends Model
{
    use HasFactory;
    protected $table = 'detail_kirim_uang';

    public $timestamps = false;
}
