<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gudang;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Produk_penawaran;
use App\Models\Satuan;
use App\Models\Stok_gudang;
use App\Models\Transaksi_produk;
use App\Models\Transaksi_produk_penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProdukPenawaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'produk_penawaran';
        $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)
                                ->get();
        return view('pages.produk_penawaran.index', $data);
    }

    public function produk()
    {
        $data['sidebar'] = 'produk_penawaran';
        $data['satuan'] = Satuan::where('id_company',Auth::user()->id_company)
                                ->get();
        return view('pages.produk_penawaran.form', $data);
    }

    public function insert(Request $request)
    {
        $produk_penawaran = new Produk_penawaran();
        $produk_penawaran->id_company = Auth::user()->id_company;
        $produk_penawaran->nama = $_POST['nama'];
        $produk_penawaran->kode = $_POST['kode'];
        $produk_penawaran->unit = $_POST['satuan'];
        $produk_penawaran->kategori = $_POST['kategori'];
        $produk_penawaran->save();

        return redirect('produk_penawaran/detail/'.$produk_penawaran->id);
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'produk_penawaran';
        $data['satuan'] = Satuan::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id){
            $data['produk_penawaran'] = Produk_penawaran::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.produk_penawaran.form', $data);
            }else if($status == 'detail'){
                $data['transaksi_produk_penawaran'] = Transaksi_produk_penawaran::where('id_produk',$id)
                                                ->where('id_company',Auth::user()->id_company)
                                                ->get();

                return view('pages.produk_penawaran.detail', $data);
            }
        }else{
            return view('pages.produk_penawaran.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $produk_penawaran =  Produk_penawaran::find($id);
        $produk_penawaran->id_company = Auth::user()->id_company;
        $produk_penawaran->nama = $_POST['nama'];
        $produk_penawaran->kode = $_POST['kode'];
        $produk_penawaran->unit = $_POST['satuan'];
        $produk_penawaran->kategori = $_POST['kategori'];
        $produk_penawaran->save();

        return redirect('produk_penawaran');
    }

    public function hapus($id){
        $produk_penawaran = Produk_penawaran::find($id);
        $produk_penawaran->delete();
        return redirect('produk_penawaran');
    }
    
}
