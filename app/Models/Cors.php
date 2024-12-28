<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cors extends Model
{
    use HasFactory;

    public static function handle($data){
        return response()->json($data)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
    }
}
