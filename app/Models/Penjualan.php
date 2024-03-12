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

    function no($jenis)
    {
        $no = Penjualan::select('no')->where('jenis',$jenis)->orderBy('id', 'DESC')->first();
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

    public function insert($request, $idJurnal, $jenis, $id_jenis=null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->no = $this->no($jenis);
        if($jenis == 'penagihan'){
            $this->no_str = 'Sales Invoice #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Sales Quote #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Sales Order #' . $this->no;
        }
        $this->id_pelanggan = $request->input('pelanggan');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->status = 'open';
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_sisa_tagihan');
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->jenis = $jenis;
        $this->id_jurnal = $idJurnal;
        if($jenis == 'pemesanan'){
            $this->id_penawaran = $id_jenis;
        }elseif($jenis == 'penagihan' && $id_jenis != null){
            $this->id_pemesanan = $id_jenis;
        }
        $this->save();

        if($jenis == 'pemesanan'){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pemesanan = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'penagihan' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->status = 'closed';
            $penjualan->save();
        }

        $this->insertDetailPenjualan($request);
    }

    protected function insertDetailPenjualan(Request $request)
    {
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $detail_penjualan = new Detail_penjualan;

            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            $detail_penjualan->id_produk = $request->input('produk')[$i];
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];
            $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            $detail_penjualan->harga_satuan = $request->input('harga_satuan')[$i];
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i];
            $detail_penjualan->pajak = $request->input('jumlah')[$i] * $request->input('pajak')[$i] / 100;
            $detail_penjualan->jumlah = $request->input('jumlah')[$i];

            $detail_penjualan->save();
        }
    }

    public function edit($request)
    {
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->id_pelanggan = $request->input('pelanggan');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_sisa_tagihan');
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->save();

        $this->editDetailPenjualan($request);
    }

    protected function editDetailPenjualan(Request $request)
    {
        $detail_penjualan = Detail_penjualan::where('id_penjualan',$this->id)->delete();
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $detail_penjualan = new Detail_penjualan;

            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            $detail_penjualan->id_produk = $request->input('produk')[$i];
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];
            $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            $detail_penjualan->harga_satuan = $request->input('harga_satuan')[$i];
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i];
            $detail_penjualan->pajak = $request->input('jumlah')[$i] * $request->input('pajak')[$i] / 100;
            $detail_penjualan->jumlah = $request->input('jumlah')[$i];

            $detail_penjualan->save();
        }
    }
}
