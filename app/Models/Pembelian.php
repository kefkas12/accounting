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
        $no = Pembelian::select('no')->where('jenis',$jenis)->orderBy('id','DESC')->first();
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
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->no = $this->no($jenis);
        if($jenis == 'faktur'){
            $this->no_str = 'Purchase Invoice #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Purchase Quote #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Purchase Order #' . $this->no;
        }
        $this->id_supplier = $request->input('supplier');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->status = 'open';
        $this->subtotal = $request->input('input_subtotal');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_sisa_tagihan');
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->jenis = $jenis;
        $this->id_jurnal = $idJurnal;
        if($jenis == 'pemesanan'){
            $this->id_penawaran = $id_jenis;
        }elseif($jenis == 'faktur' && $id_jenis != null){
            $this->id_pemesanan = $id_jenis;
        }
        $this->save();

        if($jenis == 'pemesanan'){
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->id_pemesanan = $this->id;
            $pembelian->status = 'closed';
            $pembelian->save();
        }elseif($jenis == 'faktur' && $id_jenis != null){
            $pembelian = Pembelian::find($id_jenis);
            $pembelian->status = 'closed';
            $pembelian->save();
        }

        $this->insertDetailPembelian($request);
    }

    protected function insertDetailPembelian(Request $request)
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
        }
    }

    public function edit($request)
    {
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->subtotal = $request->input('input_subtotal');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_total') - $this->jumlah_terbayar;
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
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
