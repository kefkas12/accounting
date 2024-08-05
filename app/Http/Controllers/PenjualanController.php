<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Detail_jurnal;
use App\Models\Detail_pembayaran_penjualan;
use App\Models\Detail_penjualan;
use App\Models\Gudang;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Pembayaran_penjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Transaksi_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;
use Spatie\LaravelPdf\Facades\Pdf;

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
                                        ->whereNot('penjualan.status','draf')
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
        $data['pengiriman'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','pengiriman')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['membutuhkan_persetujuan'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.status','draf')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['belum_dibayar'] = number_format(Penjualan::where('tanggal_jatuh_tempo','>',date('Y-m-d'))
                                        ->where('penjualan.jenis','penagihan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->sum('sisa_tagihan'),2,',','.');

        $approval = new Approval;
        $data['is_approver'] = $approval->check_approver('penjualan');

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
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
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

    public function cetak_penawaran($id){
        $data['penjualan'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','kontak.id')
                                        ->where('penjualan.id',$id)
                                        ->first();
        $data['detail_penjualan'] = Detail_penjualan::leftjoin('produk','detail_penjualan.id_produk','produk.id')
                                                    ->where('detail_penjualan.id_penjualan',$id)
                                                    ->get();

        $data['company'] = Company::leftJoin('users','company.id','users.id_company')
                                    ->where('company.id', $data['penjualan']->id_company)
                                    ->first();

        return Pdf::view('pdf.penjualan.penawaran' , $data)->format('a4')
                ->name('penawaran_penjualan.pdf');
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
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

    public function pemesanan_pengiriman($id)
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
        return view('pages.penjualan.pengiriman', $data);
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

    public function pengiriman_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pengiriman'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penagihan', $data);
    }

    public function penerimaan_pembayaran(Request $request)
    {
        $data['sidebar'] = 'penjualan';
        
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembayaran_penjualan($request);

        $pembayaran_penjualan = new Pembayaran_penjualan;
        $pembayaran_penjualan->insert($request, $jurnal->id);
        DB::commit();

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
        $approval = new Approval;
        $is_requester = $approval->check_requester('Faktur Penjualan');

        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->penjualan($request,null,$is_requester);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'penagihan', null,$is_requester);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function update_penagihan(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $jurnal = Jurnal::find($penjualan->id_jurnal);
        $jurnal->penjualan($request, $id);
        $penjualan->edit($request);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function insert_penawaran(Request $request)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Penawaran Penjualan');

        DB::beginTransaction();
        $penjualan = new Penjualan;
        $penjualan->insert($request, null, 'penawaran',null,$is_requester);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function update_penawaran(Request $request, $id)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Penawaran Penjualan');
        
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $penjualan->edit($request);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function insert_penawaran_pemesanan(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = new Penjualan;
        $penjualan->insert($request, null, 'pemesanan', $id);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function insert_pemesanan(Request $request)
    {
        // $approval = new Approval;
        // $is_requester = $approval->check_requester('Penawaran Penjualan');

        DB::beginTransaction();
        $penjualan = new Penjualan;
        $penjualan->insert($request, null, 'pemesanan');
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function update_pemesanan(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $penjualan->edit($request);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function insert_pemesanan_pengiriman(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pengiriman_penjualan($request);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'pengiriman', $id);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }
    public function insert_pemesanan_penagihan(Request $request, $id)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Faktur Penjualan');

        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->penjualan($request, null, $is_requester);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'penagihan', $id, $is_requester);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }
    public function insert_pengiriman_penagihan(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pengiriman_penagihan($request);
        
        $penjualan = new Penjualan;
        $penjualan->insert($request, $jurnal->id, 'penagihan', $id);
        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }
    public function hapus($id){
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        if($penjualan->jenis == 'penawaran'){
            Detail_penjualan::where('id_penjualan',$id)->delete();
            $penjualan->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            DB::commit();
            return redirect('penjualan');
        }else if($penjualan->jenis == 'pemesanan'){
            Detail_penjualan::where('id_penjualan',$penjualan->id)->delete();
            $penjualan->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            DB::commit();

            $penawaran = Penjualan::find($penjualan->id_penawaran);
            $penawaran->status = 'open';
            $penawaran->save();

            return redirect('penjualan/detail/'.$penawaran->id);
        }else if($penjualan->jenis == 'penagihan'){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$penjualan->id_jurnal)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }

            $detail_pembayaran_penjualan = Detail_pembayaran_penjualan::where('id_penjualan',$id)->get();
            foreach($detail_pembayaran_penjualan as $v){
                $pembayaran_penjualan = Pembayaran_penjualan::find($v->id_pembayaran_penjualan);
                $detail_jurnal = Detail_jurnal::where('id_jurnal',$pembayaran_penjualan->id_jurnal)->get();
                foreach($detail_jurnal as $v){
                    $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                                ->where('id_akun',$v->id_akun)->first();
                    $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                    $akun_company->save();
                }
                Detail_jurnal::where('id_jurnal',$pembayaran_penjualan->id_jurnal)->delete();
                Jurnal::find($pembayaran_penjualan->id_jurnal)->delete();
                $pembayaran_penjualan->delete();
            }
            Detail_pembayaran_penjualan::where('id_penjualan',$id)->delete();

            Detail_jurnal::where('id_jurnal',$penjualan->id_jurnal)->delete();
            Jurnal::find($penjualan->id_jurnal)->delete();

            Transaksi_produk::where('id_transaksi',$id)->delete();

            if(isset($penjualan->id_pemesanan)){
                $pemesanan = Penjualan::find($penjualan->id_pemesanan);
                $pemesanan->status = 'open';
                $pemesanan->save();
                $penjualan->delete();
                DB::commit();
                return redirect('penjualan/detail/'.$pemesanan->id);
            }else{
                $penjualan->delete();
                DB::commit();
                return redirect('penjualan');
            }
            

            
        }
    }

    public function cetak_surat_jalan($id){
        $data['company'] = Company::where('id',Auth::user()->id_company)->first();
        $data['penjualan'] = Penjualan::where('id',$id)->first();
        $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();

        return view('pages.penjualan.cetak.surat_jalan',$data);
    }
    
    public function cetak_penagihan($id){
        $data['company'] = Company::where('id',Auth::user()->id_company)->first();
        $data['penjualan'] = Penjualan::where('id',$id)->first();
        $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();

        return view('pages.penjualan.cetak.penagihan',$data);
    }

    public function approve($id){
        $penjualan = Penjualan::find($id);
        $penjualan->status = 'open';
        $penjualan->save();

        if($penjualan->id_jurnal){
            $jurnal = Jurnal::find($penjualan->id_jurnal);
            $jurnal->status = 'approved';
            $jurnal->save();
        }
        
        return redirect('penjualan');
    }
}
