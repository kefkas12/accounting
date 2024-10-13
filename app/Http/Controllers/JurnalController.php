<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Approval;
use App\Models\Detail_jurnal;
use App\Models\Jurnal;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class JurnalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function insert(Request $request)
    {
        DB::beginTransaction();

        $approval = new Approval;
        $is_requester = $approval->check_requester('Tambah Jurnal');

        $jurnal = new Jurnal;
        $jurnal->id_company = Auth::user()->id_company;
        $jurnal->tanggal_transaksi = $_POST['tanggal_transaksi'];
        $jurnal->kategori = 'journal_entry';
        $jurnal->no = $jurnal->no('journal_entry');
        $jurnal->no_str = 'Journal Entry #'.$jurnal->no('journal_entry');
        $jurnal->debit = $_POST['total_debit'];
        $jurnal->kredit = $_POST['total_kredit'];
        if($is_requester){
            $jurnal->status = 'draf';
        }
        $jurnal->save();

        for($i=0; $i<count($_POST['akun']) ; $i++){
            if($_POST['akun'][$i] != ''){
                $detail_jurnal = new Detail_jurnal;
                $detail_jurnal->id_company = Auth::user()->id_company;
                $detail_jurnal->id_jurnal = $jurnal->id;
                $detail_jurnal->id_akun = $_POST['akun'][$i];
                $detail_jurnal->deskripsi = $_POST['deskripsi'][$i];
                $detail_jurnal->debit = $_POST['debit'][$i] != '' || $_POST['debit'][$i] != null ? number_format((float)str_replace(",", "", $_POST['debit'][$i]), 2, '.', '') : 0;
                $detail_jurnal->kredit = $_POST['kredit'][$i] != '' || $_POST['kredit'][$i] != null ? number_format((float)str_replace(",", "", $_POST['kredit'][$i]), 2, '.', '') : 0;

                $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
                $saldo = $akun_company ? $akun_company->saldo : 0;

                $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                            ->where('id_company', Auth::user()->id_company)
                                            ->update(['saldo' => $saldo + $detail_jurnal->debit - $detail_jurnal->kredit]);

                $detail_jurnal->save();
            }
        }
        DB::commit();
        return redirect('laporan/jurnal');
    }

    public function edit($id){
        DB::beginTransaction();

        $approval = new Approval;
        $is_requester = $approval->check_requester('Ubah Jurnal');

        if($_POST['total_debit'] != $_POST['total_kredit']) {
            return redirect()->back();
        }else{
            $jurnal = Jurnal::find($id);
            $jurnal->tanggal_transaksi = $_POST['tanggal_transaksi'];
            $jurnal->debit = $_POST['total_debit'];
            $jurnal->kredit = $_POST['total_kredit'];
            if($is_requester){
                $jurnal->status = 'draf';
            }
            $jurnal->save();

            $detail_jurnal = Detail_jurnal::where('id_jurnal',$id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_akun',$v->id_akun)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
                $saldo = $akun_company ? $akun_company->saldo : 0;
                $debit = $v->debit ? $v->debit : 0;
                $kredit = $v->kredit ? $v->kredit : 0;

                $akun_company = Akun_company::where('id_akun', $v->id_akun)
                                            ->where('id_company', Auth::user()->id_company)
                                            ->update(['saldo' => $saldo - $debit + $kredit]);
            }
            Detail_jurnal::where('id_jurnal',$id)->delete();
            
            for($i=0; $i<count($_POST['akun']) ; $i++){
                if($_POST['akun'][$i] != ''){
                    $detail_jurnal = new Detail_jurnal;
                    $detail_jurnal->id_company = Auth::user()->id_company;
                    $detail_jurnal->id_jurnal = $jurnal->id;
                    $detail_jurnal->id_akun = $_POST['akun'][$i];
                    $detail_jurnal->deskripsi = $_POST['deskripsi'][$i];
                    $detail_jurnal->debit = $_POST['debit'][$i] != '' || $_POST['debit'][$i] != null ? number_format((float)str_replace(",", "", $_POST['debit'][$i]), 2, '.', '') : 0;
                    $detail_jurnal->kredit = $_POST['kredit'][$i] != '' || $_POST['kredit'][$i] != null ? number_format((float)str_replace(",", "", $_POST['kredit'][$i]), 2, '.', '') : 0;

                    $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                                ->where('id_company',Auth::user()->id_company)
                                                ->first();
                    $saldo = $akun_company ? $akun_company->saldo : 0;

                    $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                                ->where('id_company', Auth::user()->id_company)
                                                ->update(['saldo' => $saldo + $detail_jurnal->debit - $detail_jurnal->kredit]);

                    $detail_jurnal->save();
                }
            }

            DB::commit();

            return redirect('laporan/jurnal');
        }
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
            }else if($status == 'hapus'){
                
                try{
                    if(User::find(Auth::id())->hasRole('pemilik')){
                        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                                ->where('id',$id)
                                                ->where('id_company',Auth::user()->id_company)
                                                ->first();
                        DB::beginTransaction();

                        if(count($data['jurnal']->detail_jurnal) > 0){
                            foreach($data['jurnal']->detail_jurnal as $v){
                                $akun_company = Akun_company::where('id_akun', $v->id_akun)
                                                            ->where('id_company', Auth::user()->id_company)
                                                            ->first();
                                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                                $akun_company->save();
                                Detail_jurnal::find($v->id)->delete();
                            }
                        }
                        Jurnal::find($id)->delete();

                        DB::commit();
                    }else{
                        $approval = new Approval;
                        $is_requester = $approval->check_requester('Hapus Jurnal');

                        if($is_requester){
                            $jurnal = Jurnal::find($id);
                            $jurnal->status = 'draf';
                            $jurnal->is_delete = 'delete';
                            $jurnal->save();
                        }
                    }
                    //delete

                }catch(Exception $e){
                    dd("Message : ". $e->getMessage());
                }
                
                return redirect('laporan/jurnal');
            }
        }else{
            return view('pages.jurnal.form', $data);
        }
    }

    public function approve($id, $status = null){
        if($status == 'delete'){
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->where('id',$id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            DB::beginTransaction();

            if(count($data['jurnal']->detail_jurnal) > 0){
                foreach($data['jurnal']->detail_jurnal as $v){
                    $akun_company = Akun_company::where('id_akun', $v->id_akun)
                                                ->where('id_company', Auth::user()->id_company)
                                                ->first();
                    $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                    $akun_company->save();
                    Detail_jurnal::find($v->id)->delete();
                }
            }
            Jurnal::find($id)->delete();

            DB::commit();
        }else{
            $jurnal = Jurnal::find($id);
            $jurnal->status = 'approved';
            $jurnal->save();
        }

        return redirect('laporan/jurnal');
    }

    public function cancel($id){
        $jurnal = Jurnal::find($id);
        $jurnal->status = null;
        $jurnal->is_delete = null;
        $jurnal->save();

        return redirect('laporan/jurnal');
    }
}
