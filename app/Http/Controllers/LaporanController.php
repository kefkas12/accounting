<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Jurnal;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'laporan';
        return view('pages.laporan.index', $data);
    }

    public function jurnal(){
        $data['sidebar'] = 'laporan';
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                ->leftJoin('penjualan','jurnal.id','penjualan.id_jurnal')
                                ->select('jurnal.*','penjualan.id as id_penjualan')
                                ->where('jurnal.id_company',Auth::user()->id_company)
                                ->orderBy('jurnal.id','DESC')
                                ->get();
        return view('pages.jurnal.index',$data);
    }

    public function neraca(){
        $data['sidebar'] = 'laporan';

        $akun = Akun_company::join('akun','akun_company.id_akun','=','akun.id')
                            ->where('akun_company.id_company',Auth::user()->id_company)
                            ->get();
        $kategori_aset_lancar = array(1, 2, 3, 4);
        $kategori_aset_tetap = array(5);
        $kategori_liabilitas_jangka_pendek = array(8, 10);
        $kategori_modal = array(12);
        $kategori_pendapatan = array(13);
        $kategori_pendapatan_lainnya = array(14);
        $kategori_beban = array(16, 17);
        $kategori_harga_pokok_pendapatan = array(15);

        $pendapatan = 0;
        $pendapatan_lainnya = 0;
        $beban = 0;
        $harga_pokok_pendapatan = 0;

        foreach($akun as $v){
            if (in_array($v->id_kategori, $kategori_aset_lancar)){
                $aset_lancar[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => $v->saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_aset_tetap)){
                $aset_tetap[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => $v->saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_liabilitas_jangka_pendek)){
                $liabilitas_jangka_pendek[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => -1*$v->saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_modal)){
                $modal[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => -1*$v->saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_pendapatan)){
                $pendapatan += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)){
                $pendapatan_lainnya += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_beban)){
                $beban += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_harga_pokok_pendapatan)){
                $harga_pokok_pendapatan += $v->saldo * $v->pengali;
            }
        }

        $modal[] = 
        [
            'id_akun' => '',
            'nomor' => '',
            'nama' => 'Pendapatan Periode ini',
            'saldo' => $pendapatan + $pendapatan_lainnya + $beban - $harga_pokok_pendapatan,
        ];

        $neraca = [
            'Aset' => [
                'Aset lancar' => $aset_lancar,
                'Aset tetap' => $aset_tetap,
            ],
            'Liabilitas dan modal' => [
                'Liabilitas jangka pendek' => $liabilitas_jangka_pendek,
                'Modal pemilik' => $modal,
            ]
        ];

        $data['neraca'] = json_encode($neraca);
                                
        return view('pages.laporan.neraca',$data);
    }

    public function buku_besar(){
        $data['sidebar'] = 'laporan';
        $data['buku_besar'] = Akun_company::with('detail_jurnal.jurnal')
                                    ->join('akun','akun_company.id_akun','=','akun.id')
                                    ->select('akun.*','akun_company.saldo as saldo_akun')
                                    ->where('akun_company.id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.laporan.buku_besar',$data);
    }

    public function laba_rugi(){
        $data['sidebar'] = 'laporan';

        $akun = Akun_company::join('akun','akun_company.id_akun','=','akun.id')
                            ->where('akun_company.id_company',Auth::user()->id_company)
                            ->get();
        $kategori_pendapatan = array(13);
        $kategori_pendapatan_lainnya = array(14);
        $kategori_beban = array(16, 17);

        foreach($akun as $v){
            if (in_array($v->id_kategori, $kategori_pendapatan)){
                $saldo = $v->saldo * $v->pengali;
                $pendapatan[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => $saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_beban)){
                $beban[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => $v->saldo,
                ];
            }
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)){
                $pendapatan_lainnya[] = 
                [
                    'id_akun' => $v->id_akun,
                    'nomor' => $v->nomor,
                    'nama' => $v->nama,
                    'saldo' => -1*$v->saldo,
                ];
            }
        }


        $laba_rugi = [
            'Revenue' => [
                'Pendapatan' => $pendapatan
            ],
            'Cost of sales' => [],
            'Operational expense' => [
                'Biaya Operasional' => $beban
            ],
            'Other income' => [
                'Other income' => $pendapatan_lainnya,
                'Other expense' => ''
            ]
        ];

        $data['laba_rugi'] = json_encode($laba_rugi);
                                
        return view('pages.laporan.laba_rugi',$data);
    }

    public function penjualan($jenis){
        $data['sidebar'] = 'laporan';

        $data['penjualan'] = Penjualan::join('kontak','penjualan.id_pelanggan','=','kontak.id')
                            ->select('penjualan.*', 'kontak.nama')
                            ->where('penjualan.id_company',Auth::user()->id_company)
                            ->where('penjualan.jenis', $jenis)
                            ->get();
                                
        return view('pages.laporan.penjualan',$data);
    }

    public function pembelian($jenis){
        $data['sidebar'] = 'laporan';

        $data['pembelian'] = Pembelian::join('kontak','pembelian.id_supplier','=','kontak.id')
                            ->select('pembelian.*', 'kontak.nama')
                            ->where('pembelian.id_company',Auth::user()->id_company)
                            ->where('pembelian.jenis', $jenis)
                            ->get();
                                
        return view('pages.laporan.pembelian',$data);
    }
}
