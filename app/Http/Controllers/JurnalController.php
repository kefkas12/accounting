<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Detail_jurnal;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function insert(Request $request)
    {
        $jurnal = new Jurnal;
        $jurnal->id_company = Auth::user()->id_company;
        $jurnal->tanggal_transaksi = $_POST['tanggal_transaksi'];
        $jurnal->kategori = 'journal_entry';
        $jurnal->no = $jurnal->no('journal_entry');
        $jurnal->no_str = 'Journal Entry #'.$jurnal->no('journal_entry');
        $jurnal->debit = $_POST['total_debit'];
        $jurnal->kredit = $_POST['total_kredit'];
        $jurnal->save();

        for($i=0; $i<count($_POST['akun']) ; $i++){
            $detail_jurnal = new Detail_jurnal;
            $detail_jurnal->id_company = Auth::user()->id_company;
            $detail_jurnal->id_jurnal = $jurnal->id;
            $detail_jurnal->id_akun = $_POST['akun'][$i];
            $detail_jurnal->deskripsi = $_POST['deskripsi'][$i];
            $detail_jurnal->debit = $_POST['debit'][$i];
            $detail_jurnal->kredit = $_POST['kredit'][$i];
            $detail_jurnal->save();

            $akun = Akun::find($_POST['akun'][$i]);
            $akun->saldo = $akun->saldo + $detail_jurnal->debit - $detail_jurnal->kredit;
            $akun->save();
        }

        return redirect('laporan/jurnal');
    }
    public function detail()
    {
        $data['sidebar'] = 'akun';
        $data['akun'] = Akun::get();
        return view('pages.jurnal.form', $data);
    }
}
