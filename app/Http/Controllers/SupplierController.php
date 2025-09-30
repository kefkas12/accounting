<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'supplier';
        $data['supplier'] = Kontak::with('additional_alamat')
                                    ->where('tipe', 'supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.supplier.index', $data);
    }

    public function supplier()
    {
        $data['sidebar'] = 'supplier';
        return view('pages.supplier.insert', $data);
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
            $data['additional_alamat'] = Alamat::where('id_kontak', $id)
                                                ->get();
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
        $additional_alamat = Alamat::where('id_kontak',$id);
        if(count($additional_alamat->get()) > 0){
            $additional_alamat->delete();
        }

        for($i = 0; $i < count($_POST['additional_alamat']); $i++){
            if($_POST['additional_alamat'][$i]){
                $additional_alamat = new Alamat;
                $additional_alamat->id_kontak = $id;
                $additional_alamat->alamat = $_POST['additional_alamat'][$i];
                $additional_alamat->save();
            }   
        }
        
        $supplier =  Kontak::find($id);
        $supplier->id_company = Auth::user()->id_company;
        $supplier->nama = $_POST['nama'];
        $supplier->nama_perusahaan = $_POST['nama_perusahaan'];
        $supplier->email = $_POST['email'];
        $supplier->nomor_handphone = $_POST['nomor_handphone'];
        $supplier->nomor_telepon = $_POST['nomor_telepon'];
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

    public function alamat()
    {
        $data['alamat'] = Alamat::where('id_kontak', $_GET['id'])
                                            ->get();
        return $data['alamat'];
    }
}
