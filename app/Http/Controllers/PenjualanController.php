<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Detail_jurnal;
use App\Models\Detail_pembayaran_penjualan;
use App\Models\Detail_penjualan;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Pembayaran_penjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenjualanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'penjualan';
        $data['penagihan'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','penagihan')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['penawaran'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','penawaran')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['pemesanan'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','pemesanan')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['belum_dibayar'] = number_format(Penjualan::where('tanggal_jatuh_tempo','>',date('Y-m-d'))
                                        ->where('penjualan.jenis','penagihan')
                                        ->sum('sisa_tagihan'),2,',','.');
        return view('pages.penjualan.index', $data);
    }
    public function detail($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['penjualan'] = Penjualan::with([
                                                'detail_penjualan.produk',
                                                'detail_pembayaran_penjualan' => function ($query){
                                                    $query->orderBy('detail_pembayaran_penjualan.id_pembayaran_penjualan','desc');
                                                },
                                                'penawaran' => function ($query){
                                                    $query->select('id', 'no_str');
                                                },
                                                'pemesanan' => function ($query){
                                                    $query->select('id', 'no_str');
                                                }
                                            ])
                                        ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->leftJoin('penjualan as penawaran', 'penjualan.id_penawaran', '=', 'penawaran.id')
                                        ->leftJoin('penjualan as pemesanan', 'penjualan.id_pemesanan', '=', 'pemesanan.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id',$id)
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->first();
        if($data['penjualan']->jenis == 'pemesanan'){
            $data['penagihan'] = Penjualan::where('id_pemesanan',$id)
                                            ->where('jenis','penagihan')
                                            ->get();
        }
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                ->leftJoin('penjualan','jurnal.id','=','penjualan.id_jurnal')
                                ->select('jurnal.*')
                                ->where('penjualan.id',$id)
                                ->first();
        return view('pages.penjualan.detail', $data);
    }

    public function pembayaran($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        $data['penjualan'] = Penjualan::where('id',$id)->first();
        $data['pembayaran'] = Kontak::with(['penjualan' => function ($query){
                                        $query->where('jenis','penagihan');
                                        $query->orderBy('id', 'desc');
                                    }])
                                    ->select('kontak.*','kontak.nama as nama_pelanggan')
                                    ->where('kontak.id',$data['penjualan']->id_pelanggan)
                                    ->where('kontak.id_company',Auth::user()->id_company)
                                    ->first();
        return view('pages.penjualan.pembayaran', $data);
    }

    public function penagihan($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penagihan', $data);
    }

    public function pemesanan($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.pemesanan', $data);
    }

    public function penawaran($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penawaran', $data);
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penawaran'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.pemesanan', $data);
    }

    public function penawaran_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penawaran'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penagihan', $data);
    }
    public function pemesanan_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pemesanan'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penagihan', $data);
    }

    public function penerimaan_pembayaran(Request $request)
    {
        $data['sidebar'] = 'penjualan';
        
        $jurnal = new Jurnal;
        $jurnal->pembayaran_penjualan($request);

        $pembayaran_penjualan = new Pembayaran_penjualan;
        $pembayaran_penjualan->insert($request, $jurnal->id);

        return redirect('penjualan/receive_payment/'.$pembayaran_penjualan->id);
    }

    public function receive_payment(Request $request, $id)
    {
        $data['sidebar'] = 'penjualan';
        $data['detail_pembayaran_penjualan'] = Detail_pembayaran_penjualan::with('pembayaran_penjualan', 'penjualan.kontak')
                                            ->where('id_pembayaran_penjualan',$id)
                                            ->get();
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                            ->leftJoin('pembayaran_penjualan','jurnal.id','=','pembayaran_penjualan.id_jurnal')
                                            ->select('jurnal.*')
                                            ->where('pembayaran_penjualan.id',$id)
                                            ->first();
        return view('pages.penjualan.receive_payment',$data);
    }

    public function insert_penagihan(Request $request)
    {
        $jurnal = new Jurnal;
        $jurnal->penjualan($request);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'penagihan');

        return redirect('penjualan');
    }

    public function update_penagihan(Request $request, $id)
    {        
        $penjualan = Penjualan::find($id);
        $jurnal = Jurnal::find($penjualan->id_jurnal);
        $jurnal->penjualan($request, $id);
        $penjualan->edit($request);

        return redirect('penjualan');
    }

    public function insert_penawaran(Request $request)
    {
        $penjualan = new Penjualan;
        $penjualan->insert($request, null, 'penawaran');

        return redirect('penjualan');
    }

    public function update_penawaran(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->edit($request);

        return redirect('penjualan');
    }

    public function insert_penawaran_pemesanan(Request $request, $id)
    {
        $penjualan = new Penjualan;
        $penjualan->insert($request, null, 'pemesanan', $id);

        return redirect('penjualan');
    }

    public function insert_pemesanan_penagihan(Request $request, $id)
    {
        $jurnal = new Jurnal;
        $jurnal->penjualan($request);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'penagihan', $id);

        return redirect('penjualan');
    }

    public function hapus($id){
        Penjualan::find($id)->delete();
        Detail_penjualan::where('id_penjualan',$id)->delete();
        
        return redirect('penjualan');
    }
}
