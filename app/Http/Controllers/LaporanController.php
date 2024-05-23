<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Akun_company;
use App\Models\Detail_jurnal;
use App\Models\Jurnal;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use PDO;

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

    public function jurnal()
    {
        
        $data['sidebar'] = 'laporan';
        $select = [
                    'jurnal.*',
                    'penjualan.id as id_penjualan',
                    'pembelian.id as id_pembelian',
                    'pembayaran_pembelian.id as id_pembayaran_pembelian',
                    'pembayaran_penjualan.id as id_pembayaran_penjualan'];
        if(isset($_GET['tanggal_mulai'])){
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
            ->leftJoin('penjualan', 'jurnal.id', 'penjualan.id_jurnal')
            ->leftJoin('pembelian', 'jurnal.id', 'pembelian.id_jurnal')
            ->leftJoin('pembayaran_pembelian', 'jurnal.id', 'pembayaran_pembelian.id_jurnal')
            ->leftJoin('pembayaran_penjualan', 'jurnal.id', 'pembayaran_penjualan.id_jurnal')
            ->select($select)
            ->where('jurnal.id_company', Auth::user()->id_company)
            ->whereBetween('jurnal.tanggal_transaksi',[$_GET['tanggal_mulai'],$_GET['tanggal_selesai']])
            ->orderBy('jurnal.id', 'DESC')
            ->get();
        }else{
            $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
            ->leftJoin('penjualan', 'jurnal.id', 'penjualan.id_jurnal')
            ->leftJoin('pembelian', 'jurnal.id', 'pembelian.id_jurnal')
            ->leftJoin('pembayaran_pembelian', 'jurnal.id', 'pembayaran_pembelian.id_jurnal')
            ->leftJoin('pembayaran_penjualan', 'jurnal.id', 'pembayaran_penjualan.id_jurnal')
            ->select($select)
            ->where('jurnal.id_company', Auth::user()->id_company)
            ->orderBy('jurnal.id', 'DESC')
            ->get();
        }

        
        return view('pages.jurnal.index', $data);
    }

    public function neraca()
    {
        $data['sidebar'] = 'laporan';

        $akun = Akun_company::join('akun', 'akun_company.id_akun', '=', 'akun.id')
            ->where('akun_company.id_company', Auth::user()->id_company)
            ->get();
        $kategori_aset_lancar = array(1, 2, 3, 4);
        $kategori_aset_tetap = array(5);
        $kategori_depresiasi_dan_amortisasi = array(7);
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

        foreach ($akun as $v) {
            if (in_array($v->id_kategori, $kategori_aset_lancar)) {
                $aset_lancar[] =
                    [
                        'id_akun' => $v->id_akun,
                        'nomor' => $v->nomor,
                        'nama' => $v->nama,
                        'saldo' => $v->saldo,
                    ];
            }
            if (in_array($v->id_kategori, $kategori_aset_tetap)) {
                $aset_tetap[] =
                    [
                        'id_akun' => $v->id_akun,
                        'nomor' => $v->nomor,
                        'nama' => $v->nama,
                        'saldo' => $v->saldo,
                    ];
            }
            if (in_array($v->id_kategori, $kategori_depresiasi_dan_amortisasi)) {
                $depresiasi_dan_amortisasi[] =
                    [
                        'id_akun' => $v->id_akun,
                        'nomor' => $v->nomor,
                        'nama' => $v->nama,
                        'saldo' => $v->saldo,
                    ];
            }
            if (in_array($v->id_kategori, $kategori_liabilitas_jangka_pendek)) {
                $liabilitas_jangka_pendek[] =
                    [
                        'id_akun' => $v->id_akun,
                        'nomor' => $v->nomor,
                        'nama' => $v->nama,
                        'saldo' => -1 * $v->saldo,
                    ];
            }
            if (in_array($v->id_kategori, $kategori_modal)) {
                $modal[] =
                    [
                        'id_akun' => $v->id_akun,
                        'nomor' => $v->nomor,
                        'nama' => $v->nama,
                        'saldo' => -1 * $v->saldo,
                    ];
            }
            if (in_array($v->id_kategori, $kategori_pendapatan)) {
                $pendapatan += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)) {
                $pendapatan_lainnya += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_beban)) {
                $beban += $v->saldo * $v->pengali;
            }
            if (in_array($v->id_kategori, $kategori_harga_pokok_pendapatan)) {
                $harga_pokok_pendapatan += $v->saldo * $v->pengali;
            }
        }

        $modal[] =
            [
                'id_akun' => '',
                'nomor' => '',
                'nama' => 'Pendapatan sampai Tahun lalu',
                'saldo' => 0,
            ];
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
                'Depresiasi dan amortisasi' => $depresiasi_dan_amortisasi,
            ],
            'Liabilitas dan modal' => [
                'Liabilitas jangka pendek' => $liabilitas_jangka_pendek,
                'Modal pemilik' => $modal,
            ]
        ];

        $data['neraca'] = json_encode($neraca);

        return view('pages.laporan.neraca', $data);
    }

    public function buku_besar()
    {
        $data['sidebar'] = 'laporan';

        // $data['buku_besar'] = Akun_company::with('detail_jurnal.jurnal')
        //     ->join('akun', 'akun_company.id_akun', '=', 'akun.id')
        //     ->select('akun.*', 'akun_company.saldo as saldo_akun')
        //     ->where('akun_company.id_company', Auth::user()->id_company)
        //     ->get();
        

        $akun = Detail_jurnal::join('jurnal', 'detail_jurnal.id_jurnal', '=', 'jurnal.id')
            ->join('akun', 'detail_jurnal.id_akun', '=', 'akun.id')
            ->select('detail_jurnal.*', 'akun.id_kategori', 'akun.pengali', 'akun.nama', 'akun.nomor', 'jurnal.tanggal_transaksi', 'jurnal.kategori', 'jurnal.no', 'jurnal.no_str')
            ->where('detail_jurnal.id_company', Auth::user()->id_company)
            ->get();
        $saldo = null;
        foreach ($akun as $v) {
            if (!isset($buku_besar[$v->id_akun])) {
                $saldo[$v->id_akun] = ($v->debit - $v->kredit);
                
                $buku_besar[$v->id_akun]['nama'] = $v->nama;
                $buku_besar[$v->id_akun]['nomor'] = $v->nomor;
                $buku_besar[$v->id_akun]['detail'] = [];
                $detail = 
                    [
                        'tanggal_transaksi' => $v->tanggal_transaksi,
                        'kategori' => $v->kategori,
                        'no' => $v->no,
                        'no_str' => $v->no_str,
                        'debit' => $v->debit,
                        'kredit' => $v->kredit,
                        'saldo' => $saldo[$v->id_akun],
                    ];
                array_push($buku_besar[$v->id_akun]['detail'], $detail);
                
            }else{
                $saldo[$v->id_akun] += ($v->debit - $v->kredit);
                $detail = 
                    [
                        'tanggal_transaksi' => $v->tanggal_transaksi,
                        'kategori' => $v->kategori,
                        'no' => $v->no,
                        'no_str' => $v->no_str,
                        'debit' => $v->debit,
                        'kredit' => $v->kredit,
                        'saldo' => $saldo[$v->id_akun],
                    ];
                array_push($buku_besar[$v->id_akun]['detail'], $detail);
            }
        }

        $data['buku_besar'] = json_encode($buku_besar);

        return view('pages.laporan.buku_besar', $data);
    }

    public function neraca_new()
    {
        $data['sidebar'] = 'laporan';

        $akun = Detail_jurnal::join('jurnal', 'detail_jurnal.id_jurnal', '=', 'jurnal.id')
            ->join('akun', 'detail_jurnal.id_akun', '=', 'akun.id')
            ->select('detail_jurnal.*', 'akun.id_kategori', 'akun.pengali', 'akun.nama', 'akun.nomor', 'jurnal.tanggal_transaksi')
            ->where('detail_jurnal.id_company', Auth::user()->id_company)
            ->where('jurnal.status','!=','draf')
            ->whereBetween('jurnal.tanggal_transaksi',['2023-01-01','2024-12-31'])
            ->get();

        $kategori_aset_lancar = array(1, 2, 3, 4);
        $kategori_aset_tetap = array(5);
        $kategori_depresiasi_dan_amortisasi = array(7);
        $kategori_liabilitas_jangka_pendek = array(8, 10);
        $kategori_modal = array(12);
        $kategori_pendapatan = array(13);
        $kategori_pendapatan_lainnya = array(14);
        $kategori_beban = array(16, 17);
        $kategori_harga_pokok_pendapatan = array(15);

        $aset_lancar = [];
        $aset_tetap = [];
        $depresiasi_dan_amortisasi = [];
        $liabilitas_jangka_pendek = [];
        $modal = [];
        $pendapatan = [];
        $pendapatan_lainnya = [];
        $harga_pokok_pendapatan = [];
        $beban = [];

        foreach ($akun as $v) {
            if (in_array($v->id_kategori, $kategori_aset_lancar)) {
                if (isset($aset_lancar[$v->id_akun])) {
                    $aset_lancar[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $aset_lancar[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_aset_tetap)) {
                if (isset($aset_tetap[$v->id_akun])) {
                    $aset_tetap[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $aset_tetap[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_depresiasi_dan_amortisasi)) {
                if (isset($depresiasi_dan_amortisasi[$v->id_akun])) {
                    $depresiasi_dan_amortisasi[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $depresiasi_dan_amortisasi[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_liabilitas_jangka_pendek)) {
                if (isset($liabilitas_jangka_pendek[$v->id_akun])) {
                    $liabilitas_jangka_pendek[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $liabilitas_jangka_pendek[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_modal)) {
                if (isset($modal[$v->id_akun])) {
                    $modal[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $modal[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            //tes
            // $pendapatan['periode_ini'] = 0;
            // $pendapatan_lainnya['periode_ini'] = 0;
            // $harga_pokok_pendapatan['periode_ini'] = 0;
            // $beban['periode_ini'] = 0;
            // $harga_pokok_pendapatan['periode_ini'] = 0;
            if (in_array($v->id_kategori, $kategori_pendapatan)) {
                if (strtotime($v->tanggal_transaksi) > strtotime(date("Y") . '-01-01')) {
                    if (isset($pendapatan['periode_ini'])) {
                        $pendapatan['periode_ini'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $pendapatan['periode_ini'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }else{
                    if (isset($pendapatan['tahun_lalu'])) {
                        $pendapatan['tahun_lalu'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $pendapatan['tahun_lalu'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }
            }
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)) {
                if (strtotime($v->tanggal_transaksi) > strtotime(date("Y") . '-01-01')) {
                    if (isset($pendapatan_lainnya['periode_ini'])) {
                        $pendapatan_lainnya['periode_ini'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $pendapatan_lainnya['periode_ini'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }else{
                    if (isset($pendapatan_lainnya['tahun_lalu'])) {
                        $pendapatan_lainnya['tahun_lalu'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $pendapatan_lainnya['tahun_lalu'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }
            }
            if (in_array($v->id_kategori, $kategori_harga_pokok_pendapatan)) {
                if (strtotime($v->tanggal_transaksi) > strtotime(date("Y") . '-01-01')) {
                    if (isset($harga_pokok_pendapatan['periode_ini'])) {
                        $harga_pokok_pendapatan['periode_ini'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $harga_pokok_pendapatan['periode_ini'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }else{
                    if (isset($harga_pokok_pendapatan['tahun_lalu'])) {
                        $harga_pokok_pendapatan['tahun_lalu'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $harga_pokok_pendapatan['tahun_lalu'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }
            }
            if (in_array($v->id_kategori, $kategori_beban)) {
                if (strtotime($v->tanggal_transaksi) > strtotime(date("Y") . '-01-01')) {
                    if (isset($beban['periode_ini'])) {
                        $beban['periode_ini'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $beban['periode_ini'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }else{
                    if (isset($beban['tahun_lalu'])) {
                        $beban['tahun_lalu'] += ($v->debit - $v->kredit) * $v->pengali;
                    } else {
                        $beban['tahun_lalu'] = ($v->debit - $v->kredit) * $v->pengali;
                    }
                }
            }
        }
        $modal[] =
        [
            'id_akun' => '',
            'nomor' => '',
            'nama' => 'Pendapatan sampai Tahun lalu',
            'saldo' => (isset($pendapatan['tahun_lalu'])?$pendapatan['tahun_lalu']:0) + (isset($pendapatan_lainnya['tahun_lalu'])?$pendapatan_lainnya['tahun_lalu']:0) + (isset($beban['tahun_lalu'])?$beban['tahun_lalu']:0) - (isset($harga_pokok_pendapatan['tahun_lalu'])?$harga_pokok_pendapatan['tahun_lalu']:0),
        ];
        
        $modal[] =
            [
                'id_akun' => '',
                'nomor' => '',
                'nama' => 'Pendapatan Periode ini',
                'saldo' => (isset($pendapatan['periode_ini'])?$pendapatan['periode_ini']:0) + (isset($pendapatan_lainnya['periode_ini'])?$pendapatan_lainnya['periode_ini'] : 0) + (isset($beban['periode_ini'])?$beban['periode_ini']:0) - (isset($harga_pokok_pendapatan['periode_ini'])?$harga_pokok_pendapatan['periode_ini']:0),
            ];

        $neraca = [
            'Aset' => [
                'Aset lancar' => $aset_lancar,
                'Aset tetap' => $aset_tetap,
                'Depresiasi dan amortisasi' => $depresiasi_dan_amortisasi,
            ],
            'Liabilitas dan modal' => [
                'Liabilitas jangka pendek' => $liabilitas_jangka_pendek,
                'Modal pemilik' => $modal,
            ]
        ];

        $data['neraca'] = json_encode($neraca);
        // dd($data);

        return view('pages.laporan.neraca', $data);
    }

    public function laba_rugi()
    {
        $data['sidebar'] = 'laporan';

        $akun = Akun_company::join('akun', 'akun_company.id_akun', '=', 'akun.id')
            ->where('akun_company.id_company', Auth::user()->id_company)
            ->get();

        $akun = Detail_jurnal::join('jurnal', 'detail_jurnal.id_jurnal', '=', 'jurnal.id')
            ->join('akun', 'detail_jurnal.id_akun', '=', 'akun.id')
            ->select('detail_jurnal.*', 'akun.id_kategori', 'akun.pengali', 'akun.nama', 'akun.nomor', 'jurnal.tanggal_transaksi')
            ->where('detail_jurnal.id_company', Auth::user()->id_company)
            ->where('jurnal.tanggal_transaksi', '>=', '2024-01-01')
            ->get();

        $kategori_pendapatan = array(13);
        $kategori_pendapatan_lainnya = array(14);
        $kategori_harga_pokok_pendapatan = array(15);
        $kategori_beban = array(16, 17);
        $pendapatan = [];
        $pendapatan_lainnya = [];
        $harga_pokok_pendapatan = [];
        $beban = [];

        foreach ($akun as $v) {
            if (in_array($v->id_kategori, $kategori_pendapatan)) {
                if (isset($pendapatan[$v->id_akun])) {
                    $pendapatan[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $pendapatan[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_pendapatan_lainnya)) {
                if (isset($pendapatan_lainnya[$v->id_akun])) {
                    $pendapatan_lainnya[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $pendapatan_lainnya[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_harga_pokok_pendapatan)) {
                if (isset($harga_pokok_pendapatan[$v->id_akun])) {
                    $harga_pokok_pendapatan[$v->id_akun]['saldo'] += ($v->debit - $v->kredit) * $v->pengali;
                } else {
                    $harga_pokok_pendapatan[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit) * $v->pengali,
                        ];
                }
            }
            if (in_array($v->id_kategori, $kategori_beban)) {
                if (isset($beban[$v->id_akun])) {
                    $beban[$v->id_akun]['saldo'] += ($v->debit - $v->kredit);
                } else {
                    $beban[$v->id_akun] =
                        [
                            'id_akun' => $v->id_akun,
                            'nomor' => $v->nomor,
                            'nama' => $v->nama,
                            'saldo' => ($v->debit - $v->kredit),
                        ];
                }
            }
        }


        $laba_rugi = [
            'Revenue' => [
                'Pendapatan' => $pendapatan
            ],
            'Cost of sales' => [
                'Pembelian' => $harga_pokok_pendapatan
            ],
            'Operational expense' => [
                'Biaya Operasional' => $beban
            ],
            'Other income' => [
                'Other income' => $pendapatan_lainnya,
                'Other expense' => ''
            ]
        ];

        $data['laba_rugi'] = json_encode($laba_rugi);

        return view('pages.laporan.laba_rugi', $data);
    }

    public function penjualan($jenis)
    {
        $data['sidebar'] = 'laporan';

        $data['penjualan'] = Penjualan::join('kontak', 'penjualan.id_pelanggan', '=', 'kontak.id')
            ->select('penjualan.*', 'kontak.nama')
            ->where('penjualan.id_company', Auth::user()->id_company)
            ->where('penjualan.jenis', $jenis)
            ->get();

        return view('pages.laporan.penjualan', $data);
    }

    public function pembelian($jenis)
    {
        $data['sidebar'] = 'laporan';

        $data['pembelian'] = Pembelian::join('kontak', 'pembelian.id_supplier', '=', 'kontak.id')
            ->select('pembelian.*', 'kontak.nama')
            ->where('pembelian.id_company', Auth::user()->id_company)
            ->where('pembelian.jenis', $jenis)
            ->get();

        return view('pages.laporan.pembelian', $data);
    }
}
