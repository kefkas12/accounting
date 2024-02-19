<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receive_item extends Model
{
    use HasFactory;
    protected $table = 'receive_item';

    public function search($cabang,$search){
        $query = Receive_item::query();
        $columns = ['perusahaan', 'cabang', 'nama_supplier', 'nama_sopir', 'telepon_sopir', 'no_polisi_truk', 'tanggal'];
        $query->whereIn('cabang',$cabang);
        $query->where(function ($query) use ($columns, $search) {
            foreach($columns as $column){
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        });
        return $query->paginate(10);

    }
}
