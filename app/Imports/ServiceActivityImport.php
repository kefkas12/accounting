<?php

namespace App\Imports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class ServiceActivityImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Service([
            'nama_konsumen'             => $row['nama_konsumen'],
            'no_service_bill_manual'    => $row['no_service_bill_manual'],
            'tanggal'                   => Date::excelToDateTimeObject(intval($row['tanggal']))->format('Y-m-d'),
            'nama_teknisi_1'            => $row['nama_teknisi_1'],
            'nama_teknisi_2'            => $row['nama_teknisi_2'],
            'no_seri_unit'              => $row['no_seri_unit'],
            'cabang'                    => $row['cabang'],
            'tipe'                      => $row['tipe'],
            'jasa_service'              => $row['jasa_service'],
            'pembayaran'                => $row['pembayaran'],
        ]);

    }
}
