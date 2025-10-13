<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';

    function no($kategori)
    {
        $no = Jurnal::select('no')
                    ->where('kategori',$kategori)
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

    public function detail_jurnal(): HasMany
    {
        return $this->hasMany(Detail_jurnal::class, 'id_jurnal');
    }

    public function transfer_uang($request, $id = null)
    {
        $jumlah = $request->input('jumlah') != '' || $request->input('jumlah') != null ? number_format((float)str_replace(",", "", $_POST['jumlah']), 2, '.', '') : 0;
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'bank_transfer';
        if(!$id){
            $this->no = $this->no('bank_transfer');
            $this->no_str = 'Bank Transfer #' . $this->no('bank_transfer');
        }
        $this->debit = $jumlah;
        $this->kredit = $jumlah;
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
        }

        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        $this->createDetailJurnal($this->id, $request->input('setor_ke'), $jumlah, 0);
        $this->updateAkunBalance($request->input('setor_ke'), $jumlah, 0);

        $this->createDetailJurnal($this->id, $request->input('transfer_dari'), 0, $jumlah);
        $this->updateAkunBalance($request->input('transfer_dari'), 0, $jumlah);
    }

    public function pembayaran_penjualan($request, $id = null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'receive_payment';
        if(!$id){
            $this->no = $this->no('receive_payment');
            $this->no_str = 'Receive Payment #' . $this->no('receive_payment');
        }
        $this->debit = $request->input('subtotal');
        $this->kredit = $request->input('subtotal');
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
        }

        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        $this->createDetailJurnal($this->id, $request->input('setor_ke'), $request->input('subtotal'), 0);
        $this->updateAkunBalance($request->input('setor_ke'), $request->input('subtotal'), 0);

        // dd(count($request->input('id_penjualan')));
        for ($i = 0; $i < count($request->input('id_penjualan')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null ){

                $this->createDetailJurnal($this->id, 4, 0, $total);
                $this->updateAkunBalance(4, 0, $total);
            }
        }
    }

    public function pembayaran_pembelian($request, $id = null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'purchase_payment';
        if(!$id){
            $this->no = $this->no('purchase_payment');
            $this->no_str = 'Purchase Payment #' . $this->no('purchase_payment');
        }
        $this->debit = $request->input('subtotal');
        $this->kredit = $request->input('subtotal');
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
        }

        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        for ($i = 0; $i < count($request->input('id_pembelian')); $i++) {
            $total = $request->input('total')[$i] != '' || $request->input('total')[$i] != null ? number_format((float)str_replace(",", "", $_POST['total'][$i]), 2, '.', '') : 0;
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null ){
                $this->createDetailJurnal($this->id, 33, $total, 0);
                $this->updateAkunBalance(33, $total, 0);
            }
        }

        $this->createDetailJurnal($this->id, $request->input('setor_ke'), 0, $request->input('subtotal'));
        $this->updateAkunBalance($request->input('setor_ke'), 0, $request->input('subtotal'));
    }

    public function pengiriman_penjualan($request, $id = null)
    {
        $multiple_gudang = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                            ->where('fitur','Multiple gudang')
                                            ->where('status','active')
                                            ->first();
        $gudang = Gudang::where('id_company',Auth::user()->id_company)->get();
        //pembelian
        //1-10200 - Persediaan Barang - 6 
        $cogs = 0;
        for ($i = 0; $i < count($request->input('produk')); $i++) {
            $jumlah_pengiriman = 0;
            if(isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                foreach($gudang as $v){
                    $jumlah_pengiriman += $request->input('kuantitas_'.$v->id)[$i];
                }
            }else{
                $jumlah_pengiriman = $request->input('kuantitas')[$i];
            }
            $pembelian = Pembelian::where('id_company',Auth::user()->id_company)->where('jenis','pengiriman')->pluck('id');
            $harga_total = Detail_pembelian::whereIn('id_pembelian',$pembelian)->where('id_produk',$request->input('produk')[$i])->sum(DB::raw('kuantitas * harga_satuan'));
            $kuantitas = Detail_pembelian::whereIn('id_pembelian',$pembelian)->where('id_produk',$request->input('produk')[$i])->sum('kuantitas');
            // $harga_satuan = Detail_pembelian::whereIn('id_pembelian',$pembelian)->where('id_produk',$request->input('produk')[$i])->sum('harga_satuan');
            $harga_barang = $harga_total /(int)$kuantitas;
            $cogs += $harga_barang * $jumlah_pengiriman;
        }

        $subtotal = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') + $request->input('input_subtotal') - $request->input('input_diskon_per_baris') : $request->input('input_subtotal') - $request->input('input_diskon_per_baris');

        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'sales_delivery';
        if(!$id){
            $this->no = $this->no('sales_delivery');
            $this->no_str = 'Sales Delivery #' . $this->no('sales_delivery');
        }
        $this->debit = $cogs + $subtotal;
        $this->kredit = $cogs + $subtotal;
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        $this->createDetailJurnal($this->id, 5, $subtotal, 0);
        $this->updateAkunBalance(5, $subtotal, 0);

        $this->createDetailJurnal($this->id, 62, $cogs, 0);
        $this->updateAkunBalance(62, $cogs, 0);

        $this->createDetailJurnal($this->id, 61, 0, $subtotal);
        $this->updateAkunBalance(61, 0, $subtotal);

        $this->createDetailJurnal($this->id, 6, 0, $cogs);
        $this->updateAkunBalance(6, 0, $cogs);
    }
    //update skt
    public function pengiriman_pembelian($request, $id = null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'purchase_delivery';
        if(!$id){
            $this->no = $this->no('purchase_delivery');
            $this->no_str = 'Purchase Delivery #' . $this->no('purchase_delivery');
        }
        $this->debit = $request->input('input_subtotal');
        $this->kredit = $request->input('input_subtotal');
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();
        
        if($request->input('input_ongkos_kirim')){
            $this->createDetailJurnal($this->id, 65, $request->input('input_ongkos_kirim'), 0);
            $this->updateAkunBalance(65, $request->input('input_ongkos_kirim'), 0);
        }

        $this->createDetailJurnal($this->id, 6, $request->input('input_subtotal'), 0);
        $this->updateAkunBalance(6, $request->input('input_subtotal'), 0);

        $subtotal = $request->input('input_ongkos_kirim')? $request->input('input_ongkos_kirim') + $request->input('input_subtotal') : $request->input('input_subtotal');

        $this->createDetailJurnal($this->id, 34, 0, $subtotal);
        $this->updateAkunBalance(34, 0, $subtotal);
    }

    public function pengiriman_penagihan($request, $id = null)
    {
        $ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : 0;
        $diskon_per_baris = $request->input('input_diskon_per_baris') ? $request->input('input_diskon_per_baris') : 0;
        
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'sales_invoice';
        if(!$id){
            $this->no = $this->no('sales_invoice');
            $this->no_str = 'Sales Invoice #' . $this->no('sales_invoice');
        }
        $this->debit = $request->input('input_total') + $request->input('input_subtotal');

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->kredit = $request->input('input_subtotal') + $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ongkos_kirim') + $request->input('input_ppn');
        }else{
            $this->kredit = $request->input('input_subtotal') + $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ppn');
        }
        
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        $this->createDetailJurnal($this->id, 4, $request->input('input_total'), 0);
        $this->updateAkunBalance(4, $request->input('input_total'), 0);

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 61, $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ongkos_kirim'), 0);
            $this->updateAkunBalance(61, $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ongkos_kirim'), 0);
        }else{
            $this->createDetailJurnal($this->id, 61, $request->input('input_subtotal') - $diskon_per_baris, 0);
            $this->updateAkunBalance(61, $request->input('input_subtotal') - $diskon_per_baris, 0);
        }

        if($request->input('input_diskon_per_baris')){
            $this->createDetailJurnal($this->id, 59, $request->input('input_diskon_per_baris'), 0);
            $this->updateAkunBalance(59, $request->input('input_diskon_per_baris'), 0);
        }

        $this->createDetailJurnal($this->id, 58, 0, $request->input('input_subtotal'));
        $this->updateAkunBalance(58, 0, $request->input('input_subtotal'));

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 5, 0, $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ongkos_kirim'));  
            $this->updateAkunBalance(5, 0, $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_ongkos_kirim'));

            $this->createDetailJurnal($this->id, 121, 0, $request->input('input_ongkos_kirim'));  
            $this->updateAkunBalance(121, 0, $request->input('input_ongkos_kirim'));
        }else{
            $this->createDetailJurnal($this->id, 5, 0, $request->input('input_subtotal') - $diskon_per_baris);  
            $this->updateAkunBalance(5, 0, $request->input('input_subtotal') - $diskon_per_baris);
        }
        
        if($request->input('input_ppn')){
            $this->createDetailJurnal($this->id, 43, 0, $request->input('input_ppn'));
            $this->updateAkunBalance(43, 0, $request->input('input_ppn'));
        }
    }

    public function penjualan($request, $id = null, $is_requester = null)
    {
        $ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : 0;
        $diskon_per_baris = $request->input('input_diskon_per_baris') ? $request->input('input_diskon_per_baris') : 0;

        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'sales_invoice';
        if(!$id){
            $this->no = $this->no('sales_invoice');
            $this->no_str = 'Sales Invoice #' . $this->no('sales_invoice');
        }
        $this->debit = $request->input('input_total') + $request->input('input_subtotal') - $diskon_per_baris + $request->input('input_diskon_per_baris');
        $this->kredit = $request->input('input_total') + $request->input('input_subtotal') - $diskon_per_baris + $ongkos_kirim + $request->input('input_ppn');
        if($is_requester){
            $this->status = 'draf';
        }
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        $this->createDetailJurnal($this->id, 4, $request->input('input_total'), 0);
        $this->updateAkunBalance(4, $request->input('input_total'), 0);

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 61, $request->input('input_subtotal') - $diskon_per_baris + $ongkos_kirim, 0);
            $this->updateAkunBalance(61, $request->input('input_subtotal') - $diskon_per_baris + $ongkos_kirim, 0);
        }else{
            $this->createDetailJurnal($this->id, 61, $request->input('input_subtotal') - $diskon_per_baris, 0);
            $this->updateAkunBalance(61, $request->input('input_subtotal') - $diskon_per_baris, 0);
        }

        if($request->input('input_diskon_per_baris')){
            $this->createDetailJurnal($this->id, 59, $request->input('input_diskon_per_baris'), 0);
            $this->updateAkunBalance(59, $request->input('input_diskon_per_baris'), 0);
        }

        $this->createDetailJurnal($this->id, 58, 0, $request->input('input_subtotal'));
        $this->updateAkunBalance(58, 0, $request->input('input_subtotal'));
        
        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 5, 0, $request->input('input_subtotal') - $request->input('input_diskon_per_baris') + $request->input('input_ongkos_kirim'));  
            $this->updateAkunBalance(5, 0, $request->input('input_subtotal') - $request->input('input_diskon_per_baris') + $request->input('input_ongkos_kirim'));

            $this->createDetailJurnal($this->id, 121, 0, $request->input('input_ongkos_kirim'));  
            $this->updateAkunBalance(121, 0, $request->input('input_ongkos_kirim'));
        }else{
            $this->createDetailJurnal($this->id, 5, 0, $request->input('input_subtotal') - $request->input('input_diskon_per_baris'));  
            $this->updateAkunBalance(5, 0, $request->input('input_subtotal') - $request->input('input_diskon_per_baris'));
        }

        if($request->input('input_ppn')){
            $this->createDetailJurnal($this->id, 43, 0, $request->input('input_ppn'));
            $this->updateAkunBalance(43, 0, $request->input('input_ppn'));
        }

        //produk
        // for ($i = 0; $i < count($request->input('produk')); $i++) {
        //     $produk = Produk::find($request->input('produk')[$i]);
        //     $this->createDetailJurnal($this->id, 62, $request->input('kuantitas')[$i] * $produk->harga_beli_rata_rata, 0);
        //     $this->updateAkunBalance(62, $request->input('kuantitas')[$i] * $produk->harga_beli_rata_rata, 0);

        //     $this->createDetailJurnal($this->id, 6, 0, $request->input('kuantitas')[$i] * $produk->harga_beli_rata_rata);
        //     $this->updateAkunBalance(6, 0, $request->input('kuantitas')[$i] * $produk->harga_beli_rata_rata);    
        // }
    }

    public function pengiriman_faktur($request, $id = null)
    {
        $ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : 0;

        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'purchase_invoice';
        if(!$id){
            $this->no = $this->no('purchase_invoice');
            $this->no_str = 'Purchase Invoice #' . $this->no('purchase_invoice');
        }
        $this->debit = $request->input('input_subtotal') + $request->input('input_ppn') + $ongkos_kirim;
        $this->kredit = $request->input('input_total');
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 34, $request->input('input_subtotal') + $request->input('input_ongkos_kirim'), 0);
            $this->updateAkunBalance(34, $request->input('input_subtotal') + $request->input('input_ongkos_kirim'), 0);
        }else{
            $this->createDetailJurnal($this->id, 34, $request->input('input_subtotal'), 0);
            $this->updateAkunBalance(34, $request->input('input_subtotal'), 0);
        }
        if($request->input('input_ppn')){
            $this->createDetailJurnal($this->id, 13, $request->input('input_ppn'), 0);
            $this->updateAkunBalance(13, $request->input('input_ppn'), 0);
        }

        $this->createDetailJurnal($this->id, 33, 0, $request->input('input_total'));
        $this->updateAkunBalance(33, 0, $request->input('input_total'));
    }

    public function pembelian(Request $request, $id = null, $is_requester = null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal_transaksi');
        $this->kategori = 'purchase_invoice';
        if(!$id){
            $this->no = $this->no('purchase_invoice');
            $this->no_str = 'Purchase Invoice #' . $this->no('purchase_invoice');
        }
        $this->debit = $request->input('input_subtotal') + $request->input('input_ppn');
        $this->kredit = $request->input('input_total');
        $this->save();

        if($id){
            $detail_jurnal = Detail_jurnal::where('id_jurnal',$this->id)->get();
            foreach($detail_jurnal as $v){
                $akun_company = Akun_company::where('id_company',Auth::user()->id_company)
                            ->where('id_akun',$v->id_akun)->first();
                $akun_company->saldo = $akun_company->saldo - $v->debit + $v->kredit;
                $akun_company->save();
            }
            
        }
        Detail_jurnal::where('id_jurnal',$this->id)->delete();

        if($request->input('input_ongkos_kirim') && $request->input('input_ongkos_kirim') > 0){
            $this->createDetailJurnal($this->id, 34, $request->input('input_subtotal') + $request->input('input_ongkos_kirim'), 0);
            $this->updateAkunBalance(34, $request->input('input_subtotal') + $request->input('input_ongkos_kirim'), 0);
        }else{
            $this->createDetailJurnal($this->id, 34, $request->input('input_subtotal'), 0);
            $this->updateAkunBalance(34, $request->input('input_subtotal'), 0);
        }

        if($request->input('input_ppn')){
            $this->createDetailJurnal($this->id, 13, $request->input('input_ppn'), 0);
            $this->updateAkunBalance(13, $request->input('input_ppn'), 0);
        }

        $this->createDetailJurnal($this->id, 33, 0, $request->input('input_total'));
        $this->updateAkunBalance(33, 0, $request->input('input_total'));
        
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

    private function updateAkunBalance($idAkun, $debit, $kredit)
    {
        $akun_company = Akun_company::where('id_akun',$idAkun)
                                    ->where('id_company',Auth::user()->id_company)
                                    ->first();
        $saldo = $akun_company ? $akun_company->saldo : 0;

        $akun_company = Akun_company::where('id_akun', $idAkun)
                                    ->where('id_company', Auth::user()->id_company)
                                    ->update(['saldo' => $saldo + $debit - $kredit]);
    }

    public function penerimaan(Request $request)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = $request->input('tanggal');
        $this->kategori = 'penerimaan';
        $this->no = $this->no('penerimaan');
        $this->no_str = 'Penerimaan #' . $this->no('penerimaan');
        $this->debit = $request->input('total_nilai');
        $this->kredit = $request->input('total_nilai');
        $this->save();

        for ($i = 0; $i < count($request->input('akun')); $i++) {
            if($request->input('total')[$i] != '' && $request->input('total')[$i] != null ){

                $this->createDetailJurnal($this->id, 33, $request->input('total')[$i], 0);
                $this->updateAkunBalance(33, $request->input('total')[$i], 0);
            }
        }

        $this->createDetailJurnal($this->id, $request->input('setor_ke'), 0, $request->input('subtotal'));
        $this->updateAkunBalance($request->input('setor_ke'), 0, $request->input('subtotal'));

        
    }
}
