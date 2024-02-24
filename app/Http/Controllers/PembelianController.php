<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Company;
use App\Models\Detail_pembelian;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Pembayaran_pembelian;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PembelianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'pembelian';
        $data['pembelian'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['belum_dibayar'] = number_format(Pembelian::where('tanggal_jatuh_tempo','>',date('Y-m-d'))
                                        ->sum('sisa_tagihan'),2,',','.');

        return view('pages.pembelian.index', $data);
    }
    public function detail($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['pembelian'] = Pembelian::with('detail_pembelian.produk')
                                        ->leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->where('pembelian.id',$id)
                                        ->first();

        $data['pembayaran_pembelian'] = Pembayaran_pembelian::where('id_pembelian',$id)->get();
        return view('pages.pembelian.detail', $data);
    }

    public function pembayaran($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['pembelian'] = Pembelian::with('detail_pembelian.produk')
                                        ->leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id',$id)
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->first();
        return view('pages.pembelian.pembayaran', $data);
    }

    public function faktur()
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)->get();
        return view('pages.pembelian.faktur', $data);
    }

    public function insert(Request $request)
    {
        $jurnal = new Jurnal;
        $jurnal->pembelian($request);

        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id);

        return redirect('pembelian');
    }

    public function hapus($id){
        Pembelian::find($id)->delete();
        Detail_pembelian::where('id_pembelian',$id)->delete();
        
        return redirect('pembelian');
    }
}
