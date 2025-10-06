<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Pembayaran_penjualan extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_penjualan';

    function no()
    {
        $no = Pembayaran_penjualan::select('no')
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

    public function detail_pembayaran_penjualan(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_penjualan::class, 'id_pembayaran_penjualan');
    }

    public function insert($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->no = $this->no();
        $this->no_str = 'Receive Payment #' . $this->no;
        $this->id_setor = $request->input('setor_ke');
        $this->setor = Akun::where('id',$this->id_setor)->first()->nama;
        $this->cara_pembayaran = $request->input('cara_pembayaran');
        $this->status_pembayaran = 'Lunas';
        $this->subtotal = $request->input('subtotal');
        $this->save();

        $this->insertDetailPembayaranPenjualan($request);
    }

    protected function insertDetailPembayaranPenjualan(Request $request)
    {
        for ($i = 0; $i < count($request->input('id_penjualan')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('subtotal')[$i] != '' && $request->input('subtotal')[$i] != null ){
                $detail_penjualan = new Detail_pembayaran_penjualan;
                $detail_penjualan->id_company = Auth::user()->id_company;
                $detail_penjualan->id_pembayaran_penjualan = $this->id;
                $detail_penjualan->id_penjualan = $request->input('id_penjualan')[$i];
                $detail_penjualan->jumlah = $total;
                $detail_penjualan->save();

                $penjualan = Penjualan::find($request->input('id_penjualan')[$i]);
                $penjualan->jumlah_terbayar = $total;
                $penjualan->sisa_tagihan = $penjualan->sisa_tagihan - $total;
                if($penjualan->sisa_tagihan == 0){
                    $penjualan->status = 'paid';
                }else{
                    $penjualan->status = 'partial';
                }
                $penjualan->tanggal_pembayaran = date('Y-m-d');
                $penjualan->save();

            }
        }
    }
}
