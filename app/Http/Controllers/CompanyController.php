<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun_company;
use App\Models\Company;
use App\Models\Detail_jurnal;
use App\Models\Detail_pembayaran_pembelian;
use App\Models\Detail_pembayaran_penjualan;
use App\Models\Detail_pembelian;
use App\Models\Detail_penjualan;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Log;
use App\Models\Pembayaran_pembelian;
use App\Models\Pembayaran_penjualan;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Status_pengiriman;
use App\Models\Stok_gudang;
use App\Models\Transaksi_produk;
use App\Models\Transaksi_produk_penawaran;
use App\Models\Transfer_uang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'company';
        $data['company'] = Company::join('users','company.id','users.id_company')
                                    ->select('company.id as id_company','users.*')
                                    ->get();
        return view('superadmin.company.index', $data);
    }

    public function reset()
    {
        $id_company = Auth::user()->id_company;
        DB::beginTransaction();
        Akun_company::where('id_company',$id_company)->update(['saldo' => 0]);
        Detail_jurnal::where('id_company',$id_company)->delete();
        Detail_pembayaran_pembelian::where('id_company',$id_company)->delete();
        Detail_pembayaran_penjualan::where('id_company',$id_company)->delete();
        Detail_pembelian::where('id_company',$id_company)->delete();
        Detail_penjualan::where('id_company',$id_company)->delete();
        Jurnal::where('id_company',$id_company)->delete();
        Log::where('id_company',$id_company)->delete();
        Pembayaran_pembelian::where('id_company',$id_company)->delete();
        Pembayaran_penjualan::where('id_company',$id_company)->delete();
        Pembelian::where('id_company',$id_company)->delete();
        Penjualan::where('id_company',$id_company)->delete();
        Produk::where('id_company',$id_company)->update(['stok' => 0]);
        Status_pengiriman::where('id_company',$id_company)->delete();
        Stok_gudang::where('id_company',$id_company)->delete();
        Transaksi_produk::where('id_company',$id_company)->delete();
        Transaksi_produk_penawaran::where('id_company',$id_company)->delete();
        Transfer_uang::where('id_company',$id_company)->delete();
        DB::commit();
        return redirect()->back();
    }

    public function refresh_akun($id)
    {
        //delete detail jurnal yang tidak ada id jurnal
        $detail_jurnal = Detail_jurnal::where('id_company',$id)->get();
        $jurnal = Jurnal::where('id_company',$id)->get();
        $detailJurnalIds = $detail_jurnal->pluck('id_jurnal');
        $jurnalIds = $jurnal->pluck('id');
        $missingIds = $detailJurnalIds->diff($jurnalIds);
        Detail_jurnal::whereIn('id_jurnal', $missingIds)->delete();

        //base jurnal
        $akun = Akun_company::where('id_company',$id)->get();
        
        foreach($akun as $v){
            $debit = 0;
            $kredit = 0;
            $d[] = null;
            $detail_jurnal = Detail_jurnal::where('id_company',$id)
                                        ->where('id_akun',$v->id_akun)->get();
            foreach($detail_jurnal as $w){
                $debit += $w->debit;
                $kredit += $w->kredit;
                $d[] = $w->debit.'-'.$w->kredit;
            }
            
            DB::beginTransaction();
            $update_akun = Akun_company::find($v->id);
            $update_akun->saldo = $debit - $kredit;
            $update_akun->save();
            DB::commit();
        }
        return redirect('company');
    }
    public function insert(Request $request)
    {
        $supplier = new Kontak();
        $supplier->id_company = Auth::user()->id_company;
        $supplier->nama = $_POST['nama'];
        $supplier->nama_perusahaan = $_POST['nama_perusahaan'];
        $supplier->email = $_POST['email'];
        $supplier->nomor_handphone = $_POST['nomor_handphone'];
        $supplier->nomor_telepon = $_POST['nomor_telepon'];
        $supplier->alamat = $_POST['alamat'];
        $supplier->fax = $_POST['fax'];
        $supplier->npwp = $_POST['npwp'];
        $supplier->tipe = 'supplier';
        $supplier->save();

        return redirect('supplier');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'supplier';
        if($id){
            $data['supplier'] = Kontak::where('id', $id)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
            if($status == 'edit'){
                return view('pages.supplier.form', $data);
            }else if($status == 'detail'){
                return view('pages.supplier.detail', $data);
            }
        }else{
            return view('pages.supplier.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $supplier =  Kontak::find($id);
        $supplier->id_company = Auth::user()->id_company;
        $supplier->nama = $_POST['nama'];
        $supplier->nama_perusahaan = $_POST['nama_perusahaan'];
        $supplier->email = $_POST['email'];
        $supplier->nomor_handphone = $_POST['nomor_handphone'];
        $supplier->nomor_telepon = $_POST['nomor_telepon'];
        $supplier->alamat = $_POST['alamat'];
        $supplier->fax = $_POST['fax'];
        $supplier->npwp = $_POST['npwp'];
        $supplier->tipe = 'supplier';
        $supplier->save();

        return redirect('supplier');
    }

    public function hapus($id){
        $supplier = Kontak::find($id);
        $supplier->delete();

        return redirect('supplier');
    }
}
