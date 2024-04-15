<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'pengaturan';
        $data['pengaturan'] = Kontak::where('tipe', 'supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pengaturan.sidebar', $data);
    }
    public function perusahaan()
    {
        $data['sidebar'] = 'pengaturan';
        $data['perusahaan'] = Kontak::where('tipe', 'supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pengaturan.perusahaan', $data);
    }
    public function pengguna()
    {
        $data['sidebar'] = 'pengaturan';
        $data['pengguna'] = User::where('id_company',Auth::user()->id_company)
                                ->get();
        return view('pages.pengaturan.pengguna', $data);
    }
    public function form_pengguna()
    {
        $data['sidebar'] = 'pengaturan';
        $data['pengguna'] = Kontak::where('tipe', 'supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pengaturan.form_pengguna', $data);
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
