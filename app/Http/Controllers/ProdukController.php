<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'produk';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)
                                ->get();
        return view('pages.produk.index', $data);
    }

    public function produk()
    {
        $data['sidebar'] = 'produk';
        return view('pages.produk.form', $data);
    }

    public function insert(Request $request)
    {
        $produk = new Produk();
        $produk->id_company = Auth::user()->id_company;
        $produk->nama = $_POST['nama'];
        $produk->kode = $_POST['kode'];
        $produk->unit = 'buah';
        $produk->kategori = $_POST['kategori'];
        if(isset($_POST['batas_minimum']))
            $produk->batas_minimum = $_POST['batas_minimum'];
        $produk->harga_beli = $_POST['harga_beli'] ? $_POST['harga_beli'] : 0;
        $produk->harga_jual = $_POST['harga_jual'] ? $_POST['harga_jual'] : 0;
        $produk->save();

        return redirect('produk');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'produk';
        if($id){
            $data['produk'] = Produk::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.produk.form', $data);
            }else if($status == 'detail'){
                return view('pages.produk.detail', $data);
            }
        }else{
            return view('pages.produk.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $produk =  Produk::find($id);
        $produk->id_company = Auth::user()->id_company;
        $produk->nama = $_POST['nama'];
        $produk->kode = $_POST['kode'];
        $produk->unit = $_POST['unit'];
        $produk->kategori = $_POST['kategori'];
        $produk->harga_beli = $_POST['harga_beli'] ? $_POST['harga_beli'] : 0;
        $produk->harga_jual = $_POST['harga_jual'] ? $_POST['harga_jual'] : 0;
        $produk->save();

        return redirect('produk');
    }

    public function hapus($id){
        $produk = Produk::find($id);
        $produk->delete();
        return redirect('produk');
    }
    
}
