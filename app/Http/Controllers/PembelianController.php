<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Company;
use App\Models\Detail_pembayaran_pembelian;
use App\Models\Detail_pembelian;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Pembayaran_pembelian;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $data['faktur'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->where('pembelian.jenis','faktur')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['penawaran'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->where('pembelian.jenis','penawaran')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['pemesanan'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->where('pembelian.jenis','pemesanan')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['belum_dibayar'] = number_format(Pembelian::where('tanggal_jatuh_tempo','>',date('Y-m-d'))
                                        ->sum('sisa_tagihan'),2,',','.');

        return view('pages.pembelian.index', $data);
    }
    public function detail($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['pembelian'] = Pembelian::with([
                                            'detail_pembelian.produk',
                                            'detail_pembayaran_pembelian' => function ($query){
                                                $query->orderBy('detail_pembayaran_pembelian.id_pembayaran_pembelian','desc');
                                            },
                                            'penawaran' => function ($query){
                                                $query->select('id', 'no_str');
                                            },
                                            'pemesanan' => function ($query){
                                                $query->select('id', 'no_str');
                                            }
                                            ])
                                        ->leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->leftJoin('pembelian as penawaran', 'pembelian.id_penawaran', '=', 'penawaran.id')
                                        ->leftJoin('pembelian as pemesanan', 'pembelian.id_pemesanan', '=', 'pemesanan.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id',$id)
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->first();
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('pembelian','jurnal.id','=','pembelian.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('pembelian.id',$id)
                                        ->first();
        return view('pages.pembelian.detail', $data);
    }

    public function pembayaran($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        $data['pembelian'] = Pembelian::where('id',$id)->first();
        $data['pembayaran'] = Kontak::with(['pembelian' => function ($query){
                                        $query->where('jenis','faktur');
                                        $query->orderBy('id', 'desc');
                                    }])
                                    ->select('kontak.*','kontak.nama as nama_supplier')
                                    ->where('kontak.id',$data['pembelian']->id_supplier)
                                    ->where('kontak.id_company',Auth::user()->id_company)
                                    ->first();
        return view('pages.pembelian.pembayaran', $data);
    }
    
    public function faktur($id=null)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.faktur', $data);
    }

    public function pemesanan($id=null)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.pemesanan', $data);
    }

    public function penawaran($id=null)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.penawaran', $data);
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penawaran'] = true;
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.pemesanan', $data);
    }

    public function penawaran_faktur($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['penawaran'] = true;
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.faktur', $data);
    }

    public function pemesanan_faktur($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pemesanan'] = true;
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.faktur', $data);
    }

    public function penerimaan_pembayaran(Request $request)
    {
        DB::beginTransaction();        
        $jurnal = new Jurnal;
        $jurnal->pembayaran_pembelian($request);

        $pembayaran_pembelian = new Pembayaran_pembelian;
        $pembayaran_pembelian->insert($request, $jurnal->id);
        DB::commit();

        return redirect('pembelian/receive_payment/'.$pembayaran_pembelian->id);
    }

    public function receive_payment(Request $request, $id)
    {
        $data['sidebar'] = 'pembelian';
        $data['detail_pembayaran_pembelian'] = Detail_pembayaran_pembelian::with('pembayaran_pembelian', 'pembelian.kontak')
                                            ->where('id_pembayaran_pembelian',$id)
                                            ->get();
                                            // dd($data);
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                            ->leftJoin('pembayaran_pembelian','jurnal.id','=','pembayaran_pembelian.id_jurnal')
                                            ->select('jurnal.*')
                                            ->where('pembayaran_pembelian.id',$id)
                                            ->first();
        return view('pages.pembelian.receive_payment',$data);
    }

    public function insert_faktur(Request $request)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembelian($request);

        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur');
        DB::commit();

        return redirect('pembelian');
    }

    public function update_faktur(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $jurnal = Jurnal::find($pembelian->id_jurnal);
        $jurnal->pembelian($request);
        $pembelian->edit($request);
        DB::commit();

        return redirect('pembelian');
    }

    public function insert_penawaran(Request $request)
    {
        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'penawaran');
        DB::commit();

        return redirect('pembelian');
    }

    public function update_penawaran(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $pembelian->edit($request);
        DB::commit();

        return redirect('pembelian');
    }

    public function insert_penawaran_pemesanan(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'pemesanan', $id);
        DB::commit();

        return redirect('pembelian');
    }

    public function insert_pemesanan_faktur(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembelian($request);
        
        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur', $id);
        DB::commit();

        return redirect('pembelian');
    }

    public function hapus($id){
        DB::beginTransaction();
        Pembelian::find($id)->delete();
        Detail_pembelian::where('id_pembelian',$id)->delete();
        DB::commit();
        
        return redirect('pembelian');
    }
}
