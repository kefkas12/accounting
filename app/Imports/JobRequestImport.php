<?php

namespace App\Imports;

use App\Models\Job_request;
use App\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class JobRequestImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Job_request([
            'no_job_request'        => $row['no_job_request'],
            'cabang'                => $row['cabang'],
            'tipe'                  => $row['tipe'],
            'tanggal'               => Date::excelToDateTimeObject(intval($row['tanggal']))->format('d/m/Y'),
            'nama_konsumen'         => $row['nama_konsumen'],
            'nama_mekanik'          => $row['nama_mekanik'],
            'lokasi'                => $row['lokasi'],
            'no_seri_unit'          => $row['no_seri_unit'],
            'hourmeter'             => $row['hourmeter'],
            'permasalahan'          => $row['permasalahan'],
            'status'                => $row['status'],
        ]);
    }
}
