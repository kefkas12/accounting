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

    function no()
    {
        $no = Penjualan::select('no')->orderBy('id', 'DESC')->first();
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

    public function insert($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->no = $this->no();
        $this->no_str = 'Sales Invoice #' . $this->no;
        $this->id_pelanggan = $request->input('pelanggan');
        $this->tanggal_jatuh_tempo = $request->input('tanggal_jatuh_tempo');
        $this->status = 'open';
        $this->subtotal = $request->input('subtotal');
        $this->ppn = $request->input('ppn');
        $this->sisa_tagihan = $request->input('sisa_tagihan');
        $this->total = $request->input('total');
        $this->alamat = $request->input('alamat');
        $this->email = $request->input('email');
        $this->id_jurnal = $idJurnal;
        $this->save();

        $this->insertDetailPenjualan($request);
    }

    protected function insertDetailPenjualan(Request $request)
    {
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $detail_penjualan = new Detail_penjualan;

            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            $detail_penjualan->id_produk = $request->input('produk')[$i];
            $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            $detail_penjualan->harga_satuan = $request->input('harga_satuan')[$i];
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i];
            $detail_penjualan->pajak = $request->input('jumlah')[$i] * $request->input('pajak')[$i] / 100;
            $detail_penjualan->jumlah = $request->input('jumlah')[$i];

            $detail_penjualan->save();
        }
    }
}
