<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Stok_gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function satuan()
    {
        $data['sidebar'] = 'satuan';
        return view('pages.satuan.form', $data);
    }

    public function insert(Request $request)
    {
        $satuan = new Satuan();
        $satuan->id_company = Auth::user()->id_company;
        $satuan->nama = $_POST['nama'];
        $satuan->status = 'aktif';
        $satuan->save();

        return redirect('produk/satuan');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'satuan';
        if($id){
            $data['satuan'] = Satuan::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.satuan.form', $data);
            }else if($status == 'detail'){
                return view('pages.satuan.detail', $data);
            }
        }else{
            return view('pages.satuan.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $satuan =  Satuan::find($id);
        $satuan->nama = $_POST['nama'];
        $satuan->status = 'aktif';
        $satuan->save();

        return redirect('produk/satuan');
    }

    public function hapus($id){
        $satuan = Satuan::find($id);
        $satuan->delete();
        return redirect('produk/satuan');
    }
}
