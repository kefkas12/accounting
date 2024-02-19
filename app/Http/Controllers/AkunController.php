<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'akun';
        $data['akun'] = Akun::get();
        return view('pages.akun.index', $data);
    }

    public function akun()
    {
        $data['sidebar'] = 'akun';
        return view('pages.akun.form', $data);
    }
    
    public function insert(Request $request)
    {
        $akun = new Akun();
        $akun->nama = $_POST['nama'];
        $akun->kode = $_POST['kode'];
        $akun->unit = 'buah';
        $akun->kategori = $_POST['kategori'];
        $akun->harga_beli = $_POST['harga_beli'];
        $akun->harga_jual = $_POST['harga_jual'];
        $akun->save();

        return redirect('akun');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'akun';
        if($id){
            $data['akun'] = Akun::where('id', $id)->first();
            if($status == 'edit'){
                return view('pages.akun.form', $data);
            }else if($status == 'detail'){
                return view('pages.akun.detail', $data);
            }
        }else{
            return view('pages.akun.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $akun =  Akun::find($id);
        $akun->nama = $_POST['nama'];
        $akun->kode = $_POST['kode'];
        $akun->unit = $_POST['unit'];
        $akun->kategori = $_POST['kategori'];
        $akun->harga_beli = $_POST['harga_beli'];
        $akun->harga_jual = $_POST['harga_jual'];
        $akun->save();

        return redirect('akun');
    }
}
