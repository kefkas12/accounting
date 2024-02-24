<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun_company extends Model
{
    use HasFactory;
    protected $table = 'akun_company';

    protected $fillable = [
        'saldo'
    ];
}
