<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Akun_company;
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
            if($_POST['akun'][$i] != ''){
                $detail_jurnal = new Detail_jurnal;
                $detail_jurnal->id_company = Auth::user()->id_company;
                $detail_jurnal->id_jurnal = $jurnal->id;
                $detail_jurnal->id_akun = $_POST['akun'][$i];
                $detail_jurnal->deskripsi = $_POST['deskripsi'][$i];
                $detail_jurnal->debit = $_POST['debit'][$i];
                $detail_jurnal->kredit = $_POST['kredit'][$i];
                $detail_jurnal->save();

                $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
                $saldo = $akun_company ? $akun_company->saldo : 0;

                $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                            ->where('id_company', Auth::user()->id_company)
                                            ->update(['saldo' => $saldo + $detail_jurnal->debit - $detail_jurnal->kredit]);
            }
        }

        return redirect('laporan/jurnal');
    }

    public function edit($id){
        $jurnal = Jurnal::find($id);
        $jurnal->tanggal_transaksi = $_POST['tanggal_transaksi'];
        $jurnal->debit = $_POST['total_debit'];
        $jurnal->kredit = $_POST['total_kredit'];
        $jurnal->save();

        for($i=0; $i<count($_POST['akun']) ; $i++){
            if($_POST['akun'][$i] != ''){
                if($_POST['id_detail_jurnal'][$i] != ''){
                    $detail_jurnal = Detail_jurnal::find((int)$_POST['id_detail_jurnal'][$i]);
                    $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
                    $saldo = $akun_company ? $akun_company->saldo : 0;
                    $debit = $detail_jurnal->debit ? $detail_jurnal->debit : 0;
                    $kredit = $detail_jurnal->kredit ? $detail_jurnal->kredit : 0;

                    $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                                ->where('id_company', Auth::user()->id_company)
                                                ->update(['saldo' => $saldo - $debit + $kredit]);

                }else{
                    $detail_jurnal = new Detail_jurnal;
                    $detail_jurnal->id_company = Auth::user()->id_company;
                    $detail_jurnal->id_jurnal = $jurnal->id;
                }

                $detail_jurnal->id_akun = $_POST['akun'][$i];
                $detail_jurnal->deskripsi = $_POST['deskripsi'][$i];
                $detail_jurnal->debit = $_POST['debit'][$i];
                $detail_jurnal->kredit = $_POST['kredit'][$i];
                $detail_jurnal->save();

                $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                                ->where('id_company',Auth::user()->id_company)
                                                ->first();
                $saldo = $akun_company ? $akun_company->saldo : 0;

                $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                            ->where('id_company', Auth::user()->id_company)
                                            ->update(['saldo' => $saldo + $detail_jurnal->debit - $detail_jurnal->kredit]);
                
            }
        }

        return redirect('laporan/jurnal');
    }

    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'akun';
        $data['akun'] = Akun::get();
        if($id){
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->where('id',$id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            if($status == 'edit'){
                return view('pages.jurnal.form', $data);
            }else if($status == 'detail'){
                return view('pages.jurnal.detail', $data);
            }
        }else{
            return view('pages.jurnal.form', $data);
        }
    }
}
