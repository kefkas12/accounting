<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Detail_jurnal;
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
        $data['akun'] = Akun::join('akun_company','akun.id','=','akun_company.id_akun')
                            ->select('akun.*','akun_company.saldo as saldo_akun')
                            ->where('akun_company.id_company',Auth::user()->id_company)
                            ->get();
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
                $id_company = Auth::user()->id_company;
                $select = [
                    'detail_jurnal.*',
                    'penjualan.id as id_penjualan',
                    'pembelian.id as id_pembelian',
                    'pembayaran_pembelian.id as id_pembayaran_pembelian',
                    'pembayaran_penjualan.id as id_pembayaran_penjualan'];

                $data['transaksi_akun'] = Detail_jurnal::with('jurnal','akun')
                                                        ->join('jurnal','detail_jurnal.id_jurnal','jurnal.id')
                                                        ->leftJoin('penjualan', 'jurnal.id', 'penjualan.id_jurnal')
                                                        ->leftJoin('pembelian', 'jurnal.id', 'pembelian.id_jurnal')
                                                        ->leftJoin('pembayaran_pembelian', 'jurnal.id', 'pembayaran_pembelian.id_jurnal')
                                                        ->leftJoin('pembayaran_penjualan', 'jurnal.id', 'pembayaran_penjualan.id_jurnal')
                                                        ->select($select)
                                                        ->whereHas('jurnal', function($query) use ($id_company) {
                                                            $query->where('id_company', $id_company);
                                                        })
                                                        ->where('id_akun',$id)
                                                        ->orderBy('jurnal.tanggal_transaksi','ASC')
                                                        ->get();
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
