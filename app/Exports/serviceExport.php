<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class serviceExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            $type = explode(' ',$this->filter);
            array_pop($type);
            for($i=0;$i<count($type);$i++){
                $type[$i] = ucwords(str_replace("_", " ", $type[$i]));
            }
        }
        
        $data = Service::leftJoin('unit','service.no_seri_unit','=','unit.no_seri_unit')
                        ->leftJoin('job_request','service.id_job_request','=','job_request.id')
                        ->select('service.no_service_bill_manual','service.no_invoice','service.tipe','service.hourmeter','service.jasa_service','service.sparepart','service.transport','service.garansi','job_request.tanggal as tanggal_request','job_request.tanggal_berangkat','service.tanggal','unit.tgl_serah_terima','service.jam_mulai','service.jam_selesai','service.cabang','service.nama_konsumen','unit.model_unit','unit.no_seri_unit','unit.no_buku_warranty','job_request.no_job_request','service.nama_teknisi_1','service.nama_teknisi_2','service.nama_driver','unit.tracking_warranty','service.permasalahan','service.penyebab','service.tindakan_perbaikan');
        if($this->filter != 'all'){
            $data = $data->whereIn('service.tipe',$type);
        }
        if($this->cabang != 'all'){
            $data = $data->where('service.cabang',$this->cabang);
        }
        $data = $data->whereBetween('service.tanggal', [$this->dari, $this->sampai])
                    ->get();
        return $data;
    }
    public function headings(): array
    {
        return ["No Service Bill Manual", "No Invoice", "Service", "Hourmeter", "Jasa Service", "Sparepart", "Transport", "Garansi", "Tanggal Request", "Tanggal berangkat", "Tanggal Service","Tgl Serah Terima","Jam Mulai","Jam Selesai", "Cabang", "Nama Konsumen", "Model Unit", "No Seri Unit", "No Warranty",  "No Job Request",  "Nama Teknisi 1", "Nama Teknisi 2", "Nama Driver", "No Tracking", "Permasalahan", "Penyebab", "Tindakan Perbaikan"];
    }
}
