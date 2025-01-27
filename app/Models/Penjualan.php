<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';

    protected $fillable = [
        'id_penagihan', 
        'id_jurnal',
        'tanggal_transaksi',
        'no',
        'no_str',
        'tanggal_jatuh_tempo',
        'status',
        'jenis'
    ];

    function no($jenis)
    {
        $no = Penjualan::select('no')
                        ->where('jenis',$jenis)
                        ->where('id_company',Auth::user()->id_company)
                        ->orderBy('id', 'DESC')
                        ->first();
        if ($no) {
            $no = $no->no;
            $no++;
        } else {
            $no = 10001;
        }
        return $no;
    }

    public function detail_penjualan(): HasMany
    {
        return $this->hasMany(Detail_penjualan::class, 'id_penjualan');
    }

    public function dokumen_penjualan(): HasMany
    {
        return $this->hasMany(Dokumen_penjualan::class, 'id_penjualan');
    }

    public function detail_pembayaran_penjualan(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_penjualan::class, 'id_penjualan');
    }

    public function kontak(): BelongsTo
    {
        return $this->belongsTo(Kontak::class, 'id_pelanggan');
    }

    public function penawaran()
    {
        return $this->belongsTo(Penjualan::class, 'id_penawaran');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Penjualan::class, 'id_pemesanan');
    }

    public function insert($request, $idJurnal, $jenis, $id_jenis=null, $is_requester=null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = date('Y-m-d',strtotime($request->input('tanggal_transaksi')));
        $this->no = $this->no($jenis);
        if($jenis == 'penagihan'){
            $this->no_str = 'Sales Invoice #' . $this->no;
            $tipe = 'Penagihan Penjualan #' . $this->no;
        }else if($jenis == 'pengiriman'){
            $this->no_str = 'Sales Delivery #' . $this->no;
            $tipe = 'Pengiriman Penjualan #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Sales Quote #' . $this->no;
            $tipe = 'Penawaran Penjualan #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Sales Order #' . $this->no;
            $tipe = 'Pemesanan Penjualan #' . $this->no;
        }
        
        $this->id_pelanggan = $request->input('pelanggan');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->status = $is_requester ? 'draf' : 'open';
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_sisa_tagihan');
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->jenis = $jenis;
        $this->id_jurnal = $idJurnal;
        $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
        $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
        $this->info_pengiriman = $request->input('info_pengiriman') ? $request->input('info_pengiriman') : null;

        if($request->input('info_pengiriman') && $request->input('info_pengiriman') == 'on'){
            $this->tanggal_pengiriman = $request->input('tanggal_pengiriman') ? $request->input('tanggal_pengiriman') : null;
            $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
            $this->sama_dengan_penagihan = $request->input('sama_dengan_penagihan') ? $request->input('sama_dengan_penagihan') : null;
        }

        if($jenis == 'penawaran'){
            $this->no_rfq = $request->input('no_rfq');
            $this->pic = $request->input('pic');
        }elseif($jenis == 'pemesanan'){
            if($id_jenis != null){
                $this->id_penawaran = $id_jenis;
            }
            $this->no_rfq = Penjualan::find($id_jenis) ? Penjualan::find($id_jenis)->no_rfq : null;
        }elseif(($jenis == 'penagihan' || $jenis == 'pengiriman') && $id_jenis != null){
            $this->no_rfq = Penjualan::find($id_jenis)->no_rfq ? Penjualan::find($id_jenis)->no_rfq : null;
            $this->id_penawaran = Penjualan::find($id_jenis)->id_penawaran ? Penjualan::find($id_jenis)->id_penawaran : null;
            if(Penjualan::find($id_jenis)->jenis == 'pengiriman'){
                $this->id_pemesanan = Penjualan::find($id_jenis)->id_pemesanan;
                $this->id_pengiriman = $id_jenis;
            }else{
                $this->id_pemesanan = $id_jenis;
            }
        }
        if($request->input('gudang')){
            $gudang = Gudang::find((int)$request->input('gudang'));
            $this->id_gudang = $gudang->id;
            $this->nama_gudang = $gudang->nama;
        }
        $this->ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : null;
        $this->pesan = $request->input('pesan') ? $request->input('pesan') : null;
        $this->memo = $request->input('memo') ? $request->input('memo') : null;
        $this->save();

        if(($jenis == 'pemesanan') && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pemesanan = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'pengiriman' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pengiriman = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'penagihan' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->status = 'closed';
            $penjualan->save();
        }

        $this->insertDetailPenjualan($request, $tipe, $jenis,$this->id_gudang);


        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'penjualan';
        $log->save();

    }

    protected function insertDetailPenjualan(Request $request, $tipe, $jenis, $id_gudang)
    {
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;

            $detail_penjualan = new Detail_penjualan;
            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            if($request->input('produk')){
                $detail_penjualan->id_produk = $request->input('produk')[$i];
            }
            if($request->input('produk_penawaran')){
                $detail_penjualan->id_produk_penawaran = $request->input('produk_penawaran')[$i];
            }
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];
            $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            $detail_penjualan->harga_satuan = $harga_satuan;
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i] ? $request->input('diskon_per_baris')[$i] : 0;
            $detail_penjualan->nilai_diskon_per_baris = $request->input('nilai_diskon_per_baris')[$i] ? $request->input('nilai_diskon_per_baris')[$i] : 0;
            $detail_penjualan->jumlah = $jumlah;
            $detail_penjualan->pajak = $jumlah * $pajak / 100;
            $detail_penjualan->save();

            $jenis_transaksi = "";

            if($jenis == 'penawaran'){
                $produk_penawaran = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                        ->where('fitur','Produk penawaran')
                                                        ->where('status','active')
                                                        ->first();
                if(isset($produk_penawaran)){
                    $transaksi_produk_penawaran = new Transaksi_produk_penawaran;
                    $transaksi_produk_penawaran->id_company = Auth::user()->id_company;
                    $transaksi_produk_penawaran->id_transaksi = $this->id;
                    $transaksi_produk_penawaran->id_produk = $request->input('produk')[$i];
                    $transaksi_produk_penawaran->tanggal = $request->input('tanggal_transaksi');
                    $transaksi_produk_penawaran->tipe = $tipe;
                    $transaksi_produk_penawaran->jenis = 'penjualan';
                    $transaksi_produk_penawaran->qty = -$request->input('kuantitas')[$i];
        
                    $produk_penawaran = Produk_penawaran::where('id',$request->input('produk')[$i])->first();
                    $transaksi_produk_penawaran->unit = $produk_penawaran->unit;
                    $transaksi_produk_penawaran->save();
    
                    $jenis_transaksi = $transaksi_produk_penawaran->jenis;
                }else{
                    $transaksi_produk = new Transaksi_produk;
                    $transaksi_produk->id_company = Auth::user()->id_company;
                    $transaksi_produk->id_transaksi = $this->id;
                    $transaksi_produk->id_produk = $request->input('produk')[$i];
                    $transaksi_produk->tanggal = $request->input('tanggal_transaksi');
                    $transaksi_produk->tipe = $tipe;
                    $transaksi_produk->jenis = 'penjualan';
                    $transaksi_produk->qty = -$request->input('kuantitas')[$i];

                    $produk = Produk::where('id',$request->input('produk')[$i])->first();
                    $transaksi_produk->unit = $produk->unit;
                    $transaksi_produk->save();

                    $jenis_transaksi = $transaksi_produk->jenis;
                }
            }else{
                $transaksi_produk = new Transaksi_produk;
                $transaksi_produk->id_company = Auth::user()->id_company;
                $transaksi_produk->id_transaksi = $this->id;
                $transaksi_produk->id_produk = $request->input('produk')[$i];
                $transaksi_produk->tanggal = $request->input('tanggal_transaksi');
                $transaksi_produk->tipe = $tipe;
                $transaksi_produk->jenis = 'penjualan';
                $transaksi_produk->qty = -$request->input('kuantitas')[$i];

                $produk = Produk::where('id',$request->input('produk')[$i])->first();
                $transaksi_produk->unit = $produk->unit;
                $transaksi_produk->save();

                $jenis_transaksi = $transaksi_produk->jenis;
            }
            
            
            

            if($jenis == 'pengiriman'){
                $this->updateStok($request->input('produk')[$i], $request->input('kuantitas')[$i]);
            }else if($jenis == 'penagihan'){
                $this->updateStokGudang(
                    $this->id,
                    $request->input('produk')[$i],
                    $id_gudang,
                    $request->input('kuantitas')[$i],
                    $request->input('tanggal_transaksi'),
                    $tipe,
                    $jenis_transaksi
                );
            }
        }
    }

    public function updateStok($produk, $kuantitas)
    {
        $produk = Produk::find($produk);
        $produk->stok = $produk->stok - $kuantitas;
        $produk->save();
    }

    public function updateStokGudang($id_transaksi, $produk, $gudang, $kuantitas, $tanggal, $tipe, $jenis)
    {
        $stok_gudang = new Stok_gudang;
        $stok_gudang->id_company = Auth::user()->id_company;
        $stok_gudang->id_transaksi = $id_transaksi;
        $stok_gudang->id_produk = $produk;
        $stok_gudang->id_gudang = $gudang;
        $stok_gudang->stok = $stok_gudang->stok - $kuantitas;
        $stok_gudang->tanggal = $tanggal;
        $stok_gudang->tipe = $tipe;
        $stok_gudang->jenis = $jenis;
        $stok_gudang->save();
    }

    public function ubah($request, $jenis = null)
    {
        $this->tanggal_transaksi = date('Y-m-d',strtotime($request->tanggal_transaksi));
        $this->id_pelanggan = $request->input('pelanggan');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_total') - $this->jumlah_terbayar;
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->no_rfq = $request->input('no_rfq');
        $this->pesan = $request->input('pesan');
        $this->memo = $request->input('memo');
        if($request->input('pic')){
            $this->pic = $request->input('pic');
        }
        if($this->sisa_tagihan == $this->total){
            $this->status = 'open';
        }elseif($this->sisa_tagihan < $this->total && $this->sisa_tagihan > 0){
            $this->status = 'partial';
        }else{
            $this->status = 'paid';
        }
        if($jenis == 'pemesanan'){
            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
            $this->info_pengiriman = $request->input('info_pengiriman');
            $this->sama_dengan_penagihan = $request->input('sama_dengan_penagihan');
            $this->tanggal_pengiriman = $request->input('tanggal_pengiriman') ? $request->input('tanggal_pengiriman') : null;
            $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
            if($request->input('gudang')){
                $gudang = Gudang::find((int)$request->input('gudang'));
                $this->id_gudang = $gudang->id;
                $this->nama_gudang = $gudang->nama;
            }else{
                $this->id_gudang = null;
                $this->nama_gudang = null;
            }
        }
        $this->save();

        $this->editDetailPenjualan($request);

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'penjualan';
        $log->save();
    }

    protected function editDetailPenjualan(Request $request)
    {
        $detail_penjualan = Detail_penjualan::where('id_penjualan',$this->id)->delete();
        $produk = $request->input('produk') ? $request->input('produk') : $request->input('produk_penawaran');
        for ($i = 0; $i < count($produk); $i++) {
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;

            $detail_penjualan = new Detail_penjualan;
            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            if($request->input('produk')){
                $detail_penjualan->id_produk = $request->input('produk')[$i];
            }
            if($request->input('produk_penawaran')){
                $detail_penjualan->id_produk_penawaran = $request->input('produk_penawaran')[$i];
            }
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];
            $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            $detail_penjualan->harga_satuan = $harga_satuan;
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i];
            $detail_penjualan->pajak = $jumlah * $pajak / 100;
            $detail_penjualan->jumlah = $jumlah;

            $detail_penjualan->save();
        }
    }

    public function selesai($id)
    {
        $penjualan = Penjualan::find($id);
        
        $no = $this->no('selesai');
        $selesai = $penjualan->replicate()->fill([
            'id_penagihan' => $id, 
            'id_jurnal' => null,
            'tanggal_transaksi' => date('Y-m-d'),
            'no' => $no,
            'no_str' => 'Sales Finish #' .$no,
            'tanggal_jatuh_tempo' => null,
            'status' => 'open',
            'jenis' => 'selesai',
        ]);
        $selesai->save();

        $penjualan->selesai = 'selesai';
        $penjualan->save();

    }
}
