<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Job_request extends Model
{
    use HasFactory;
    protected $table = 'job_request';

    public function newJobRequest($cabang = null)
	{
	    if(!$cabang){
	        $cabang = Auth::user()->cabang;
	    }
		$last = Job_request::select("no_job_request")->orderBy("id", "desc")->first();
		if (!$last) {
			return 'JR-' . $cabang . '-' . date("Y") . '-' . date("m") . '-0001';
			// return '0001';
		} else {
			$no = intval(substr($last->no_job_request, -3)) + 1;
			if ($no < 10) {
				// return '000'.$no;
				return 'JR-' . $cabang . '-' . date("Y") . '-' . date("m") . '-000' . $no;
			} elseif ($no < 100) {
				// return '00'.$no;
				return 'JR-' . $cabang . '-' . date("Y") . '-' . date("m") . '-00' . $no;
			} elseif ($no < 1000) {
				// return '0'.$no;
				return 'JR-' . $cabang . '-' . date("Y") . '-' . date("m") . '-0' . $no;
			} else {
				// return $no;
				return 'JR-' . $cabang . '-' . date("Y") . '-' . date("m") . '-' . $no;
			}
		}
	}

	public function search($cabang,$search){
        $query = Job_request::query();
        $columns = ['job_request.no_job_request',
                    'job_request.cabang',
                    'job_request.tipe',
                    'job_request.lokasi',
                    'job_request.no_seri_unit',
                    'job_request.permasalahan',
                    'job_request.status',
                    'job_request.nama_konsumen',
                    'job_request.nama_mekanik',
                    'unit.model_unit'
                    ];
        $query->leftJoin('unit','job_request.no_seri_unit','=','unit.no_seri_unit');
        $query->select('job_request.*','unit.model_unit');
        $query->whereIn('job_request.cabang',$cabang);
        $query->where(function ($query) use ($columns, $search) {
            foreach($columns as $column){
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        });
        $query->orWhere('tanggal', '=', date('Y-m-d', strtotime($search)));
        $query->orWhere('tanggal_berangkat', '=', date('Y-m-d', strtotime($search)));
        return $query->paginate(10);

    }

}
