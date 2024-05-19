<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Approval extends Model
{
    use HasFactory;
    protected $table = 'approval';

    function check_requester($jenis){
        $is_requester = false;
        $approval = Approval::where('id_company',Auth::user()->id_company)->where('tipe_transaksi',$jenis)->get();
        foreach($approval as $v){
            $requester = Requester::where('id_approval',$v->id)->where('id_user',Auth::id())->first();
            if($requester){
                $is_requester = true;
            }
        }
        return $is_requester;
    }
}
