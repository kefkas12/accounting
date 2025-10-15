<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transfer_uang extends Model
{
    use HasFactory;
    protected $table = 'transfer_uang';

    function no()
    {
        $no = Transfer_uang::select('no')
                        ->where('id_company',Auth::user()->id_company)
                        ->orderBy('id', 'DESC')
                        ->first();
        if ($no) {
            $no = $no->no;
            $no++;
        } else {
            $no = 10001;
        }
        return $no;
    }

    public function insert($request, $idJurnal)
    {
        $jumlah = $request->input('jumlah') != '' || $request->input('jumlah') != null ? number_format((float)str_replace(",", "", $_POST['jumlah']), 2, '.', '') : 0;
        
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;

        $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

        if ($date) {
            $this->tanggal_transaksi = $date->format('Y-m-d');
        } else {
            $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
            $this->tanggal_transaksi = $date ? $date->format('Y-m-d') : null;
        }

        $this->no = $this->no();
        $this->no_str = 'Bank Transfer #' . $this->no;
        $this->id_transfer_dari = $request->input('transfer_dari');
        $this->transfer_dari = Akun::where('id',$this->id_transfer_dari)->first()->nama;
        $this->id_setor_ke = $request->input('setor_ke');
        $this->setor_ke = Akun::where('id',$this->id_setor_ke)->first()->nama;
        $this->jumlah = $jumlah;

        $this->memo = $request->input('memo') ? $request->input('memo') : null;
        $this->save();

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'transfer_uang';
        $log->aksi = 'insert';
        $log->save();

    }
}
