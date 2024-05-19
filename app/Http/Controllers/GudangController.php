<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    public function gudang()
    {
        $data['sidebar'] = 'produk';
        return view('pages.gudang.form', $data);
    }

    public function insert(Request $request)
    {
        $gudang = new Gudang();
        $gudang->id_company = Auth::user()->id_company;
        if(isset($_POST['kode']))
            $gudang->kode = $_POST['kode'];
        $gudang->nama = $_POST['nama'];
        if(isset($_POST['alamat']))
            $gudang->alamat = $_POST['alamat'];
        if(isset($_POST['keterangan']))
            $gudang->keterangan = $_POST['keterangan'];
        $gudang->status = 'aktif';
        $gudang->save();

        return redirect('gudang/detail/'.$gudang->id);
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'produk';
        if($id){
            $data['gudang'] = Gudang::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.gudang.form', $data);
            }else if($status == 'detail'){
                return view('pages.gudang.detail', $data);
            }
        }else{
            return view('pages.gudang.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $gudang =  Gudang::find($id);
        $gudang->id_company = Auth::user()->id_company;
        if(isset($_POST['kode']))
            $gudang->kode = $_POST['kode'];
        $gudang->nama = $_POST['nama'];
        if(isset($_POST['alamat']))
            $gudang->alamat = $_POST['alamat'];
        if(isset($_POST['keterangan']))
            $gudang->keterangan = $_POST['keterangan'];
        $gudang->save();

        return redirect('produk');
    }

    public function hapus($id){
        $gudang = Gudang::find($id);
        $gudang->delete();
        return redirect('produk');
    }
}
