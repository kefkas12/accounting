<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelian';

    function no($jenis){
        $no = Pembelian::select('no')
                        ->where('jenis',$jenis)
                        ->where('id_company',Auth::user()->id_company)
                        ->orderBy('id','DESC')
                        ->first();
        if($no){
            $no = $no->no;
            $no++; 
        }else{
            $no = 10001;
        }
        return $no;
    }

    public function detail_pembelian(): HasMany
    {
        return $this->hasMany(Detail_pembelian::class, 'id_pembelian');
    }

    public function detail_pembayaran_pembelian(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_pembelian::class, 'id_pembelian');
    }

    public function kontak(): BelongsTo
    {
        return $this->belongsTo(Kontak::class, 'id_supplier');
    }

    public function penawaran()
    {
        return $this->belongsTo(Pembelian::class, 'id_penawaran');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pembelian::class, 'id_pemesanan');
    }

    public function insert($request, $idJurnal, $jenis, $id_jenis=null, $is_requester=null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = date('Y-m-d',strtotime($request->input('tanggal_transaksi')));
        $this->no = $this->no($jenis);
        if($jenis == 'faktur'){
            $this->no_str = 'Purchase Invoice #' . $this->no;
            $tipe = 'Faktur Pembelian #' . $this->no;
        }else if($jenis == 'pengiriman'){
            $this->no_str = 'Purchase Delivery #' . $this->no;
            $tipe = 'Pengiriman Pembelian #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Purchase Quote #' . $this->no;
            $tipe = 'Penawaran Pembelian #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Purchase Order #' . $this->no;
            $tipe = 'Pesanan Pembelian #' . $this->no;
        }

        $this->id_supplier = $request->input('supplier');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->status = $is_requester ? 'draf' : 'open';
        $this->subtotal = $request->input('input_subtotal');
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
        
        if(($jenis == 'faktur' || $jenis == 'pengiriman') && $id_jenis != null){
            $this->id_penawaran = isset(Penjualan::find($id_jenis)->id_penawaran) ? Penjualan::find($id_jenis)->id_penawaran : null;
            if(Pembelian::find($id_jenis)->jenis == 'pengiriman'){
                $this->id_pemesanan = Pembelian::find($id_jenis)->id_pemesanan;
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
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->id_pemesanan = $this->id;
            $pembelian->status = 'closed';
            $pembelian->save();
        }elseif($jenis == 'pengiriman' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pengiriman = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'faktur' && $id_jenis != null){
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->status = 'closed';
            $pembelian->save();
        }

        $this->insertDetailPembelian($request, $tipe, $jenis,$this->id_gudang);
    
        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'pembelian';
        $log->save();
    }

    protected function insertDetailPembelian(Request $request, $tipe, $jenis, $id_gudang)
    {
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;

            $detail_pembelian = new Detail_pembelian;
            $detail_pembelian->id_company = Auth::user()->id_company;
            $detail_pembelian->id_pembelian = $this->id;
            $detail_pembelian->id_produk = $request->input('produk')[$i];
            $detail_pembelian->deskripsi = $request->input('deskripsi')[$i];
            $detail_pembelian->kuantitas = $request->input('kuantitas')[$i];
            $detail_pembelian->harga_satuan = $harga_satuan;
            $detail_pembelian->jumlah = $jumlah;
            $detail_pembelian->pajak = $jumlah * $pajak / 100;
            $detail_pembelian->save();

            $transaksi_produk = new Transaksi_produk;
            $transaksi_produk->id_company = Auth::user()->id_company;
            $transaksi_produk->id_transaksi = $this->id;
            $transaksi_produk->id_produk = $request->input('produk')[$i];
            $transaksi_produk->tanggal = $request->input('tanggal_transaksi');
            $transaksi_produk->tipe = $tipe;
            $transaksi_produk->jenis = 'pembelian';
            $transaksi_produk->qty = $request->input('kuantitas')[$i];

            $produk = Produk::where('id',$request->input('produk')[$i])->first();
            $transaksi_produk->unit = $produk->unit;
            $transaksi_produk->save();

            if($jenis == 'pengiriman'){
                $this->updateStok($request->input('produk')[$i], $request->input('kuantitas')[$i]);
            }else if($jenis == 'faktur'){
                $this->updateStokGudang(
                    $this->id,
                    $request->input('produk')[$i],
                    $id_gudang,
                    $request->input('kuantitas')[$i],
                    $request->input('tanggal_transaksi'),
                    $tipe,
                    $transaksi_produk->jenis
                );
            }
        }
    }

    public function updateStok($produk, $kuantitas)
    {
        $produk = Produk::find($produk);
        $produk->stok = $produk->stok + $kuantitas;
        $produk->save();
    }

    public function updateStokGudang($id_transaksi, $produk, $gudang, $kuantitas, $tanggal, $tipe, $jenis)
    {
        $stok_gudang = new Stok_gudang;
        $stok_gudang->id_company = Auth::user()->id_company;
        $stok_gudang->id_transaksi = $id_transaksi;
        $stok_gudang->id_produk = $produk;
        $stok_gudang->id_gudang = $gudang;
        $stok_gudang->stok = $stok_gudang->stok + $kuantitas;
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
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_total') - $this->jumlah_terbayar;
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->pesan = $request->input('pesan');
        $this->memo = $request->input('memo');
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

        $this->editDetailPembelian($request);

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'pembelian';
        $log->save();
    }

    protected function editDetailPembelian(Request $request)
    {
        $detail_pembelian = Detail_pembelian::where('id_pembelian',$this->id)->delete();
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;
            
            $detail_pembelian = new Detail_pembelian;
            $detail_pembelian->id_company = Auth::user()->id_company;
            $detail_pembelian->id_pembelian = $this->id;
            $detail_pembelian->id_produk = $request->input('produk')[$i];
            $detail_pembelian->deskripsi = $request->input('deskripsi')[$i];
            $detail_pembelian->kuantitas = $request->input('kuantitas')[$i];
            $detail_pembelian->harga_satuan = $harga_satuan;
            $detail_pembelian->pajak = $jumlah * $pajak / 100;
            $detail_pembelian->jumlah = $jumlah;

            $detail_pembelian->save();
        }
    }
}
