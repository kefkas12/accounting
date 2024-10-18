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

    public function insert($request, $idJurnal, $jenis, $id_jenis=null)
    {
        $ongkos_kirim = 0;

        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
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
        $this->status = 'open';
        if($request->input('input_ongkos_kirim')){
            $ongkos_kirim = $request->input('input_ongkos_kirim');
            $this->ongkos_kirim = $request->input('input_ongkos_kirim');
        }
        $this->subtotal = $request->input('input_subtotal');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_sisa_tagihan');
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat_penagihan') ? $request->input('alamat_penagihan') : $request->input('alamat');
        $this->email = $request->input('email');
        $this->jenis = $jenis;
        $this->id_jurnal = $idJurnal;

        //update
        $this->ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : null;
        if($request->input('info_pengiriman') == 'on'){
            $this->tanggal_pengiriman = $request->input('tanggal_pengiriman') ? $request->input('tanggal_pengiriman') : null;
            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
        }
        
        $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
        $this->alamat = $this->alamat ? $this->alamat : $this->alamat_pengiriman;
        if($jenis == 'pemesanan' || $jenis == 'pengiriman'){
            if($id_jenis != null && $jenis == 'pemesanan'){
                $this->id_penawaran = $id_jenis;
            }else if($id_jenis != null && $jenis == 'pengiriman'){
                $this->id_pemesanan = $id_jenis;
                $this->tanggal_pengiriman = $request->input('tanggal_pengiriman') ? $request->input('tanggal_pengiriman') : $request->input('tanggal_transaksi');

                $this->info_pengiriman = Pembelian::find($id_jenis)->info_pengiriman;
                $this->sama_dengan_penagihan = Pembelian::find($id_jenis)->sama_dengan_penagihan;
            }else{
                $this->info_pengiriman = $request->input('info_pengiriman') ? $request->input('info_pengiriman') : null;
                $this->sama_dengan_penagihan = $request->input('info_pengiriman') == 'on' ? $request->input('sama_dengan_penagihan') : null;
            }
        }elseif(($jenis == 'faktur' || $jenis == 'pengiriman') && $id_jenis != null){
            if(Pembelian::find($id_jenis)->jenis == 'pengiriman'){
                $this->id_pemesanan = Pembelian::find($id_jenis)->id_pemesanan;
                $this->info_pengiriman = Pembelian::find($id_jenis)->info_pengiriman;
                $this->sama_dengan_penagihan = Pembelian::find($id_jenis)->sama_dengan_penagihan;
            }else{
                $this->id_pemesanan = $id_jenis;
            }
        }
        if($request->input('gudang')){
            $gudang = Gudang::find((int)$request->input('gudang'));
            $this->id_gudang = $gudang->id;
            $this->nama_gudang = $gudang->nama;
        }
        $this->save();
        
        if(($jenis == 'pemesanan' || $jenis == 'pengiriman') && $id_jenis != null){
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->id_pemesanan = $this->id;
            $pembelian->status = 'closed';
            $pembelian->save();
        }elseif($jenis == 'faktur' && $id_jenis != null){
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->status = 'closed';
            $pembelian->save();
        }

        $this->insertDetailPembelian($request, $tipe, $jenis,$this->id_gudang);
    }

    protected function insertDetailPembelian(Request $request, $tipe, $jenis, $id_gudang)
    {
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $detail_pembelian = new Detail_pembelian;
            $detail_pembelian->id_company = Auth::user()->id_company;
            $detail_pembelian->id_pembelian = $this->id;
            $detail_pembelian->id_produk = $request->input('produk')[$i];
            $detail_pembelian->deskripsi = $request->input('deskripsi')[$i];
            $detail_pembelian->kuantitas = $request->input('kuantitas')[$i];
            $detail_pembelian->harga_satuan = $request->input('harga_satuan')[$i];
            $detail_pembelian->pajak = $request->input('jumlah')[$i] * $request->input('pajak')[$i] / 100;
            $detail_pembelian->jumlah = $request->input('jumlah')[$i];

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
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->subtotal = $request->input('input_subtotal');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_total') - $this->jumlah_terbayar;
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        if($this->sisa_tagihan > 0){
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
    }

    protected function editDetailPembelian(Request $request)
    {
        $detail_pembelian = Detail_pembelian::where('id_pembelian',$this->id)->delete();
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $detail_pembelian = new Detail_pembelian;

            $detail_pembelian->id_company = Auth::user()->id_company;
            $detail_pembelian->id_pembelian = $this->id;
            $detail_pembelian->id_produk = $request->input('produk')[$i];
            $detail_pembelian->deskripsi = $request->input('deskripsi')[$i];
            $detail_pembelian->kuantitas = $request->input('kuantitas')[$i];
            $detail_pembelian->harga_satuan = $request->input('harga_satuan')[$i];
            $detail_pembelian->pajak = $request->input('jumlah')[$i] * $request->input('pajak')[$i] / 100;
            $detail_pembelian->jumlah = $request->input('jumlah')[$i];

            $detail_pembelian->save();
        }
    }
}
