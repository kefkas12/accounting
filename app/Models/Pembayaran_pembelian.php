<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Pembayaran_pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_pembelian';

    function no()
    {
        $no = Pembayaran_pembelian::select('no')
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

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'id_jurnal', 'id');
    }

    public function detail_pembayaran_pembelian(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_pembelian::class, 'id_pembayaran_pembelian', 'id');
    }

    public function pembelian(): BelongsToMany
    {
        return $this->belongsToMany(
            Pembelian::class,
            'detail_pembayaran_pembelian',
            'id_pembayaran_pembelian',
            'id_pembelian'
        )->withPivot('jumlah');
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
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null && $request->input('total')[$i] > 0){
                $detail_pembelian = new Detail_pembayaran_pembelian;
                $detail_pembelian->id_company = Auth::user()->id_company;
                $detail_pembelian->id_pembayaran_pembelian = $this->id;
                $detail_pembelian->id_pembelian = $request->input('id_pembelian')[$i];
                $detail_pembelian->jumlah = $total;
                $detail_pembelian->save();

                $pembelian = Pembelian::find($request->input('id_pembelian')[$i]);
                $pembelian->jumlah_terbayar = $pembelian->jumlah_terbayar + $total;
                $pembelian->sisa_tagihan = $pembelian->sisa_tagihan - $total;
                if($pembelian->sisa_tagihan == 0){
                    $pembelian->status = 'paid';
                }else{
                    $pembelian->status = 'partial';
                }
                $pembelian->save();

            }
        }
    }

    public function ubah($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;
        $this->tanggal_transaksi = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d');
        $this->id_setor = $request->input('setor_ke');
        $this->setor = Akun::where('id',$this->id_setor)->first()->nama;
        $this->cara_pembayaran = $request->input('cara_pembayaran');
        $this->status_pembayaran = 'Lunas';
        $this->subtotal = $request->input('subtotal');
        $this->save();

        $this->editDetailPembayaranPembelian($request);
    }

    protected function editDetailPembayaranPembelian(Request $request)
    {
        for ($i = 0; $i < count($request->input('id_pembelian')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null  && $request->input('total')[$i] > 0){
                $detail_pembayaran_pembelian = Detail_pembayaran_pembelian::where('id_pembayaran_pembelian',$this->id)
                                            ->where('id_pembelian',$request->input('id_pembelian')[$i])
                                            ->first();
                $pembelian = Pembelian::find($request->input('id_pembelian')[$i]);
                $pembelian->jumlah_terbayar = $pembelian->jumlah_terbayar - $detail_pembayaran_pembelian->jumlah;
                $pembelian->sisa_tagihan = $pembelian->sisa_tagihan + $detail_pembayaran_pembelian->jumlah;
                $pembelian->save();
                
                $detail_pembayaran_pembelian->delete();
                
                $detail_pembayaran_pembelian = new Detail_pembayaran_pembelian;
                $detail_pembayaran_pembelian->id_company = Auth::user()->id_company;
                $detail_pembayaran_pembelian->id_pembayaran_pembelian = $this->id;
                $detail_pembayaran_pembelian->id_pembelian = $request->input('id_pembelian')[$i];
                $detail_pembayaran_pembelian->jumlah = $total;
                $detail_pembayaran_pembelian->save();

                
                $pembelian->jumlah_terbayar = $pembelian->jumlah_terbayar + $total;
                $pembelian->sisa_tagihan = $pembelian->sisa_tagihan - $total;
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
