<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UserImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User([
            'nama'          => $row['nama'],
            'perusahaan'          => 'PT Pilar Putra Teknik',
            'cabang'          => $row['cabang'],
            'telepon_1'          => $row['telepon'],
            'alamat'          => $row['alamat'],
            'jabatan'          => $row['jabatan'],
        ]);

        $user->syncRoles([$row['jabatan']]);

        return $user;
    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
}
