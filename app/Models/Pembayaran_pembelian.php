<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Pembayaran_pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_pembelian';

    function no()
    {
        $no = Pembayaran_pembelian::select('no')->orderBy('id', 'DESC')->first();
        if ($no) {
            $no = $no->no;
            $no++;
        } else {
            $no = 10001;
        }
        return $no;
    }

    public function detail_pembayaran_pembelian(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_pembelian::class, 'id_pembayaran_pembelian');
    }

    public function insert($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->no = $this->no();
        $this->no_str = 'Purchase Payment #' . $this->no;
        $this->id_setor = $request->input('setor_ke');
        $this->setor = Akun::where('id',$this->id_setor)->first()->nama;
        $this->cara_pembayaran = $request->input('cara_pembayaran');
        $this->status_pembayaran = 'Lunas';
        $this->subtotal = $request->input('subtotal');
        $this->save();

        $this->insertDetailPembayaranPembelian($request);
    }

    protected function insertDetailPembayaranPembelian(Request $request)
    {
        for ($i = 0; $i < count($request->input('id_pembelian')); $i++) {
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null ){
                $detail_pembelian = new Detail_pembayaran_pembelian;
                $detail_pembelian->id_pembayaran_pembelian = $this->id;
                $detail_pembelian->id_pembelian = $request->input('id_pembelian')[$i];
                $detail_pembelian->jumlah = $request->input('total')[$i];
                $detail_pembelian->save();

                $pembelian = Pembelian::find($request->input('id_pembelian')[$i]);
                $pembelian->sisa_tagihan = $pembelian->sisa_tagihan - $request->input('total')[$i];
                if($pembelian->sisa_tagihan == 0){
                    $pembelian->status = 'paid';
                }else{
                    $pembelian->status = 'partial';
                }
                $pembelian->save();

            }
        }
    }
}
