<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';

    function no($kategori)
    {
        $no = Jurnal::select('no')->where('kategori',$kategori)->orderBy('id', 'DESC')->first();
        if ($no) {
            $no = $no->no;
            $no++;
        } else {
            $no = 10001;
        }
        return $no;
    }

    public function detail_jurnal(): HasMany
    {
        return $this->hasMany(Detail_jurnal::class, 'id_jurnal');
    }

    public function pembayaran(Request $request)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'receive_payment';
        $this->no = $this->no('receive_payment');
        $this->no_str = 'Receive Payment #' . $this->no('receive_payment');
        $this->debit = $request->input('subtotal');
        $this->kredit = $request->input('subtotal');
        $this->save();

        $this->createDetailJurnal($this->id, $request->input('setor_ke'), $request->input('subtotal'), 0);
        $this->updateAkunBalance($request->input('setor_ke'), $request->input('subtotal'));

        for ($i = 0; $i < count($request->input('id_penjualan')); $i++) {
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null ){

                $this->createDetailJurnal($this->id, 4, 0, $request->input('total')[$i]);
                $this->updateAkunBalance(4, $request->input('total')[$i]);
            }
        }
    }

    public function penjualan(Request $request)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'sales_invoice';
        $this->no = $this->no('sales_invoice');
        $this->no_str = 'Sales Invoice #' . $this->no('sales_invoice');
        $this->debit = $request->input('sisa_tagihan');
        $this->kredit = $request->input('subtotal') + $request->input('ppn');
        $this->save();

        $this->createDetailJurnal($this->id, 4, $request->input('sisa_tagihan'), 0);
        $this->updateAkunBalance(4, $request->input('sisa_tagihan'));

        if($request->input('diskon_per_baris')){
            $this->createDetailJurnal($this->id, 59, $request->input('diskon_per_baris'), 0);
            $this->updateAkunBalance(59, $request->input('diskon_per_baris'));
        }

        $this->createDetailJurnal($this->id, 58, 0, $request->input('subtotal'));
        $this->updateAkunBalance(58, $request->input('subtotal'));
        
        if($request->input('ppn')){
            $this->createDetailJurnal($this->id, 43, 0, $request->input('ppn'));
            $this->updateAkunBalance(43, $request->input('ppn'));
        }
    }

    private function createDetailJurnal($idJurnal, $idAkun, $debit, $kredit)
    {
        $detail_jurnal = new Detail_jurnal;
        $detail_jurnal->id_company = Auth::user()->id_company;
        $detail_jurnal->id_jurnal = $idJurnal;
        $detail_jurnal->id_akun = $idAkun;
        $detail_jurnal->debit = $debit;
        $detail_jurnal->kredit = $kredit;
        $detail_jurnal->save();
    }

    private function updateAkunBalance($idAkun, $amount)
    {
        $akun = Akun::find($idAkun);
        $akun->saldo += $amount;
        $akun->save();
    }
}
