<?php

namespace App\Imports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsOnError;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class UnitImport implements ToModel, WithHeadingRow, SkipsOnError
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Unit([
            'cabang'                                => $row['cabang'],
            'no_seri_unit'                          => $row['no_seri_unit'],
            'no_engine'                             => $row['no_engine'],
            'model_unit'                            => $row['model_unit'],
            'tgl_serah_terima'                      => Date::excelToDateTimeObject(intval($row['tgl_serah_terima']))->format('Y-m-d'),
            'nama_pemilik_terakhir_serah_terima'    => $row['nama_pemilik'],
            'no_buku_warranty'                      => $row['no_buku_warranty'],
            'tracking_warranty'                     => $row['tracking_warranty'],
        ]);
    }
    public function onError(\Throwable $e)
    {
        // Handle the error
        // For example, you can log the error or perform any other action
        \Log::error($e->getMessage());
    }
}
