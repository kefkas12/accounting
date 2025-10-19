<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class Terima_uang extends Model
{
    use HasFactory;
    protected $table = 'terima_uang';

    function no()
    {
        $no = Terima_uang::select('no')
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
        $this->no_str = 'Bank Deposit #' . $this->no;
        $this->id_setor_ke = $request->input('setor_ke');
        $this->setor_ke = Akun::where('id',$this->id_setor_ke)->first()->nama;
        $this->jumlah = $request->input('input_total');

        $this->memo = $request->input('memo') ? $request->input('memo') : null;
        $this->save();

        $this->insertDetailTerimaUang($request);

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'terima_uang';
        $log->aksi = 'insert';
        $log->save();

    }

    protected function insertDetailTerimaUang(Request $request)
    {
        for ($i = 0; $i < count($request->input('akun')); $i++) {
            $detail_terima_uang = new Detail_terima_uang;
            $detail_terima_uang->id_terima_uang = $this->id;
            $detail_terima_uang->id_company = Auth::user()->id_company;
            $detail_terima_uang->id_akun = $request->input('akun')[$i];
            $detail_terima_uang->deskripsi = $request->input('deskripsi')[$i];
            $detail_terima_uang->jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $request->input('jumlah')[$i]), 2, '.', '') : 0;
            $detail_terima_uang->pajak = $detail_terima_uang->jumlah * $request->input('pajak')[$i] / 100;
            $detail_terima_uang->save();

        }
    }

    public function ubah($request, $idJurnal)
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
        $log->aksi = 'edit';
        $log->save();

    }
}
