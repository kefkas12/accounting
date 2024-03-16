<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun_company;
use App\Models\Jurnal;
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
                                ->select('jurnal.*')
                                ->where('id_company',Auth::user()->id_company)
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
        $kategori_pendapatan_lainnya = array(14);
        $kategori_beban = array(16, 17);

        $pendapatan_lainnya = 0;
        $beban = 0;

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
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)){
                $pendapatan_lainnya += $v->saldo;
            }
            if (in_array($v->id_kategori, $kategori_beban)){
                $beban += $v->saldo;
            }
        }

        $modal[] = 
        [
            'id_akun' => '',
            'nomor' => '',
            'nama' => 'Pendapatan Periode ini',
            'saldo' => -1*$pendapatan_lainnya - $beban,
        ];

        $neraca = [
            'aset' => [
                'aset lancar' => $aset_lancar,
                'aset tetap' => $aset_tetap,
            ],
            'Liabilitas dan modal' => [
                'Liabilitas jangka pendek' => $liabilitas_jangka_pendek,
                'Modal pemilik' => $modal,
            ]
        ];

        $data['neraca'] = json_encode($neraca);
                                
        return view('pages.laporan.neraca',$data);
    }
}
