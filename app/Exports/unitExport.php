<?php

namespace App\Exports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class unitExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $filter;
    private $dari;
    private $sampai;
    private $cabang;

    function __construct($filter, $dari, $sampai, $cabang)
    {
        $this->filter = $filter;
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->cabang = $cabang;
    }

    public function collection()
    {
        if($this->filter != 'all'){
            $model_unit = explode(' ',$this->filter);
            array_pop($model_unit);
            for($i=0;$i<count($model_unit);$i++){
                $model_unit[$i] = ucwords(str_replace("_", " ", $model_unit[$i]));
            }
        }

        $cabang = explode(' ',$this->cabang);
        array_pop($cabang);
        for($i=0;$i<count($cabang);$i++){
            $cabang[$i] = strtoupper(str_replace("_", " ", $cabang[$i]));
        }

        $data = Unit::leftJoin('service','unit.no_seri_unit','=','service.no_seri_unit')
                    ->leftJoin('user','unit.nama_pemilik_terakhir_serah_terima','=','user.nama')
                                ->select("unit.tgl_serah_terima", "unit.nama_pemilik_terakhir_serah_terima", 'user.alamat', "unit.cabang", "unit.no_seri_unit", "unit.no_engine","unit.model_unit", DB::raw('group_concat(service.tipe SEPARATOR ", ") as tipe_service'));
        
        if($this->filter != 'all'){
            $data = $data->whereIn('unit.model_unit',$model_unit);
        }
        if($this->cabang != 'all'){
            $data = $data->where('unit.cabang',$this->cabang);
        }
        $data = $data->whereBetween('unit.tgl_serah_terima', [$this->dari, $this->sampai])
                    ->groupBy('unit.no_seri_unit')
                    ->get();
        
        return $data;
    }
    public function headings(): array
    {
        return ["Tanggal Serah Terima", "Nama Pemilik Terakhir", "Alamat", "Cabang", "No Seri Unit", "No Engine","Model Unit", "Tipe Service"];
    }
}
