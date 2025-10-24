<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Detail_jurnal;
use App\Models\Detail_penerimaan;
use App\Models\Detail_terima_uang;
use App\Models\Jurnal;
use App\Models\Kirim_uang;
use App\Models\Kontak;
use App\Models\Log;
use App\Models\Pembayaran;
use App\Models\Penerimaan;
use App\Models\Terima_uang;
use App\Models\Transfer_uang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $data['sidebar'] = 'kas_bank';

        $data['kas_bank'] = Akun_company::leftJoin('akun','akun_company.id_akun','akun.id')
                                        ->where('akun.id_kategori','3')
                                        ->where('akun_company.id_company',Auth::user()->id_company)
                                        ->select('akun_company.*','akun.nama','akun.nomor')
                                        ->orderBy('akun.nomor','asc')
                                        ->get();
        
        $data['total_saldo'] = Akun_company::leftJoin('akun','akun_company.id_akun','akun.id')
                                        ->where('akun.id_kategori','3')
                                        ->where('akun_company.id_company',Auth::user()->id_company)
                                        ->sum('akun_company.saldo');
        return view('pages.kas_bank.index', $data);
    }

    public function detail_kas_bank($id=null)
    {
        $data['sidebar'] = 'kas_bank';
        $data['akun'] = Akun::where('id',$id)->first();
        if($id){
            $data['detail_jurnal'] = Detail_jurnal::leftJoin('jurnal', 'detail_jurnal.id_jurnal', '=', 'jurnal.id')
                                                    ->select('detail_jurnal.*') // ambil semua field detail_jurnal
                                                    ->with([
                                                        'jurnal:id,tanggal_transaksi,kategori,no_str',
                                                        'jurnal.pembayaranPenjualan:id,id_jurnal,no_str,cara_pembayaran,subtotal',
                                                        'jurnal.pembayaranPenjualan.detail_pembayaran_penjualan:id,id_pembayaran_penjualan,id_penjualan,jumlah',
                                                        'jurnal.pembayaranPenjualan.detail_pembayaran_penjualan.penjualan:id,no_str,jenis,id_pelanggan',
                                                        'jurnal.pembayaranPenjualan.detail_pembayaran_penjualan.penjualan.pelanggan:id,nama',
                                                        'jurnal.pembayaranPembelian:id,id_jurnal,no_str,cara_pembayaran,subtotal',
                                                        'jurnal.pembayaranPembelian.detail_pembayaran_pembelian:id,id_pembayaran_pembelian,id_pembelian,jumlah',
                                                        'jurnal.pembayaranPembelian.detail_pembayaran_pembelian.pembelian:id,no_str,jenis,id_supplier',
                                                        'jurnal.pembayaranPembelian.detail_pembayaran_pembelian.pembelian.supplier:id,nama',
                                                        'jurnal.transferUang:id,id_jurnal,no_str,id_transfer_dari,transfer_dari,id_setor_ke,setor_ke',
                                                        'jurnal.kirimUang:id,id_jurnal,no_str,id_bayar_dari,bayar_dari',
                                                        'jurnal.terimaUang:id,id_jurnal,no_str,id_setor_ke,setor_ke,id_yang_membayar,tipe_yang_membayar',
                                                        'jurnal.terimaUang.kontak:id,nama',
                                                    ])
                                                    ->where('detail_jurnal.id_company', Auth::user()->id_company)
                                                    ->where('detail_jurnal.id_akun', $id)
                                                    ->orderByRaw("STR_TO_DATE(jurnal.tanggal_transaksi, '%d/%m/%Y') ASC")
                                                    ->get();
        }
        return view('pages.kas_bank.detail', $data);
    }

    public function transfer_uang($id=null)
    {
        $data['sidebar'] = 'kas_bank';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        if($id){
            $data['transfer_uang'] = Transfer_uang::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('transfer_uang','jurnal.id','=','transfer_uang.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('transfer_uang.id',$id)
                                        ->first();
        }

        return view('pages.kas_bank.transfer_uang.index', $data);
    }

    public function insert_transfer_uang(Request $request)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal();
        $jurnal->transfer_uang($request);

        $transfer_uang = new Transfer_uang();
        $transfer_uang->insert($request, $jurnal->id);
        DB::commit();

        return redirect('kas_bank/transfer_uang/detail/'.$transfer_uang->id);
    }

    public function update_transfer_uang(Request $request, $id)
    {
        DB::beginTransaction();
        $transfer_uang = Transfer_uang::find($id);
        $jurnal = Jurnal::find($transfer_uang->id_jurnal);
        $jurnal->transfer_uang($request, $id);
        $transfer_uang->ubah($request, $jurnal->id);
        DB::commit();

        return redirect('kas_bank/transfer_uang/detail/'.$transfer_uang->id);
    }

    public function detail_transfer_uang($id=null)
    {
        $data['sidebar'] = 'kas_bank';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        if($id){
            $data['transfer_uang'] = Transfer_uang::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('transfer_uang','jurnal.id','=','transfer_uang.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('transfer_uang.id',$id)
                                        ->first();
            return view('pages.kas_bank.transfer_uang.detail', $data);
        }

        return view('pages.kas_bank.transfer_uang.index', $data);
    }

    public function hapus_transfer_uang($id){
        DB::beginTransaction();
        $transfer_uang = Transfer_uang::find($id);

        $detail_jurnal = Detail_jurnal::where('id_jurnal',$transfer_uang->id_jurnal)->get();
        foreach($detail_jurnal as $v){
            $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                        ->where('id_akun',$v->id_akun)->first();
            $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
            $akun_company->save();
        }

        Detail_jurnal::where('id_jurnal',$transfer_uang->id_jurnal)->delete();
        Jurnal::find($transfer_uang->id_jurnal)->delete();
        Log::where('id_transaksi',$id)->delete();
        $transfer_uang->delete();
        DB::commit();
        
        return redirect('kas_bank');
    }

    public function terima_uang($id=null)
    {
        $data['sidebar'] = 'kas_bank';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        $data['akun_terima_dari'] = Akun::get();
        $data['kontak'] = Kontak::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id){
            $data['terima_uang'] = Terima_uang::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            $data['detail_terima_uang'] = Detail_terima_uang::with('akun')
                                        ->where('id_terima_uang', $id)
                                        ->get();
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('terima_uang','jurnal.id','=','terima_uang.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('terima_uang.id',$id)
                                        ->first();
        }

        return view('pages.kas_bank.terima_uang.index', $data);
    }

    public function insert_terima_uang(Request $request)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal();
        $jurnal->terima_uang($request);

        $terima_uang = new Terima_uang();
        $terima_uang->insert($request, $jurnal->id);
        DB::commit();

        return redirect('kas_bank/terima_uang/detail/'.$terima_uang->id);
    }

    public function update_terima_uang(Request $request, $id)
    {
        DB::beginTransaction();
        $terima_uang = Terima_uang::find($id);
        $jurnal = Jurnal::find($terima_uang->id_jurnal);
        $jurnal->terima_uang($request, $id);
        $terima_uang->ubah($request, $jurnal->id);
        DB::commit();

        return redirect('kas_bank/terima_uang/detail/'.$terima_uang->id);
    }

    public function detail_terima_uang($id=null)
    {
        $data['sidebar'] = 'terima_uang';
        $data['akun'] = Akun::where('id',$id)->first();
        $data['terima_uang'] = Terima_uang::where('id_company', Auth::user()->id_company)
                                        ->where('id',$id)
                                        ->first();
        $data['detail_terima_uang'] = Detail_terima_uang::with('akun')
                                        ->where('id_terima_uang', $id)
                                        ->get();
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('terima_uang','jurnal.id','=','terima_uang.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('terima_uang.id',$id)
                                        ->first();

        return view('pages.kas_bank.terima_uang.detail', $data);
    }

    public function hapus_terima_uang($id){
        DB::beginTransaction();
        $terima_uang = Terima_uang::find($id);

        $detail_jurnal = Detail_jurnal::where('id_jurnal',$terima_uang->id_jurnal)->get();
        foreach($detail_jurnal as $v){
            $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                        ->where('id_akun',$v->id_akun)->first();
            $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
            $akun_company->save();
        }

        Detail_jurnal::where('id_jurnal',$terima_uang->id_jurnal)->delete();
        Jurnal::find($terima_uang->id_jurnal)->delete();
        Log::where('id_transaksi',$id)->delete();
        $terima_uang->delete();
        DB::commit();
        
        return redirect('terima_uang');
    }

    public function kirim_uang($id=null)
    {
        $data['sidebar'] = 'kas_bank';
        $data['akun'] = Akun::where('id_kategori',3)->get();
        $data['akun_terima_dari'] = Akun::get();
        $data['kontak'] = Kontak::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id){
            $data['kirim_uang'] = Kirim_uang::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                        ->leftJoin('kirim_uang','jurnal.id','=','kirim_uang.id_jurnal')
                                        ->select('jurnal.*')
                                        ->where('kirim_uang.id',$id)
                                        ->first();
        }

        return view('pages.kas_bank.kirim_uang.index', $data);
    }

    public function pembayaran()
    {
        $data['sidebar'] = 'pembayaran';
        $data['pembayaran'] = Pembayaran::where('id_company',Auth::user()->id_company)
                                        ->get();
        return view('pages.kas_bank.pembayaran.index', $data);
    }
    public function insert_pembayaran(Request $request)
    {
        $pembayaran = new Pembayaran();
        $pembayaran->id_company = Auth::user()->id_company;
        $pembayaran->nama = $_POST['nama'];
        $pembayaran->nama_perusahaan = $_POST['nama_perusahaan'];
        $pembayaran->email = $_POST['email'];
        $pembayaran->nomor_handphone = $_POST['nomor_handphone'];
        $pembayaran->nomor_telepon = $_POST['nomor_telepon'];
        $pembayaran->alamat = $_POST['alamat'];
        $pembayaran->fax = $_POST['fax'];
        $pembayaran->npwp = $_POST['npwp'];
        $pembayaran->tipe = 'pembayaran';
        $pembayaran->save();

        return redirect('kas_bank/pembayaran');
    }
    public function detail_pembayaran($status=null,$id=null)
    {
        $data['sidebar'] = 'pembayaran';
        $data['kas_bank'] = Akun::where('id_kategori','3')->whereNot('id','129')->get();
        
        if($id){
            $data['pembayaran'] = Pembayaran::where('id', $id)
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
            if($status == 'edit'){
                return view('pages.kas_bank.pembayaran.form', $data);
            }else if($status == 'detail'){
                return view('pages.kas_bank.pembayaran.detail', $data);
            }
        }else{
            return view('pages.kas_bank.pembayaran.form', $data);
        }  
    }
    public function edit_pembayaran($id, Request $request)
    {
        $pelanggan =  Pembayaran::find($id);
        $pelanggan->id_company = Auth::user()->id_company;
        $pelanggan->nama = $_POST['nama'];
        $pelanggan->nama_perusahaan = $_POST['nama_perusahaan'];
        $pelanggan->email = $_POST['email'];
        $pelanggan->nomor_handphone = $_POST['nomor_handphone'];
        $pelanggan->nomor_telepon = $_POST['nomor_telepon'];
        $pelanggan->alamat = $_POST['alamat'];
        $pelanggan->fax = $_POST['fax'];
        $pelanggan->npwp = $_POST['npwp'];
        $pelanggan->tipe = 'pelanggan';
        $pelanggan->save();

        return redirect('pelanggan');
    }

    public function penerimaan()
    {
        $data['sidebar'] = 'penerimaan';
        $data['penerimaan'] = Penerimaan::leftJoin('akun','penerimaan.id_kas_bank','akun.id')->select('penerimaan.*','akun.nama')->where('id_company',Auth::user()->id_company)
                                        ->get();

        return view('pages.kas_bank.penerimaan.index', $data);
    }
    public function insert_penerimaan(Request $request)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal();
        $jurnal->penerimaan($request);

        $penerimaan = new Penerimaan();
        $penerimaan->id_company = Auth::user()->id_company;
        $penerimaan->id_kas_bank = $_POST['kas_bank'];
        $penerimaan->tanggal = $_POST['tanggal'];
        $penerimaan->nilai = $_POST['total_nilai'];
        $penerimaan->save();

        for($i=0; $i<count($_POST['akun']) ; $i++){
            if($_POST['akun'][$i] != ''){
                $detail_penerimaan = new Detail_penerimaan;
                $detail_penerimaan->id_company = Auth::user()->id_company;
                $detail_penerimaan->id_penerimaan = $penerimaan->id;
                $detail_penerimaan->id_akun = $_POST['akun'][$i];
                $detail_penerimaan->atas_penerimaan = $_POST['atas_penerimaan'][$i];
                $detail_penerimaan->deskripsi = $_POST['deskripsi'][$i];
                $detail_penerimaan->nilai = $_POST['nilai'][$i] != '' || $_POST['nilai'][$i] != null ? number_format((float)str_replace(",", "", $_POST['nilai'][$i]), 2, '.', '') : 0;

                $akun_company = Akun_company::where('id_akun',$_POST['akun'][$i])
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
                $saldo = $akun_company ? $akun_company->saldo : 0;

                $akun_company = Akun_company::where('id_akun', $_POST['akun'][$i])
                                            ->where('id_company', Auth::user()->id_company)
                                            ->update(['saldo' => $saldo - $detail_penerimaan->nilai ]);

                $detail_penerimaan->save();
            }
        }

        $akun_company = Akun_company::where('id_akun',$_POST['kas_bank'])
                                            ->where('id_company',Auth::user()->id_company)
                                            ->first();
        $saldo = $akun_company ? $akun_company->saldo : 0;

        $penerimaan = Akun_company::where('id_akun', $_POST['kas_bank'])
                                    ->where('id_company', Auth::user()->id_company)
                                    ->update(['saldo' => $saldo + $_POST['total_nilai']]);

        DB::commit();
        return redirect('kas_bank/penerimaan');
    }
    public function detail_penerimaan($status=null,$id=null)
    {
        $data['sidebar'] = 'penerimaan';
        $data['akun'] = Akun::whereNotIn('id_kategori', ['1','3','6','15'])->get();
        $data['kas_bank'] = Akun::where('id_kategori','3')->whereNot('id','129')->get();
        if($id){
            $data['penerimaan'] = Penerimaan::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            if($status == 'edit'){
                return view('pages.kas_bank.penerimaan.form', $data);
            }else if($status == 'detail'){
                return view('pages.kas_bank.penerimaan.detail', $data);
            }
        }else{
            return view('pages.kas_bank.penerimaan.form', $data);
        }  
    }
    public function edit_penerimaan($id, Request $request)
    {
        $penerimaan =  Penerimaan::find($id);
        $penerimaan->id_company = Auth::user()->id_company;
        $penerimaan->nama = $_POST['nama'];
        $penerimaan->nama_perusahaan = $_POST['nama_perusahaan'];
        $penerimaan->email = $_POST['email'];
        $penerimaan->nomor_handphone = $_POST['nomor_handphone'];
        $penerimaan->nomor_telepon = $_POST['nomor_telepon'];
        $penerimaan->alamat = $_POST['alamat'];
        $penerimaan->fax = $_POST['fax'];
        $penerimaan->npwp = $_POST['npwp'];
        $penerimaan->tipe = 'penerimaan';
        $penerimaan->save();

        return redirect('kas_bank/penerimaan');
    }
    
    
    // public function detail_transfer_uang($status=null,$id=null)
    // {
    //     $data['sidebar'] = 'transfer_uang';
    //     if($id){
    //         $data['transfer_uang'] = Transfer_uang::where('id', $id)
    //                                     ->where('id_company',Auth::user()->id_company)
    //                                     ->first();
    //         if($status == 'edit'){
    //             return view('pages.kas_bank.transfer_uang.form', $data);
    //         }else if($status == 'detail'){
    //             return view('pages.kas_bank.transfer_uang.detail', $data);
    //         }
    //     }else{
    //         return view('pages.kas_bank.transfer_uang.form', $data);
    //     }  
    // }
    public function edit_transfer_uang($id, Request $request)
    {
        $transfer_uang = Transfer_uang::find($id);
        $transfer_uang->id_company = Auth::user()->id_company;
        $transfer_uang->nama = $_POST['nama'];
        $transfer_uang->nama_perusahaan = $_POST['nama_perusahaan'];
        $transfer_uang->email = $_POST['email'];
        $transfer_uang->nomor_handphone = $_POST['nomor_handphone'];
        $transfer_uang->nomor_telepon = $_POST['nomor_telepon'];
        $transfer_uang->alamat = $_POST['alamat'];
        $transfer_uang->fax = $_POST['fax'];
        $transfer_uang->npwp = $_POST['npwp'];
        $transfer_uang->tipe = 'transfer_uang';
        $transfer_uang->save();

        return redirect('transfer_uang');
    }
}
