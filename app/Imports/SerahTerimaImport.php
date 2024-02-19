<?php

namespace App\Imports;

use App\Models\Serah_terima;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class SerahTerimaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Serah_terima([
            'no_seri_unit'    => $row['no_seri_unit'],
            'nama_pemilik'       => $row['nama_pemilik'],
            'nama_pemilik_terakhir'       => $row['nama_pemilik_terakhir'],
        ]);
    }
}
