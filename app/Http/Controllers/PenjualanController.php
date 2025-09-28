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
use App\Models\Dokumen_penjualan;
use App\Models\Gudang;
use App\Models\Jurnal;
use App\Models\Kontak;
use App\Models\Log;
use App\Models\Pembayaran_penjualan;
use App\Models\Pengaturan_dokumen;
use App\Models\Pengaturan_nama;
use App\Models\Pengaturan_produk;
use App\Models\Pengaturan_status_pengiriman;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Produk_penawaran;
use App\Models\Status_pengiriman;
use App\Models\Stok_gudang;
use App\Models\Transaksi_produk;
use App\Models\Transaksi_produk_penawaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;
use Spatie\LaravelPdf\Facades\Pdf;

use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'penjualan';

        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                        ->where('fitur','Produk penawaran')
                                        ->where('status','active')
                                        ->first();
        if(isset($data['produk_penawaran'])){
            $data['penawaran'] = Penjualan::with('detail_penjualan.produk_penawaran')
                                            ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                            ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                            ->where('penjualan.id_company',Auth::user()->id_company)
                                            ->where('penjualan.jenis','penawaran')
                                            ->orderBy('id','DESC')
                                            ->get();
        }else{
            $data['penawaran'] = Penjualan::with('detail_penjualan.produk')
                                            ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                            ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                            ->where('penjualan.id_company',Auth::user()->id_company)
                                            ->where('penjualan.jenis','penawaran')
                                            ->orderBy('id','DESC')
                                            ->get();
        }
        

        $data['pemesanan'] = Penjualan::with('detail_penjualan.produk')
                                        ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->leftJoin('penjualan as penawaran','penjualan.id_penawaran','=','penawaran.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan', 'penawaran.no_str as no_str_penawaran')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','pemesanan')
                                        ->orderBy('id','DESC')
                                        ->get();

        $data['pengiriman'] = Penjualan::with('detail_penjualan.produk')
                                        ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->leftJoin('penjualan as pemesanan','penjualan.id_pemesanan','=','pemesanan.id')
                                        ->leftJoin('penjualan as penawaran','pemesanan.id_penawaran','=','penawaran.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan', 'penawaran.no_str as no_str_penawaran', 'pemesanan.no_str as no_str_pemesanan', 'pemesanan.id_penawaran')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','pengiriman')
                                        ->orderBy('id','DESC')
                                        ->get();

        $data['penagihan'] = Penjualan::with('detail_penjualan.produk')
                                        ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->leftJoin('penjualan as pemesanan','penjualan.id_pemesanan','=','pemesanan.id')
                                        ->leftJoin('penjualan as pengiriman','penjualan.id_pengiriman','=','pengiriman.id')
                                        ->leftJoin('penjualan as penawaran','penjualan.id_penawaran','=','penawaran.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan','penawaran.no_str as no_str_penawaran','pemesanan.no_str as no_str_pemesanan','pengiriman.tanggal_transaksi as tanggal_transaksi_pengiriman')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','penagihan')
                                        ->orderBy('id','DESC')
                                        ->get();

        $data['membutuhkan_persetujuan'] = Penjualan::with('detail_penjualan.produk')
                                                    ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                                    ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                                    ->where('penjualan.id_company',Auth::user()->id_company)
                                                    ->where('penjualan.status','draf')
                                                    ->orderBy('id','DESC')
                                                    ->get();

        $data['selesai'] = Penjualan::with([
                                            'dokumen_penjualan.dokumen',
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
                                        ->leftJoin('penjualan as pemesanan','penjualan.id_pemesanan','=','pemesanan.id')
                                        ->leftJoin('penjualan as pengiriman','penjualan.id_pengiriman','=','pengiriman.id')
                                        ->leftJoin('penjualan as penagihan','penjualan.id_penagihan','=','penagihan.id')
                                        ->leftJoin('penjualan as penawaran','penjualan.id_penawaran','=','penawaran.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan','penawaran.no_str as no_str_penawaran','pemesanan.no_str as no_str_pemesanan','pengiriman.tanggal_transaksi as tanggal_transaksi_pengiriman')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->where('penjualan.jenis','selesai')
                                        ->whereNot('penjualan.status','draf')
                                        ->orderBy('id','DESC')
                                        ->get();

        $data['belum_dibayar'] = number_format(Penjualan::where('tanggal_jatuh_tempo','>',date('Y-m-d'))
                                        ->where('penjualan.jenis','penagihan')
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->sum('sisa_tagihan'),2,',','.');

        $approval = new Approval;
        $data['is_approver'] = $approval->check_approver('penjualan');

        $data['pengaturan_nama'] = Pengaturan_nama::where('id_company',Auth::user()->id_company)->get();
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)->get();

        return view('pages.penjualan.index', $data);
    }
    public function detail($id)
    {
        // Cari data penjualan berdasarkan ID
        $penjualan = Penjualan::findOrFail($id);
        
        // Cek apakah user yang sedang login memang pemilik data tersebut
        if ($penjualan->id_company !== Auth::user()->id_company) {
            abort(403, 'Unauthorized action.');
        }else{
            $data['sidebar'] = 'penjualan';
            $data['penjualan'] = Penjualan::with([
                                    'detail_penjualan.produk',
                                    'detail_penjualan.produk_penawaran',
                                    'detail_penjualan.stok_gudang',
                                    'detail_pembayaran_penjualan' => function ($query){
                                        $query->orderBy('detail_pembayaran_penjualan.id_pembayaran_penjualan','desc');
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
                                ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                ->leftJoin('penjualan as penawaran', 'penjualan.id_penawaran', '=', 'penawaran.id')
                                ->leftJoin('penjualan as pemesanan', 'penjualan.id_pemesanan', '=', 'pemesanan.id')
                                ->leftJoin('penjualan as pengiriman', 'penjualan.id_pengiriman', '=', 'pengiriman.id')
                                ->select('penjualan.*','kontak.nama as nama_pelanggan')
                                ->where('penjualan.id',$id)
                                ->where('penjualan.id_company',Auth::user()->id_company)
                                ->first();
                                            
            if($data['penjualan']->jenis == 'penawaran'){
                $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                            ->where('fitur','Produk penawaran')
                                            ->where('status','active')
                                            ->first();
            }
            if($data['penjualan']->jenis == 'pemesanan'){
                $data['penagihan'] = Penjualan::where('id_pemesanan',$id)
                                                ->where('jenis','penagihan')
                                                ->get();
                $data['dokumen_penjualan'] = Dokumen_penjualan::with('dokumen')->where('id_pemesanan',$id)->get();
            }

            if($data['penjualan']->jenis == 'pengiriman'){
                $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Multiple gudang')
                                                ->where('status','active')
                                                ->first();
                if(Auth::user()->id_gudang){
                    $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
                                            ->get();
                }else{
                    $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                            ->get();
                }

                $data['pengaturan_status_pengiriman'] = Pengaturan_status_pengiriman::where('id_company',Auth::user()->id_company)
                                                ->get();
                $data['status_pengiriman'] = Status_pengiriman::where('id_pengiriman_penjualan',$id)
                                                ->get();
                $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                                ->get();
                $data['dokumen_penjualan'] = Dokumen_penjualan::with('dokumen')->where('id_pengiriman',$id)->get();
            }

            $count_pengiriman = Penjualan::where('id_pemesanan',$data['penjualan']->id_pemesanan)
                                                ->where('jenis','pengiriman')
                                                ->count();
            if($data['penjualan']->jenis == 'penagihan'){
                $data['dokumen_penjualan'] = Dokumen_penjualan::with('dokumen')->where('id_penagihan',$id)->get();
            }
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

            return view('pages.penjualan.detail', $data);
        }
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
        // $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
        //                                         ->where('fitur','Multiple gudang')
        //                                         ->where('status','active')
        //                                         ->first();
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        // if(Auth::user()->id_gudang){
        //     $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
        //                             ->get();
        // }else{
        //     $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
        //                             ->get();
        // }
        if($id != null){
            $data['penagihan'] = true;
            $data['penjualan'] = Penjualan::leftJoin('penjualan as penawaran', 'penawaran.id','=','penjualan.id_penawaran')
                                            ->leftJoin('penjualan as pemesanan', 'pemesanan.id','=','penjualan.id_pemesanan')
                                            ->leftJoin('penjualan as pengiriman', 'pengiriman.id','=','penjualan.id_pengiriman')
                                            ->select('penjualan.*','penawaran.no_str as no_str_penawaran','pemesanan.no_str as no_str_pemesanan','pengiriman.no_str as no_str_pengiriman')
                                            ->where('penjualan.id',$id)->first();
            if($data['penjualan']->id_penawaran){
                $data['penawaran'] = true;
            }
            if($data['penjualan']->id_pemesanan){
                $data['pemesanan'] = true;
            }
            if($data['penjualan']->id_pengiriman){
                $data['pengiriman'] = true;
            }
            $data['detail_penjualan'] = Detail_penjualan::with('stok_gudang')->where('id_penjualan',$id)->get();
        }
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','penagihan')
                                                        ->get();
        $data['dokumen_penjualan'] = Dokumen_penjualan::where('id_company',Auth::user()->id_company)
                                                        ->where('id_penagihan', $id)
                                                        ->get();
        return view('pages.penjualan.penagihan', $data);
    }

    public function pengiriman($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Produk penawaran')
                                                ->where('status','active')
                                                ->first();
        $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Multiple gudang')
                                                ->where('status','active')
                                                ->first();
        if(isset($data['produk_penawaran'])){
            $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)->get();
        }
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
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
            $data['penjualan'] = Penjualan::leftJoin('penjualan as penawaran', 'penawaran.id','=','penjualan.id_penawaran')
                                            ->leftJoin('penjualan as pemesanan', 'pemesanan.id','=','penjualan.id_pemesanan')
                                            ->select('penjualan.*','penawaran.no_str as no_str_penawaran','pemesanan.no_str as no_str_pemesanan')
                                            ->where('penjualan.id',$id)->first();
            if($data['penjualan']->id_penawaran){
                $data['penawaran'] = true;
            }
            if($data['penjualan']->id_pemesanan){
                $data['pemesanan'] = true;
            }
            $data['detail_penjualan'] = Detail_penjualan::with(['stok_gudang','produk_penawaran.produk'])
                                                        ->join('produk','detail_penjualan.id_produk','=','produk.id')
                                                        ->select('detail_penjualan.*','produk.unit')
                                                        ->where('detail_penjualan.id_penjualan',$id)
                                                        ->get();
            if($data['penjualan']->id_pemesanan && isset($data['produk_penawaran'])){
                $data['detail_pemesanan'] = Detail_penjualan::with(['produk_penawaran.produk'])
                                                            ->join('produk','detail_penjualan.id_produk','=','produk.id')
                                                            ->select('detail_penjualan.*','produk.unit')
                                                            ->where('detail_penjualan.id_penjualan',$data['penjualan']->id_pemesanan)
                                                            ->get();
            }
        }

        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','pengiriman')
                                                        ->get();
        $data['dokumen_penjualan'] = Dokumen_penjualan::where('id_company',Auth::user()->id_company)
                                                        ->where('id_pengiriman', $id)
                                                        ->get();
        return view('pages.penjualan.pengiriman', $data);
    }

    public function pemesanan($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Produk penawaran')
                                                ->where('status','active')
                                                ->first();
        // $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
        //                                         ->where('fitur','Multiple gudang')
        //                                         ->where('status','active')
        //                                         ->first();
        if(isset($data['produk_penawaran'])){
            $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)->get();
        }
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        // if(Auth::user()->id_gudang){
        //     $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
        //                             ->get();
        // }else{
        //     $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
        //                             ->get();
        // }
        if($id != null){
            $data['pemesanan'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            if($data['penjualan']->id_penawaran){
                $data['penawaran'] = true;
            }
            $data['detail_penjualan'] = Detail_penjualan::with('stok_gudang')->where('id_penjualan',$id)->get();
            if($data['penjualan']->id_penawaran && isset($data['produk_penawaran'])){
                $data['detail_penawaran'] = Detail_penjualan::with('produk_penawaran')
                                                            ->where('id_penjualan',$data['penjualan']->id_penawaran)->get();
            }
        }

        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','pemesanan')
                                                        ->get();
        $data['dokumen_penjualan'] = Dokumen_penjualan::where('id_company',Auth::user()->id_company)
                                                        ->where('id_pemesanan', $id)
                                                        ->get();
        return view('pages.penjualan.pemesanan', $data);
    }

    public function penawaran($id=null)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Produk penawaran')
                                                ->where('status','active')
                                                ->first();
                                                
        if(isset($data['produk_penawaran'])){
            $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)->get();
        }
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

    public function v2_penawaran($id=null)
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
        return view('v2.penjualan.penawaran', $data);
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

        // return Pdf::view('pdf.penjualan.penawaran' , $data)->format('a4')->name('penawaran_penjualan.pdf');

        $html = view('pdf.penjualan.penawaran', $data)->render();

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
        return response($mpdf->Output('penawaran_penjualan.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function cetak_pemesanan($id){
        $data['penjualan'] = Penjualan::leftJoin('kontak','penjualan.id_pelanggan','kontak.id')
                                        ->where('penjualan.id',$id)
                                        ->first();
        $data['detail_penjualan'] = Detail_penjualan::leftjoin('produk','detail_penjualan.id_produk','produk.id')
                                                    ->where('detail_penjualan.id_penjualan',$id)
                                                    ->get();

        $data['company'] = Company::leftJoin('users','company.id','users.id_company')
                                    ->where('company.id', $data['penjualan']->id_company)
                                    ->first();

        // return Pdf::view('pdf.penjualan.pemesanan' , $data)->format('a4')->name('pemesanan_penjualan.pdf');

        $html = view('pdf.penjualan.pemesanan', $data)->render();

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
        return response($mpdf->Output('pemesanan_penjualan.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function penawaran_pemesanan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Produk penawaran')
                                                ->where('status','active')
                                                ->first();
        // $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
        //                                         ->where('fitur','Multiple gudang')
        //                                         ->where('status','active')
        //                                         ->first();
        if(isset($data['produk_penawaran'])){
            $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)->get();
        }
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        // if(Auth::user()->id_gudang){
        //     $data['gudang'] = Gudang::where('id',Auth::user()->id_gudang)
        //                             ->get();
        // }else{
        //     $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
        //                             ->get();
        // }
        if($id != null){
            $data['penawaran'] = true;
            $data['penawaran_pemesanan'] = true;
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::with(['produk_penawaran.produk'])
                                                        ->where('id_penjualan',$id)
                                                        ->get();
            // dd($data['detail_penjualan']);
        }
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','pemesanan')
                                                        ->get();

                                                        // dd($data['produk_penawaran']);
        return view('pages.penjualan.pemesanan', $data);
    }

    public function penawaran_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Multiple gudang')
                                                ->where('status','active')
                                                ->first();
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
        return view('pages.penjualan.penagihan', $data);
    }

    public function pemesanan_pengiriman($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk_penawaran'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Produk penawaran')
                                                ->where('status','active')
                                                ->first();
        $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Multiple gudang')
                                                ->where('status','active')
                                                ->first();
        if(isset($data['produk_penawaran'])){
            $data['produk_penawaran'] = Produk_penawaran::where('id_company',Auth::user()->id_company)->get();
        }
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
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
            $data['pemesanan_pengiriman'] = true;
            $data['penjualan'] = Penjualan::leftJoin('penjualan as penawaran', 'penawaran.id','=','penjualan.id_penawaran')
                                            ->leftJoin('penjualan as pemesanan', 'pemesanan.id','=','penjualan.id_pemesanan')
                                            ->select('penjualan.*','penawaran.no_str as no_str_penawaran')
                                            ->where('penjualan.id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::with(['produk_penawaran.produk'])
                                                        ->join('produk','detail_penjualan.id_produk','=','produk.id')
                                                        ->select('detail_penjualan.*','produk.unit')
                                                        ->where('detail_penjualan.id_penjualan',$id)
                                                        ->get();
            $data['detail_pemesanan'] = Detail_penjualan::with(['produk_penawaran.produk'])
                                                        ->join('produk','detail_penjualan.id_produk','=','produk.id')
                                                        ->select('detail_penjualan.*','produk.unit')
                                                        ->where('detail_penjualan.id_penjualan',$id)
                                                        ->get();
        }
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','pengiriman')
                                                        ->get();
                                                        
        return view('pages.penjualan.pengiriman', $data);
    }

    public function pemesanan_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                ->where('fitur','Multiple gudang')
                                                ->where('status','active')
                                                ->first();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
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
            $data['penjualan'] = Penjualan::where('id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        return view('pages.penjualan.penagihan', $data);
    }

    public function pengiriman_penagihan($id)
    {
        $data['sidebar'] = 'penjualan';
        $data['produk'] = Produk::where('id_company',Auth::user()->id_company)->get();
        // $data['multiple_gudang'] = Pengaturan_produk::where('id_company',Auth::user()->id_company)
        //                                         ->where('fitur','Multiple gudang')
        //                                         ->where('status','active')
        //                                         ->first();
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        $data['gudang'] = Gudang::where('id_company',Auth::user()->id_company)
                                    ->get();
        if($id != null){
            $data['pengiriman'] = true;
            $data['pengiriman_penagihan'] = true;
            $data['penjualan'] = Penjualan::leftJoin('penjualan as penawaran', 'penawaran.id','=','penjualan.id_penawaran')
                                            ->leftJoin('penjualan as pemesanan', 'pemesanan.id','=','penjualan.id_pemesanan')
                                            ->select('penjualan.*','penawaran.no_str as no_str_penawaran','pemesanan.no_str as no_str_pemesanan')
                                            ->where('penjualan.id',$id)->first();
            $data['detail_penjualan'] = Detail_penjualan::where('id_penjualan',$id)->get();
        }
        $data['pengaturan_dokumen'] = Pengaturan_dokumen::where('id_company',Auth::user()->id_company)
                                                        ->where('status_penjualan','penagihan')
                                                        ->get();

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

        $multiple_gudang = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                    ->where('fitur','Multiple gudang')
                                                    ->where('status','active')
                                                    ->first();
        $gudang = Gudang::where('id_company',Auth::user()->id_company)->get();


        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $kuantitas = 0;
            $produk = Produk::find($request->input('produk')[$i]);
            if($produk->batas_stok_minimum){
                if($multiple_gudang && $gudang->count() > 0){
                    foreach($gudang as $v){
                        $kuantitas += $request->input('kuantitas_'.$v->id)[$i];
                    }
                    $produk->stok = $produk->stok - $kuantitas;
                }else{
                    $produk->stok = $produk->stok - $request->input('kuantitas')[$i];
                }
                $produk->save();
            }
        }

        DB::commit();

        return redirect('penjualan/detail/'.$penjualan->id);
    }

    public function update_penagihan(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $jurnal = Jurnal::find($penjualan->id_jurnal);
        $jurnal->penjualan($request, $id);
        $penjualan->ubah($request, 'penagihan');
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
        $penjualan->ubah($request, 'penawaran');
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
        $penjualan->ubah($request, 'pemesanan');
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

    public function update_pengiriman(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        $jurnal = Jurnal::find($penjualan->id_jurnal);
        $jurnal->pengiriman_penjualan($request, $id);
        $penjualan->ubah($request, 'pengiriman');
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

    public function update_status_pengiriman(){

        $status_pengiriman = new Status_pengiriman();
        $status_pengiriman->id_company = Auth::user()->id_company;
        $status_pengiriman->id_pengiriman_penjualan = $_POST['id_pengiriman_penjualan'];
        $status_pengiriman->id_status_pengiriman = $_POST['status_pengiriman'];
        $status_pengiriman->nama_status_pengiriman = Pengaturan_status_pengiriman::find($_POST['status_pengiriman'])->nama;
        if(Gudang::find($_POST['gudang'])){
            $status_pengiriman->id_gudang = $_POST['gudang'];
            $status_pengiriman->nama_gudang = Gudang::find($_POST['gudang'])->nama;
        }else{
            $status_pengiriman->penerima = $_POST['gudang'];
        }
        
        $status_pengiriman->save();

        return redirect('penjualan/detail/'.$_POST['id_pengiriman_penjualan']);
    }

    public function hapus($id){
        DB::beginTransaction();
        $penjualan = Penjualan::find($id);
        if($penjualan->jenis == 'penawaran'){
            Detail_penjualan::where('id_penjualan',$id)->delete();
            $penjualan->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Transaksi_produk_penawaran::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();
            DB::commit();
            return redirect('penjualan');
        }else if($penjualan->jenis == 'pemesanan'){
            Detail_penjualan::where('id_penjualan',$id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Stok_gudang::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();
            Dokumen_penjualan::where('id_pemesanan',$id)->delete();
            if($penjualan->id_penawaran){
                $penawaran = Penjualan::find($penjualan->id_penawaran);
                if($penawaran){
                    $penawaran->status = 'open';
                    $penawaran->id_pemesanan = null;
                    $penawaran->save();
                    $penjualan->delete();
                    DB::commit();
                    return redirect('penjualan/detail/'.$penawaran->id);
                }else{
                    return redirect('penjualan');
                }
            }else{
                $penjualan->delete();
                DB::commit();
                return redirect('penjualan');
            }
        }else if($penjualan->jenis == 'pengiriman'){
            Transaksi_produk::where('id_transaksi',$id)->delete();
            Stok_gudang::where('id_transaksi',$id)->delete();
            Log::where('id_transaksi',$id)->delete();
            Dokumen_penjualan::where('id_pengiriman',$id)->delete();
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$penjualan->id_jurnal)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            Detail_jurnal::where('id_jurnal',$penjualan->id_jurnal)->delete();
            Jurnal::find($penjualan->id_jurnal)->delete();
            $detail_penjualan = Detail_penjualan::where('id_penjualan',$penjualan->id)->get();
            foreach($detail_penjualan as $v){
                $produk = Produk::find($v->id_produk);
                $produk->stok = (int) $produk->stok + (int) $v->kuantitas;
                $produk->save();
            }
            Detail_penjualan::where('id_penjualan',$penjualan->id)->delete();
            if($penjualan->id_pemesanan){
                $pemesanan = Penjualan::find($penjualan->id_pemesanan);
                if($pemesanan){
                    $pemesanan->id_pengiriman = null;
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
            }else{
                return redirect('penjualan');
            }
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

            if(!$penjualan->id_pengiriman){
                $detail_penjualan = Detail_penjualan::where('id_penjualan',$penjualan->id)->get();
                foreach($detail_penjualan as $v){
                    $produk = Produk::find($v->id_produk);
                    $produk->stok = $produk->stok + $v->kuantitas;
                    $produk->save();
                }
            }

            Detail_penjualan::where('id_penjualan',$penjualan->id)->delete();
            Transaksi_produk::where('id_transaksi',$id)->delete();

            Stok_gudang::where('id_transaksi',$id)->delete();
            if(isset($penjualan->id_pengiriman)){
                $pengiriman = Penjualan::find($penjualan->id_pengiriman);
                if($pengiriman){
                    $pengiriman->status = 'open';
                    $pengiriman->save();
                    $penjualan->delete();
                    DB::commit();
                    return redirect('penjualan/detail/'.$pengiriman->id);
                }else{
                    $penjualan->delete();
                    DB::commit();
                    return redirect('penjualan');
                }
                
            }else if(isset($penjualan->id_pemesanan)){
                $pemesanan = Penjualan::find($penjualan->id_pemesanan);
                if($pemesanan){
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
                
            }else{
                $penjualan->delete();
                DB::commit();
                return redirect('penjualan');
            }
        }
    }

    public function cetak_surat_jalan($id){
        $data['company'] = Company::where('id',Auth::user()->id_company)->first();
        $data['penjualan'] = Penjualan::with([
                                                'detail_penjualan.produk'
                                            ])
                                        ->leftJoin('kontak','penjualan.id_pelanggan','=','kontak.id')
                                        ->leftJoin('penjualan as penawaran', 'penjualan.id_penawaran', '=', 'penawaran.id')
                                        ->leftJoin('penjualan as pemesanan', 'penjualan.id_pemesanan', '=', 'pemesanan.id')
                                        ->select('penjualan.*','kontak.nama as nama_pelanggan','kontak.alamat','pemesanan.no_str as no_str_pemesanan')
                                        ->where('penjualan.id',$id)
                                        ->where('penjualan.id_company',Auth::user()->id_company)
                                        ->first();
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

    public function insert_selesai(Request $request, $id)
    {
        DB::beginTransaction();
        $penjualan = new Penjualan;
        $penjualan->selesai($id);
        DB::commit();

        return redirect('penjualan/detail/'.$id);
    }
    public function upload_dokumen(Request $request, $id)
    {
        for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
            if($request->file($_POST['id_dokumen'][$i])){
                $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                $uniqueFileName = time() . '.' . $fileName;

                $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                $dokumen_penjualan = new Dokumen_penjualan();
                $dokumen_penjualan->id_company = Auth::user()->id_company;
                $dokumen_penjualan->id_penjualan = $id;
                $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                $dokumen_penjualan->nama = $uniqueFileName;
                $dokumen_penjualan->save();
            }
        }
        

        return back()->with('success', 'File uploaded successfully!');
    }
}
