<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'unit';
    protected $fillable = [
        'cabang', 
        'no_seri_unit', 
        'model_unit', 
        'no_engine', 
        'nama_pemilik_terakhir_serah_terima', 
        'no_buku_warranty', 
        'tracking_warranty',
        'status_pdi',
        'tgl_serah_terima',
        'tanggal_pdi',
    ];

    public function search($cabang,$search, $keyword = null){
        $query = Unit::query();
        $columns = ['cabang', 'no_seri_unit', 'no_engine', 'model_unit', 'nama_pemilik_terakhir_serah_terima'];
        if($keyword){
            $query->where('unit.no_seri_unit', 'LIKE', '%' . $keyword . '%');
        }
        $query->whereIn('cabang',$cabang);
        $query->where(function ($query) use ($columns, $search) {
            foreach($columns as $column){
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        });
        
        return $query->paginate(10);
    }
}
