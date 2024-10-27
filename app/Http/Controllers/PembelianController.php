<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Company;
use App\Models\Detail_jurnal;
use App\Models\Detail_pembayaran_pembelian;
use App\Models\Detail_pembelian;
use App\Models\Gudang;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Pembayaran_pembelian;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Stok_gudang;
use App\Models\Transaksi_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;

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
        $data['pengiriman'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                        ->select('pembelian.*','kontak.nama as nama_supplier')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->where('pembelian.jenis','pengiriman')
                                        ->orderBy('id','DESC')
                                        ->get();
        $data['belum_dibayar'] = number_format(Pembelian::where('status','open')
                                        ->where('pembelian.jenis','faktur')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->sum('sisa_tagihan'),2,',','.');
        $data['telat_dibayar'] = number_format(Pembelian::where('status','open')
                                        ->where('pembelian.tanggal_jatuh_tempo','<',date('Y-m-d'))
                                        ->where('pembelian.jenis','faktur')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->sum('sisa_tagihan'),2,',','.');
        $data['pelunasan_30_hari_terakhir'] = number_format(Pembelian::leftJoin('detail_pembayaran_pembelian','pembelian.id','=','detail_pembayaran_pembelian.id_pembelian')
                                        ->leftJoin('pembayaran_pembelian','detail_pembayaran_pembelian.id_pembayaran_pembelian','=','pembayaran_pembelian.id')
                                        ->where('pembayaran_pembelian.tanggal_transaksi','>',now()->subDays(30)->endOfDay())
                                        ->where('pembelian.jenis','faktur')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->sum('jumlah_terbayar'),2,',','.');

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
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
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
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
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

    public function cetak_penawaran($id){
        $data['pembelian'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','kontak.id')
                                        ->where('pembelian.id',$id)
                                        ->first();
        $data['detail_pembelian'] = Detail_pembelian::leftjoin('produk','detail_pembelian.id_produk','produk.id')
                                                    ->where('detail_pembelian.id_pembelian',$id)
                                                    ->get();

        $data['company'] = Company::leftJoin('users','company.id','users.id_company')
                                    ->where('company.id', $data['pembelian']->id_company)
                                    ->first();

        // return view('pdf.pembelian.penawaran' , $data);

        return Pdf::view('pdf.pembelian.penawaran' , $data)->format('a4')
                ->name('penawaran_pembelian.pdf');
    }

    public function cetak_pemesanan($id){
        $data['pembelian'] = Pembelian::leftJoin('kontak','pembelian.id_supplier','kontak.id')
                                        ->where('pembelian.id',$id)
                                        ->first();
        $data['detail_pembelian'] = Detail_pembelian::leftjoin('produk','detail_pembelian.id_produk','produk.id')
                                                    ->where('detail_pembelian.id_pembelian',$id)
                                                    ->get();

        $data['company'] = Company::leftJoin('users','company.id','users.id_company')
                                    ->where('company.id', $data['pembelian']->id_company)
                                    ->first();

        // return view('pdf.pembelian.penawaran' , $data);

        return Pdf::view('pdf.pembelian.pemesanan' , $data)->format('a4')
                ->name('pemesanan_pembelian.pdf');
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
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

    public function pemesanan_pengiriman($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pemesanan'] = true;
            $data['pembelian'] = Pembelian::join('kontak','id_supplier','=','kontak.id')
                                            ->select('pembelian.*','kontak.nama')->where('pembelian.id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::join('produk','detail_pembelian.id_produk','=','produk.id')
                                                        ->select('detail_pembelian.*','produk.unit')
                                                        ->where('detail_pembelian.id_pembelian',$id)->get();
        }
        return view('pages.pembelian.pengiriman', $data);
    }

    public function pemesanan_faktur($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pemesanan'] = true;
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.faktur', $data);
    }

    public function pengiriman_faktur($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pengiriman'] = true;
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
        $data['pembayaran_pembelian'] = Pembayaran_pembelian::where('id',$id)->first();
        $data['detail_pembayaran_pembelian'] = Detail_pembayaran_pembelian::with('pembayaran_pembelian', 'pembelian.kontak')
                                            ->where('id_pembayaran_pembelian',$id)
                                            ->get();
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

        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $produk = Produk::find($request->input('produk')[$i]);
            if($produk->batas_stok_minimum){
                $produk->stok = $produk->stok + $request->input('kuantitas')[$i];

                $detail_pembelian = Detail_pembelian::where('id_company',Auth::user()->id_company)
                                                    ->where('id_produk',$request->input('produk')[$i])
                                                    ->select(DB::raw('sum(kuantitas) as kuantitas'),DB::raw('sum(harga_satuan) as harga_satuan'))
                                                    ->first();
                if($produk->stok> 0){
                    $produk->harga_beli_rata_rata = $detail_pembelian->harga_satuan / $detail_pembelian->kuantitas;
                }else{
                    $produk->harga_beli_rata_rata = 0;
                }
                
                $produk->save();
            }
        }

        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function update_faktur(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $jurnal = Jurnal::find($pembelian->id_jurnal);
        $jurnal->pembelian($request, $id);
        $pembelian->edit($request);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_penawaran(Request $request)
    {
        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'penawaran');
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function update_penawaran(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $pembelian->edit($request);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_penawaran_pemesanan(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'pemesanan', $id);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_pemesanan(Request $request)
    {
        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'pemesanan');
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function update_pemesanan(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $pembelian->ubah($request, 'pemesanan');
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_pemesanan_pengiriman(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pengiriman_pembelian($request);
        
        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'pengiriman', $id);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_pemesanan_faktur(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembelian($request);
        
        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur', $id);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }
    public function insert_pengiriman_faktur(Request $request, $id)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pengiriman_faktur($request);
        
        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur', $id);
        DB::commit();


        return redirect('pembelian/detail/'.$pembelian->id);
    }
    public function hapus($id){
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        if($pembelian->jenis == 'penawaran'){
            Detail_pembelian::where('id_pembelian',$id)->delete();
            $pembelian->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            DB::commit();
            return redirect('pembelian');
        }else if($pembelian->jenis == 'pemesanan'){
            Detail_pembelian::where('id_pembelian',$pembelian->id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            if($pembelian->id_penawaran){
                $penawaran = Pembelian::find($pembelian->id_penawaran);
                $penawaran->status = 'open';
                $penawaran->save();
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian/detail/'.$penawaran->id);
            }else{
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian');
            }
        }else if($pembelian->jenis == 'pengiriman'){
            //updated
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$pembelian->id_jurnal)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }

            Detail_jurnal::where('id_jurnal',$pembelian->id_jurnal)->delete();
            Jurnal::find($pembelian->id_jurnal)->delete();

            $detail_pembelian = Detail_pembelian::where('id_pembelian',$pembelian->id)->get();
            foreach($detail_pembelian as $v){
                $produk = Produk::find($v->id_produk);
                $produk->stok = $produk->stok - $v->kuantitas;
                $produk->save();
            }

            Detail_pembelian::where('id_pembelian',$pembelian->id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            if($pembelian->id_pemesanan){
                $pemesanan = Pembelian::find($pembelian->id_pemesanan);
                $pemesanan->id_pemesanan = null;
                $pemesanan->status = 'open';
                $pemesanan->save();
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian/detail/'.$pemesanan->id);
            }else{
                return redirect('pembelian');
            }
        }else if($pembelian->jenis == 'faktur'){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$pembelian->id_jurnal)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }

            $detail_pembayaran_pembelian = Detail_pembayaran_pembelian::where('id_pembelian',$id)->get();
            foreach($detail_pembayaran_pembelian as $v){
                $pembayaran_pembelian = Pembayaran_pembelian::find($v->id_pembayaran_pembelian);
                $detail_jurnal = Detail_jurnal::where('id_jurnal',$pembayaran_pembelian->id_jurnal)->get();
                foreach($detail_jurnal as $v){
                    $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                                ->where('id_akun',$v->id_akun)->first();
                    $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                    $akun_company->save();
                }
                Detail_jurnal::where('id_jurnal',$pembayaran_pembelian->id_jurnal)->delete();
                Jurnal::find($pembayaran_pembelian->id_jurnal)->delete();
                $pembayaran_pembelian->delete();
            }
            Detail_pembayaran_pembelian::where('id_pembelian',$id)->delete();

            Detail_jurnal::where('id_jurnal',$pembelian->id_jurnal)->delete();
            Jurnal::find($pembelian->id_jurnal)->delete();

            $detail_pembelian = Detail_pembelian::where('id_pembelian',$pembelian->id)->get();
            foreach($detail_pembelian as $v){
                $produk = Produk::find($v->id_produk);
                $produk->stok = $produk->stok - $v->kuantitas;

                $detail_pembelian_sum = Detail_pembelian::where('id_company',Auth::user()->id_company)
                                                    ->where('id_produk',$v->id_produk)
                                                    ->whereNot('id_pembelian', $id)
                                                    ->select(DB::raw('sum(kuantitas) as kuantitas'),DB::raw('sum(harga_satuan) as harga_satuan'))
                                                    ->first();
                if($produk->stok> 0 && $detail_pembelian_sum->kuantitas > 0){
                    $produk->harga_beli_rata_rata = $detail_pembelian_sum->harga_satuan / $detail_pembelian_sum->kuantitas;
                }else{
                    $produk->harga_beli_rata_rata = 0;
                }

                $produk->save();
            }

            Detail_pembelian::where('id_pembelian',$pembelian->id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();

            $pengiriman = Pembelian::find($pembelian->id_pemesanan);
            Stok_gudang::where('id_transaksi',$id)->delete();
            if(isset($pengiriman->id_pemesanan) && $pembelian->jenis == 'faktur'){
                $pengiriman = Pembelian::find($pengiriman->id_pemesanan);
                $pengiriman->status = 'open';
                $pengiriman->save();
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian/detail/'.$pengiriman->id);
            }else if(isset($pembelian->id_pemesanan)){
                $pemesanan = Pembelian::find($pembelian->id_pemesanan);
                $pemesanan->status = 'open';
                $pemesanan->save();
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian/detail/'.$pemesanan->id);
            }else{
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian');
            }
        }

    }

    public function hapus_pembayaran($id){
        DB::beginTransaction();

        $pembayaran_pembelian = Pembayaran_pembelian::find($id);

        $detail_jurnal = Detail_jurnal::where('id_jurnal',$pembayaran_pembelian->id_jurnal)->get();
        foreach($detail_jurnal as $v){
            $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                        ->where('id_akun',$v->id_akun)->first();
            $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
            $akun_company->save();
        }
        Detail_jurnal::where('id_jurnal',$pembayaran_pembelian->id_jurnal)->delete();
        Jurnal::find($pembayaran_pembelian->id_jurnal)->delete();

        $pembayaran_pembelian->delete();
        $detail_pembayaran_pembelian = Detail_pembayaran_pembelian::where('id_pembayaran_pembelian',$id)->first();
        $pembelian = Pembelian::find($detail_pembayaran_pembelian->id_pembelian);
        $pembelian->jumlah_terbayar = $pembelian->jumlah_terbayar - $detail_pembayaran_pembelian->jumlah;
        $pembelian->sisa_tagihan = $pembelian->sisa_tagihan + $detail_pembayaran_pembelian->jumlah;
        if($pembelian->total == $pembelian->sisa_tagihan){
            $pembelian->status = 'open';
        }else{
            $pembelian->status = 'partial';
        }
        $pembelian->save();

        $detail_pembayaran_pembelian->delete();

        DB::commit();
        return redirect('pembelian');
    }
}
