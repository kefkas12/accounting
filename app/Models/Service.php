<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    use HasFactory;
    protected $table = 'service';

    public function newServiceBill()
	{
		$last = Service::select("no_service_bill")->orderBy("id", "desc")->first();
		if(Auth::user()->cabang){
    		if (!$last) {
    			return 'SB-' . Auth::user()->cabang . '-' . date("Y") . '-' . date("m") . '-0001';
    		} else {
    			$no = intval(substr($last->no_service_bill, -3)) + 1;
    			if ($no < 10) {
    				return 'SB-' . Auth::user()->cabang . '-' . date("Y") . '-' . date("m") . '-000' . $no;
    			} elseif ($no < 100) {
    				return 'SB-' . Auth::user()->cabang . '-' . date("Y") . '-' . date("m") . '-00' . $no;
    			} elseif ($no < 1000) {
    				return 'SB-' . Auth::user()->cabang . '-' . date("Y") . '-' . date("m") . '-0' . $no;
    			} else {
    				return 'SB-' . Auth::user()->cabang . '-' . date("Y") . '-' . date("m") . '-' . $no;
    			}
    		}
		}else{
		    if (!$last) {
    			return 'SB-PLM-' . date("Y") . '-' . date("m") . '-0001';
    		} else {
    			$no = intval(substr($last->no_service_bill, -3)) + 1;
    			if ($no < 10) {
    				return 'SB-PLM-' . date("Y") . '-' . date("m") . '-000' . $no;
    			} elseif ($no < 100) {
    				return 'SB-PLM-' . date("Y") . '-' . date("m") . '-00' . $no;
    			} elseif ($no < 1000) {
    				return 'SB-PLM-' . date("Y") . '-' . date("m") . '-0' . $no;
    			} else {
    				return 'SB-PLM-' . date("Y") . '-' . date("m") . '-' . $no;
    			}
    		}
		}
	}

    public function search($cabang,$search){
        $query = Service::query();
        $columns = [
                    'service.no_service_bill',
                    'service.no_service_bill_manual',
                    'service.no_invoice',
                    'service.cabang',
                    'service.tipe',
                    'service.no_seri_unit',
                    'service.lokasi',
                    'service.permasalahan',
                    'service.penyebab',
                    'service.tindakan_perbaikan',
                    'service.jasa_service',
                    'service.status',
                    'service.pembayaran',
                    'service.keterangan',
                    'service.nama_teknisi_1',
                    'service.nama_teknisi_2',
                    'service.nama_konsumen',
                    'unit.model_unit'
                    ];
        $query->leftJoin('unit','service.no_seri_unit','=','unit.no_seri_unit');
        $query->whereIn('service.cabang',$cabang);
        $query->where(function ($query) use ($columns, $search) {
            foreach($columns as $column){
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        });
        $query->orWhere('tanggal', '=', date('Y-m-d', strtotime($search)));
        $query->orderBy('service.id', 'desc');
        return $query->paginate(10);

    }
}
