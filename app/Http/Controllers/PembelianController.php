<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Detail_jurnal;
use App\Models\Detail_pembayaran_pembelian;
use App\Models\Detail_pembelian;
use App\Models\Gudang;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Log;
use App\Models\Pembayaran_pembelian;
use App\Models\Pembelian;
use App\Models\Pengaturan_dokumen;
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

        $start = now()->subDays(30)->startOfDay();
        $end   = now()->endOfDay();
        $data['pelunasan_30_hari_terakhir'] = number_format(Pembelian::leftJoin('detail_pembayaran_pembelian','pembelian.id','=','detail_pembayaran_pembelian.id_pembelian')
                                        ->leftJoin('pembayaran_pembelian','detail_pembayaran_pembelian.id_pembayaran_pembelian','=','pembayaran_pembelian.id')
                                        ->whereRaw(
                                            "STR_TO_DATE(pembayaran_pembelian.tanggal_transaksi, '%d/%m/%Y') BETWEEN ? AND ?",
                                            [$start, $end]
                                        )
                                        ->where('pembelian.jenis','faktur')
                                        ->where('pembelian.id_company',Auth::user()->id_company)
                                        ->sum('jumlah_terbayar'),2,',','.');

        return view('pages.pembelian.index', $data);
    }
    public function detail($id)
    {
        // Cari data pembelian berdasarkan ID
        $pembelian = Pembelian::findOrFail($id);

        if ($pembelian->id_company !== Auth::user()->id_company) {
            abort(403, 'Unauthorized action.');
        }else{
            $data['sidebar'] = 'pembelian';
            $data['pembelian'] = Pembelian::with([
                                                'detail_pembelian.produk',
                                                'detail_pembelian.stok_gudang',
                                                'detail_pembayaran_pembelian' => function ($query){
                                                    $query->orderBy('detail_pembayaran_pembelian.id_pembayaran_pembelian','desc');
                                                },
                                                'penawaran' => function ($query){
                                                    $query->select('id', 'no_str');
                                                },
                                                'pemesanan' => function ($query){
                                                    $query->select('id', 'no_str');
                                                },
                                                'pengiriman' => function ($query){
                                                    $query->select('id', 'no_str');
                                                }
                                                ])
                                            ->leftJoin('kontak','pembelian.id_supplier','=','kontak.id')
                                            ->leftJoin('pembelian as pemesanan', 'pembelian.id_pemesanan', '=', 'pemesanan.id')
                                            ->leftJoin('pembelian as pengiriman', 'pembelian.id_pengiriman', '=', 'pengiriman.id')
                                            ->select('pembelian.*','kontak.nama as nama_supplier')
                                            ->where('pembelian.id',$id)
                                            ->where('pembelian.id_company',Auth::user()->id_company)
                                            ->first();
            
            if($data['pembelian']->jenis == 'pemesanan'){
                $data['faktur'] = Pembelian::where('id_pemesanan',$id)
                                                ->where('jenis','faktur')
                                                ->get();
            }

            if($data['pembelian']->jenis == 'pengiriman'){
                if(Auth::user()->id_gudang){
                    $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                            ->get();
                }else{
                    $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                            ->get();
                }
                $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                                ->get();
            }

            $count_pengiriman = Pembelian::where('id_pemesanan',$data['pembelian']->id_pemesanan)
                                                ->where('jenis','pengiriman')
                                                ->count();

            if($data['pembelian']->jenis == 'faktur' && $count_pengiriman > 0){
                $data['pengiriman'] = Pembelian::where('id_pemesanan',$data['pembelian']->id_pemesanan)
                                                ->where('jenis','pengiriman')
                                                ->get();
            }

            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                            ->leftJoin('pembelian','jurnal.id','=','pembelian.id_jurnal')
                                            ->select('jurnal.*')
                                            ->where('pembelian.id',$id)
                                            ->first();
            $data['log'] = Log::leftJoin('users','id_user','=','users.id')
                                ->select('log.*','users.name')
                                ->where('log.id_transaksi',$id)
                                ->orderBy('log.id','DESC')
                                ->first();

            $data['status_update'] = Log::leftJoin('users','id_user','=','users.id')
                                ->select('log.*','users.name','users.email')
                                ->where('log.id_transaksi',$id)
                                ->orderBy('log.id','DESC')
                                ->get();
        }
        return view('pages.pembelian.detail', $data);
    }

    public function faktur_pembayaran($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['faktur_payment'] = true;
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

    public function pembayaran($id)
    {   
        $data['sidebar'] = 'pembelian';
        $data['payment'] = true;
        $data['akun'] = Akun::where('id_kategori',3)->get();
        $data['detail_pembayaran_pembelian'] = Detail_pembayaran_pembelian::where('id_pembayaran_pembelian',$id)->first();
        $data['pembelian'] = Pembelian::where('id',$data['detail_pembayaran_pembelian']->id_pembelian)->first();
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
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
        if($id != null){
            $data['faktur'] = true;
            $data['pembelian'] = Pembelian::leftJoin('pembelian as pemesanan', 'pemesanan.id','=','pembelian.id_pemesanan')
                                            ->leftJoin('pembelian as pengiriman', 'pengiriman.id','=','pembelian.id_pengiriman')
                                            ->select('pembelian.*','pemesanan.no_str as no_str_pemesanan','pengiriman.no_str as no_str_pengiriman')
                                            ->where('pembelian.id',$id)->first();
            if($data['pembelian']->id_pemesanan){
                $data['pemesanan'] = true;
            }
            if($data['pembelian']->id_pengiriman){
                $data['pengiriman'] = true;
            }
            $data['detail_pembelian'] = Detail_pembelian::with('stok_gudang')->where('id_pembelian',$id)->get();
        }
        return view('pages.pembelian.faktur', $data);
    }

    public function pengiriman($id=null)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
        if($id != null){
            $data['pengiriman'] = true;
            $data['pembelian'] = Pembelian::leftJoin('pembelian as pemesanan', 'pemesanan.id','=','pembelian.id_pemesanan')
                                            ->select('pembelian.*','pemesanan.no_str as no_str_pemesanan')
                                            ->where('pembelian.id',$id)->first();
            if($data['pembelian']->id_pemesanan){
                $data['pemesanan'] = true;
            }
            $data['detail_pembelian'] = Detail_pembelian::join('produk','detail_pembelian.id_produk','=','produk.id')
                                                        ->select('detail_pembelian.*','produk.unit')
                                                        ->where('detail_pembelian.id_pembelian',$id)
                                                        ->get();
            if($data['pembelian']->id_pemesanan){
                $data['detail_pemesanan'] = Detail_pembelian::join('produk','detail_pembelian.id_produk','=','produk.id')
                                                            ->select('detail_pembelian.*','produk.unit')
                                                            ->where('detail_pembelian.id_pembelian',$data['pembelian']->id_pemesanan)
                                                            ->get();
            }
        }
        return view('pages.pembelian.pengiriman', $data);
    }

    public function pemesanan($id=null)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
        
        if($id != null){
            $data['pemesanan'] = true;
            $data['pembelian'] = Pembelian::where('id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::with('stok_gudang')->where('id_pembelian',$id)->get();
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

        // return Pdf::view('pdf.pembelian.pemesanan' , $data)->format('a4')->name('pemesanan_pembelian.pdf');

        $html = view('pdf.pembelian.pemesanan', $data)->render();

        // Buat objek mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4', // Ukuran sertifikat
            'orientation' => 'L', // L = Landscape
            'margin_left' => 0,  // Hilangkan margin kiri
            'margin_right' => 0, // Hilangkan margin kanan
            'margin_top' => 0,   // Hilangkan margin atas
            'margin_bottom' => 0 // Hilangkan margin bawah
        ]);

        // Atur background agar full-page
        // $mpdf->SetDefaultBodyCSS('background', "url('https://myedi.stma-trisakti.ac.id/img/background.png')");
        // $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

        // Full page rendering
        $mpdf->SetDisplayMode('fullpage');
        
        // Tambahkan HTML ke PDF
        $mpdf->WriteHTML($html);
        
        // Output PDF langsung di browser
        return response($mpdf->Output('pemesanan_pembelian.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'pembelian';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['supplier'] = Kontak::where('tipe','supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
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
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
        if($id != null){
            $data['pemesanan_pengiriman'] = true;
            $data['pemesanan'] = true;
            $data['pembelian'] = Pembelian::join('kontak','id_supplier','=','kontak.id')
                                            ->select('pembelian.*','kontak.nama')->where('pembelian.id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::join('produk','detail_pembelian.id_produk','=','produk.id')
                                                        ->select('detail_pembelian.*','produk.unit')
                                                        ->where('detail_pembelian.id_pembelian',$id)
                                                        ->get();
            $data['detail_pemesanan'] = Detail_pembelian::join('produk','detail_pembelian.id_produk','=','produk.id')
                                                        ->select('detail_pembelian.*','produk.unit')
                                                        ->where('detail_pembelian.id_pembelian',$id)
                                                        ->get();
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
        if(Auth::user()->id_gudang){
            $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                    ->get();
        }else{
            $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        }
        if($id != null){
            $data['pengiriman_faktur'] = true;
            $data['pengiriman'] = true;
            $data['pembelian'] = Pembelian::leftJoin('pembelian as pemesanan', 'pemesanan.id','=','pembelian.id_pemesanan')
                                            ->select('pembelian.*','pemesanan.no_str as no_str_pemesanan')
                                            ->where('pembelian.id',$id)->first();
            $data['detail_pembelian'] = Detail_pembelian::where('id_pembelian',$id)->get();
        }
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_pembelian','faktur')
                                                        ->get();

        return view('pages.pembelian.faktur', $data);
    }

    public function insert_faktur_pembayaran(Request $request)
    {
        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembayaran_pembelian($request);

        $pembayaran_pembelian = new Pembayaran_pembelian;
        $pembayaran_pembelian->insert($request, $jurnal->id);
        DB::commit();

        return redirect('pembelian/receive_payment/'.$pembayaran_pembelian->id);
    }

    public function update_pembayaran(Request $request, $id)
    {
        DB::beginTransaction();
        $pembayaran_pembelian = Pembayaran_pembelian::find($id);
        $jurnal = Jurnal::find($pembayaran_pembelian->id_jurnal);
        $jurnal->pembayaran_pembelian($request, $id);
        $pembayaran_pembelian->ubah($request, $jurnal->id);
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
        $data['pembelian'] = Detail_pembayaran_pembelian::where('id_pembayaran_pembelian',$id)->first();
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                            ->leftJoin('pembayaran_pembelian','jurnal.id','=','pembayaran_pembelian.id_jurnal')
                                            ->select('jurnal.*')
                                            ->where('pembayaran_pembelian.id',$id)
                                            ->first();
        return view('pages.pembelian.receive_payment',$data);
    }

    public function insert_faktur(Request $request)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Faktur Pembelian');

        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembelian($request,null,$is_requester);

        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur', null,$is_requester);

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
        $pembelian->ubah($request, 'faktur');
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function insert_penawaran(Request $request)
    {
        $approval = new Approval;
        $is_requester = $approval->check_requester('Penawaran Pembelian');

        DB::beginTransaction();
        $pembelian = new Pembelian;
        $pembelian->insert($request, null, 'penawaran',null,$is_requester);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function update_penawaran(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $pembelian->edit($request, 'penawaran');
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
        $approval = new Approval;
        $is_requester = $approval->check_requester('Faktur Pembelian');

        DB::beginTransaction();
        $jurnal = new Jurnal;
        $jurnal->pembelian($request, null, $is_requester);
        
        $pembelian = new Pembelian;
        $pembelian->insert($request, $jurnal->id, 'faktur', $id, $is_requester);
        DB::commit();

        return redirect('pembelian/detail/'.$pembelian->id);
    }

    public function update_pengiriman(Request $request, $id)
    {
        DB::beginTransaction();
        $pembelian = Pembelian::find($id);
        $jurnal = Jurnal::find($pembelian->id_jurnal);
        $jurnal->pengiriman_pembelian($request, $id);
        $pembelian->ubah($request, 'pengiriman');
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
            Log::where('id_transaksi',$id)->delete();
            DB::commit();
            return redirect('pembelian');
        }else if($pembelian->jenis == 'pemesanan'){
            Detail_pembelian::where('id_pembelian',$id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Stok_gudang::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();
            if($pembelian->id_penawaran){
                $penawaran = Pembelian::find($pembelian->id_penawaran);
                if($penawaran){
                    $penawaran->status = 'open';
                    $penawaran->id_pemesanan = null;
                    $penawaran->save();
                    $pembelian->delete();
                    DB::commit();
                    return redirect('pembelian/detail/'.$penawaran->id);
                }else{
                    return redirect('pembelian');
                }
            }else{
                $pembelian->delete();
                DB::commit();
                return redirect('pembelian');
            }
        }else if($pembelian->jenis == 'pengiriman'){
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Stok_gudang::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();
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
                $produk->stok = (int) $produk->stok - (int) $v->kuantitas;
                $produk->save();
            }
            Detail_pembelian::where('id_pembelian',$pembelian->id)->delete();
            if($pembelian->id_pemesanan){
                $pemesanan = Pembelian::find($pembelian->id_pemesanan);
                if($pemesanan){
                    $pemesanan->id_pengiriman = null;
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

            if(!$pembelian->id_pengiriman){
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
            }

            Detail_pembelian::where('id_pembelian',$pembelian->id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Stok_gudang::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();

            if($pembelian->id_penawaran){
                $penawaran = Pembelian::find($pembelian->id_penawaran);
                $penawaran->id_faktur = null;
                $penawaran->save();
            }
            if($pembelian->id_pemesanan){
                $pemesanan = Pembelian::find($pembelian->id_pemesanan);
                $pemesanan->id_faktur = null;
                $pemesanan->save();
            }
            if($pembelian->id_pengiriman){
                $pengiriman = Pembelian::find($pembelian->id_pengiriman);
                $pengiriman->id_faktur = null;
                $pengiriman->save();
            }

            if(isset($pembelian->id_pengiriman)){
                $pengiriman = Pembelian::find($pembelian->id_pengiriman);
                if($pengiriman){
                    $pengiriman->status = 'open';
                    $pengiriman->save();
                    $pembelian->delete();
                    DB::commit();
                    return redirect('pembelian/detail/'.$pengiriman->id);
                }else{
                    $pembelian->delete();
                    DB::commit();
                    return redirect('pembelian');
                }
            }else if(isset($pembelian->id_pemesanan)){
                $pemesanan = Pembelian::find($pembelian->id_pemesanan);
                if($pemesanan){
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

        // $pembayaran_pembelian->delete();
        $detail_pembayaran_pembelian = Detail_pembayaran_pembelian::where('id_pembayaran_pembelian',$id);
        foreach($detail_pembayaran_pembelian->get() as $v){
            $pembelian = Pembelian::find($v->id_pembelian);
            $pembelian->jumlah_terbayar = $pembelian->jumlah_terbayar - $v->jumlah > 0 ? $pembelian->jumlah_terbayar - $v->jumlah : null;
            $pembelian->sisa_tagihan = $pembelian->sisa_tagihan + $v->jumlah;
            $pembelian->status = $pembelian->total == $pembelian->sisa_tagihan ? 'open' : 'partial';
            $pembelian->tanggal_pembayaran = null;
            $pembelian->save();
        }
        $id_pembelian = $pembelian->id;
        $detail_pembayaran_pembelian->delete();
        $pembayaran_pembelian->delete();

        DB::commit();
        return redirect('pembelian/detail/'.$id_pembelian);
    }
}
