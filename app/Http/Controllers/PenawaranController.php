<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Approval;
use App\Models\Detail_penjualan;
use App\Models\Gudang;
use App\Models\Kontak;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenawaranController extends Controller
{
    public function index()
    {
        $data['sidebar'] = 'penjualan';
        $data['penawaran'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','penawaran')
                                        ->orderBy('id','DESC')
                                        ->get();
        return view('pages.penawaran.index', $data);
    }
    public function penawaran($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['additional_alamat'] = Alamat::get()->toJson();
        if($id != null){
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penawaran.insert', $data);
    }

    public function insert_penawaran(Request $request)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Penawaran Penjualan');

        DB::beginTransaction();
        $penjualan = new Penjualan;
        $penjualan->insert($is_requester,$request, null, 'penawaran');
        DB::commit();

        return redirect('penawaran/detail/'.$penjualan->id);
    }

    public function update_penawaran(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $penjualan->edit($request);
        DB::commit();

        return redirect('penawaran/detail/'.$penjualan->id);
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
        $count_pengiriman = Penjualan::where('id_pemesanan',$data['penjualan']->id_pemesanan)
                                            ->where('jenis','pengiriman')
                                            ->count();
        if($data['penjualan']->jenis == 'penagihan' && $count_pengiriman > 0){
            $data['pengiriman'] = Penjualan::where('id_pemesanan',$data['penjualan']->id_pemesanan)
                                            ->where('jenis','pengiriman')
                                            ->get();
        }
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                ->leftJoin('penjualan','jurnal.id','=','penjualan.id_jurnal')
                                ->select('jurnal.*')
                                ->where('penjualan.id',$id)
                                ->first();
        return view('pages.penjualan.detail', $data);
    }
}
