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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($menu = null)
    {
        if($menu){
            $data['sidebar'] = $menu;
        }else{
            $data['sidebar'] = 'produk';
        }
        $data['produk_tersedia'] = Produk::where('id_company',Auth::user()->id_company)
                                            ->count();
        $data['produk_segera_habis'] = Produk::whereColumn('stok','<','batas_stok_minimum')
                                            ->where('stok','>',0)
                                            ->where('id_company',Auth::user()->id_company)
                                            ->count();
        $data['produk_habis'] = Produk::where('stok','<=',0)
                                            ->where('id_company',Auth::user()->id_company)
                                            ->count();
        $data['gudang_terdaftar'] = Gudang::where('status','aktif')
                                            ->where('id_company',Auth::user()->id_company)
                                            ->count();
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)
                                ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                ->get();
        $data['satuan'] = Satuan::where('id_company',Auth::user()->id_company)
                                ->get();
        if(isset($menu)){
            $data['menu'] = $menu;
        }else{
            $data['menu'] = 'produk';
        }
        return view('pages.produk.index', $data);
    }

    public function produk()
    {
        $data['sidebar'] = 'produk';
        $data['satuan'] = Satuan::where('id_company',Auth::user()->id_company)
                                ->get();
        return view('pages.produk.form', $data);
    }

    public function insert(Request $request)
    {
        $produk = new Produk();
        if($_POST['produk_penawaran']){
            $produk->id_produk_penawaran = $_POST['produk_penawaran'];
        }
        $produk->id_company = Auth::user()->id_company;
        $produk->nama = $_POST['nama'];
        if($_POST['kode']){
            $produk->kode = $_POST['kode'];
        }
        if($_POST['kategori']){
            $produk->kategori = $_POST['kategori'];
        }
        $produk->unit = $_POST['satuan'];
        if(isset($_POST['batas_minimum'])){
            $produk->stok = 0;
            $produk->batas_stok_minimum = $_POST['batas_stok_minimum'];
        }
        $produk->harga_beli = $_POST['harga_beli'] ? $_POST['harga_beli'] : 0;
        $produk->harga_jual = $_POST['harga_jual'] ? $_POST['harga_jual'] : 0;
        $produk->save();

        return redirect('produk/detail/'.$produk->id);
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'produk';
        $companyId = Auth::user()->id_company;

        $usedIds = Produk::where('id_company', $companyId)
                        ->pluck('id_produk_penawaran')     // Collection, bisa berisi null
                        ->filter(fn ($v) => !is_null($v))  // singkirkan null
                        ->unique()
                        ->values();
        $query = Produk_penawaran::where('id_company', $companyId);

        if ($usedIds->isNotEmpty()) {
            $query->whereNotIn('id', $usedIds);
        }

        $data['produk_penawaran'] = $query->get();
        $data['satuan'] = Satuan::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id){
            $data['produk'] = Produk::leftJoin('produk_penawaran','produk.id_produk_penawaran','produk_penawaran.id')
                                    ->select('produk.*','produk_penawaran.nama as nama_produk_penawaran')
                                    ->where('produk.id', $id)
                                    ->where('produk.id_company',Auth::user()->id_company)
                                    ->first();
            $data['gudang'] = Gudang::query()
                                    ->leftJoin('stok_gudang', function($join){
                                        $join->on( 'gudang.id', '=', 'stok_gudang.id_gudang');
                                            // ->where(function($query){
                                            //     $query->where('stok_gudang.tipe','like','Faktur Pembelian%')
                                            //         ->orWhere('stok_gudang.tipe','like','Penagihan Pembelian%')
                                            //         ->orWhere('stok_gudang.tipe','like','Pengiriman Penjualan%');
                                            // }); 
                                    })
                                    ->where('gudang.id_company', Auth::user()->id_company)
                                    ->select('gudang.id','gudang.nama')
                                    ->selectRaw("
                                        COALESCE(SUM(
                                            CASE 
                                                WHEN stok_gudang.id_produk = ? AND (stok_gudang.tipe LIKE 'Faktur Pembelian%' OR stok_gudang.tipe LIKE 'Penagihan Pembelian%') 
                                                    THEN stok_gudang.stok
                                                WHEN stok_gudang.id_produk = ? AND (stok_gudang.tipe LIKE 'Pengiriman Penjualan%')
                                                    THEN -stok_gudang.stok
                                                ELSE 0
                                            END
                                        ), 0) as stok
                                    ",[$id, $id])
                                    ->groupBy('gudang.id','gudang.nama')
                                    ->get();

            $data['stok_gudang'] = Stok_gudang::select(DB::raw('sum(stok) as stok'))
                                                ->where('id_produk',$id)
                                                ->where('id_company', Auth::user()->id_company)
                                                ->whereNull('id_gudang')
                                                ->first();
            if($status == 'edit'){
                return view('pages.produk.form', $data);
            }else if($status == 'detail'){
                $data['transaksi_produk'] = Transaksi_produk::where('id_produk',$id)
                                                ->where('id_company',Auth::user()->id_company)
                                                ->get();
                return view('pages.produk.detail', $data);
            }else if($status == 'hapus'){
                $produk = Produk::find($id);
                $produk->delete();
                return redirect('produk');
            }
        }else{
            return view('pages.produk.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $produk =  Produk::find($id);
        if($_POST['produk_penawaran']){
            $produk->id_produk_penawaran = $_POST['produk_penawaran'];
        }
        $produk->id_company = Auth::user()->id_company;
        $produk->nama = $_POST['nama'];
        $produk->kode = $_POST['kode'];
        $produk->unit = $_POST['satuan'];
        $produk->kategori = $_POST['kategori'];
        if(isset($_POST['batas_minimum'])){
            if(!$produk->stok){
                $produk->stok = 0;
            }
            $produk->batas_stok_minimum = $_POST['batas_stok_minimum'];
        }
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
