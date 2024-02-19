<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Detail_pembelian;
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
        $pembelian = new Pembelian;
        $pembelian->id_company = Auth::user()->id_company;
        $pembelian->tanggal_transaksi = $_POST['tanggal_transaksi'];
        $pembelian->no = $pembelian->no();
        $pembelian->no_str = 'Purchase Invoice #'.$pembelian->no();
        $pembelian->id_supplier = $_POST['supplier'];
        $pembelian->tanggal_jatuh_tempo = $_POST['tanggal_jatuh_tempo'];
        $pembelian->status = 'open';
        $pembelian->subtotal = $_POST['subtotal'];
        $pembelian->ppn = $_POST['ppn'];
        $pembelian->sisa_tagihan = $_POST['sisa_tagihan'];
        $pembelian->total = $_POST['total'];
        $pembelian->alamat = $_POST['alamat'];
        $pembelian->email = $_POST['email'];
        $pembelian->save();

        for($i=0; $i<count($_POST['produk']) ; $i++){
            $detail_pembelian = new Detail_pembelian;
            $detail_pembelian->id_company = Auth::user()->id_company;
            $detail_pembelian->id_pembelian = $pembelian->id;
            $detail_pembelian->id_produk = $_POST['produk'][$i];
            $detail_pembelian->kuantitas = $_POST['kuantitas'][$i];
            $detail_pembelian->harga_satuan = $_POST['harga_satuan'][$i];
            $detail_pembelian->pajak = $_POST['jumlah'][$i] * $_POST['pajak'][$i] / 100;
            $detail_pembelian->jumlah = $_POST['jumlah'][$i];
            $detail_pembelian->save();
        }

        return redirect('pembelian');
    }

    public function hapus($id){
        Pembelian::find($id)->delete();
        Detail_pembelian::where('id_pembelian',$id)->delete();
        
        return redirect('pembelian');
    }
}
