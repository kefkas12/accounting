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

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'id_jurnal', 'id');
    }

    public function detail_pembayaran_penjualan(): HasMany
    {
        return $this->hasMany(Detail_pembayaran_penjualan::class, 'id_pembayaran_penjualan', 'id');
    }

    public function penjualan(): BelongsToMany
    {
        return $this->belongsToMany(
            Penjualan::class,
            'detail_pembayaran_penjualan',
            'id_pembayaran_penjualan',
            'id_penjualan'
        )->withPivot('jumlah');
    }

    public function insert($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;
        $this->tanggal_transaksi = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d');
        $this->tanggal_jatuh_tempo = DateTime::createFromFormat('d/m/Y', $request->tanggal_jatuh_tempo)->format('Y-m-d');
        $this->no = $this->no();
        $this->no_str = 'Receive Payment #' . $this->no;
        $this->id_setor = $request->input('setor_ke');
        $this->setor = Akun::where('id',$this->id_setor)->first()->nama;
        $this->cara_pembayaran = $request->input('cara_pembayaran');
        $this->status_pembayaran = 'Lunas';
        $this->subtotal = $request->input('subtotal');
        $this->save();

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'pembayaran_penjualan';
        $log->aksi = 'insert';
        $log->save();

        $this->insertDetailPembayaranPenjualan($request);
    }

    protected function insertDetailPembayaranPenjualan(Request $request)
    {
        for ($i = 0; $i < count($request->input('id_penjualan')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('subtotal')[$i] != '' && $request->input('subtotal')[$i] != null  && $request->input('total')[$i] > 0){
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

    public function ubah($request, $idJurnal)
    {
        $this->id_company = Auth::user()->id_company;
        $this->id_jurnal = $idJurnal;
        $this->tanggal_transaksi = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d');
        $this->tanggal_jatuh_tempo = DateTime::createFromFormat('d/m/Y', $request->tanggal_jatuh_tempo)->format('Y-m-d');
        $this->id_setor = $request->input('setor_ke');
        $this->setor = Akun::where('id',$this->id_setor)->first()->nama;
        $this->cara_pembayaran = $request->input('cara_pembayaran');
        $this->status_pembayaran = 'Lunas';
        $this->subtotal = $request->input('subtotal');
        $this->save();

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'pembayaran_penjualan';
        $log->aksi = 'edit';
        $log->save();

        $this->editDetailPembayaranPenjualan($request);
    }

    protected function editDetailPembayaranPenjualan(Request $request)
    {
        $detail_pembayaran_penjualan = Detail_pembayaran_penjualan::where('id_pembayaran_penjualan',$this->id);
        foreach($detail_pembayaran_penjualan->get() as $v){
            $penjualan = Penjualan::find($v->id_penjualan);
            $penjualan->jumlah_terbayar = $penjualan->jumlah_terbayar - $v->jumlah;
            $penjualan->sisa_tagihan = $penjualan->sisa_tagihan + $v->jumlah;
            if($penjualan->sisa_tagihan == 0){
                $penjualan->status = 'paid';
            }else if($penjualan->sisa_tagihan == $penjualan->total){
                $penjualan->status = 'open';
            }else{
                $penjualan->status = 'partial';
            }
            $penjualan->save();
        }
        $detail_pembayaran_penjualan->delete();

        for ($i = 0; $i < count($request->input('id_penjualan')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null && $request->input('total')[$i] > 0){
                
                
                $detail_pembayaran_penjualan = new Detail_pembayaran_penjualan;
                $detail_pembayaran_penjualan->id_company = Auth::user()->id_company;
                $detail_pembayaran_penjualan->id_pembayaran_penjualan = $this->id;
                $detail_pembayaran_penjualan->id_penjualan = $request->input('id_penjualan')[$i];
                $detail_pembayaran_penjualan->jumlah = $total;
                $detail_pembayaran_penjualan->save();

                
                $penjualan->jumlah_terbayar = $penjualan->jumlah_terbayar + $total;
                $penjualan->sisa_tagihan = $penjualan->sisa_tagihan - $total;
                if($penjualan->sisa_tagihan == 0){
                    $penjualan->status = 'paid';
                }else{
                    $penjualan->status = 'partial';
                }
                $penjualan->save();

            }
        }
    }
}
