<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun_company;
use App\Models\Company;
use App\Models\Detail_jurnal;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'company';
        $data['company'] = Company::join('users','company.id','users.id_company')
                                    ->select('company.id as id_company','users.*')
                                    ->get();
        return view('superadmin.company.index', $data);
    }

    public function refresh_akun($id)
    {
        //base jurnal
        $akun = Akun_company::where('id_company',$id)->get();
        
        foreach($akun as $v){
            $debit = 0;
            $kredit = 0;
            $d[] = null;
            $detail_jurnal = Detail_jurnal::where('id_company',$id)
                                        ->where('id_akun',$v->id_akun)->get();
            foreach($detail_jurnal as $w){
                $debit += $w->debit;
                $kredit += $w->kredit;
                $d[] = $w->debit.'-'.$w->kredit;

            }
            dd($d);
            
            DB::beginTransaction();
            $update_akun = Akun_company::find($v->id);
            $update_akun->saldo = $debit - $kredit;
            $update_akun->save();
            DB::commit();
        }
        return redirect('company');
    }
    public function insert(Request $request)
    {
        $supplier = new Kontak();
        $supplier->id_company = Auth::user()->id_company;
        $supplier->nama = $_POST['nama'];
        $supplier->nama_perusahaan = $_POST['nama_perusahaan'];
        $supplier->email = $_POST['email'];
        $supplier->nomor_handphone = $_POST['nomor_handphone'];
        $supplier->nomor_telepon = $_POST['nomor_telepon'];
        $supplier->alamat = $_POST['alamat'];
        $supplier->fax = $_POST['fax'];
        $supplier->npwp = $_POST['npwp'];
        $supplier->tipe = 'supplier';
        $supplier->save();

        return redirect('supplier');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'supplier';
        if($id){
            $data['supplier'] = Kontak::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.supplier.form', $data);
            }else if($status == 'detail'){
                return view('pages.supplier.detail', $data);
            }
        }else{
            return view('pages.supplier.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $supplier =  Kontak::find($id);
        $supplier->id_company = Auth::user()->id_company;
        $supplier->nama = $_POST['nama'];
        $supplier->nama_perusahaan = $_POST['nama_perusahaan'];
        $supplier->email = $_POST['email'];
        $supplier->nomor_handphone = $_POST['nomor_handphone'];
        $supplier->nomor_telepon = $_POST['nomor_telepon'];
        $supplier->alamat = $_POST['alamat'];
        $supplier->fax = $_POST['fax'];
        $supplier->npwp = $_POST['npwp'];
        $supplier->tipe = 'supplier';
        $supplier->save();

        return redirect('supplier');
    }

    public function hapus($id){
        $supplier = Kontak::find($id);
        $supplier->delete();

        return redirect('supplier');
    }
}
