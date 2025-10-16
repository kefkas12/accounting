<?php

namespace App\Models;

use DateTime;
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

    protected $fillable = [
        'id_penagihan', 
        'id_jurnal',
        'tanggal_transaksi',
        'no',
        'no_str',
        'tanggal_jatuh_tempo',
        'status',
        'jenis'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Kontak::class, 'id_pelanggan', 'id');
    }

    function no($jenis)
    {
        $no = Penjualan::select('no')
                        ->where('jenis',$jenis)
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

    public function detail_penjualan(): HasMany
    {
        return $this->hasMany(Detail_penjualan::class, 'id_penjualan');
    }

    public function dokumen_penjualan(): HasMany
    {
        return $this->hasMany(Dokumen_penjualan::class, 'id_penjualan');
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

    public function pengiriman()
    {
        return $this->belongsTo(Penjualan::class, 'id_pengiriman');
    }

    public function insert($request, $idJurnal, $jenis, $id_jenis=null, $is_requester=null)
    {
        $this->id_company = Auth::user()->id_company;
        $this->tanggal_transaksi = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d');
        $this->no = $this->no($jenis);
        if($jenis == 'penagihan'){
            $this->no_str = 'Sales Invoice #' . $this->no;
            $tipe = 'Penagihan Penjualan #' . $this->no;
        }else if($jenis == 'pengiriman'){
            $this->no_str = 'Sales Delivery #' . $this->no;
            $tipe = 'Pengiriman Penjualan #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Sales Quote #' . $this->no;
            $tipe = 'Penawaran Penjualan #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Sales Order #' . $this->no;
            $tipe = 'Pemesanan Penjualan #' . $this->no;
        }
        
        $this->id_pelanggan = $request->input('pelanggan');

        $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_jatuh_tempo);

        if ($date) {
            $this->tanggal_jatuh_tempo = $date->format('Y-m-d');
        } else {
            $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_jatuh_tempo);
            $this->tanggal_jatuh_tempo = $date ? $date->format('Y-m-d') : null;
        }

        $this->status = $is_requester ? 'draf' : 'open';
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        if($jenis == 'pengiriman' && $request->input('input_ongkos_kirim') > 0){
            $this->sisa_tagihan = $request->input('input_sisa_tagihan') + $request->input('input_ongkos_kirim');
            $this->total = $request->input('input_total') + $request->input('input_ongkos_kirim');
        }else{
            $this->sisa_tagihan = $request->input('input_sisa_tagihan');
            $this->total = $request->input('input_total');
        }
        $this->alamat = $request->input('alamat');
        $this->detail_alamat = $request->input('detail_alamat');
        $this->email = $request->input('email');
        $this->jenis = $jenis;
        $this->id_jurnal = $idJurnal;
        $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
        $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
        $this->info_pengiriman = $request->input('info_pengiriman') ? $request->input('info_pengiriman') : null;

        if($request->input('info_pengiriman') && $request->input('info_pengiriman') == 'on'){
            $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_pengiriman);

            if ($date) {
                $this->tanggal_pengiriman = $date->format('Y-m-d');
            } else {
                $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_pengiriman);
                $this->tanggal_pengiriman = $date ? $date->format('Y-m-d') : null;
            }

            $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
            $this->sama_dengan_penagihan = $request->input('sama_dengan_penagihan') ? $request->input('sama_dengan_penagihan') : null;
        }
        if($request->input('detail_alamat') != null){
            Alamat::create([
                'id_kontak' => $this->id_pelanggan,
                'alamat' => $request->input('detail_alamat')
            ]);
        }
        if($jenis == 'penawaran'){
            $this->no_rfq = $request->input('no_rfq');
            $this->pic = $request->input('pic');
        }elseif($jenis == 'pemesanan'){
            if($id_jenis != null){
                $this->id_penawaran = $id_jenis;
            }
            $this->no_rfq = Penjualan::find($id_jenis) ? Penjualan::find($id_jenis)->no_rfq : null;
            $this->pic = Penjualan::find($id_jenis) ? Penjualan::find($id_jenis)->pic : null;
        }elseif(($jenis == 'penagihan' || $jenis == 'pengiriman') && $id_jenis != null){
            $this->no_rfq = Penjualan::find($id_jenis)->no_rfq ? Penjualan::find($id_jenis)->no_rfq : null;
            $this->pic = Penjualan::find($id_jenis)->pic ? Penjualan::find($id_jenis)->pic : null;

            $this->id_penawaran = Penjualan::find($id_jenis)->id_penawaran ? Penjualan::find($id_jenis)->id_penawaran : null;
            if(Penjualan::find($id_jenis)->jenis == 'pengiriman'){
                $this->id_pemesanan = Penjualan::find($id_jenis)->id_pemesanan;
                $this->id_pengiriman = $id_jenis;
            }else{
                $this->id_pemesanan = $id_jenis;
            }
        }
        // if($request->input('gudang')){
        //     $gudang = Gudang::find((int)$request->input('gudang'));
        //     $this->id_gudang = $gudang->id;
        //     $this->nama_gudang = $gudang->nama;
        // }
        $this->ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : null;
        $this->pesan = $request->input('pesan') ? $request->input('pesan') : null;
        $this->memo = $request->input('memo') ? $request->input('memo') : null;
        $this->save();

        if($jenis == 'pemesanan' && isset($_POST['id_dokumen'])){
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_pemesanan = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }
        if($jenis == 'pengiriman' && isset($_POST['id_dokumen'])){
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_pengiriman = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }

        if($jenis == 'penagihan' && isset($_POST['id_dokumen'])){
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_penagihan = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }

        if(($jenis == 'pemesanan') && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pemesanan = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'pengiriman' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->id_pengiriman = $this->id;
            $penjualan->status = 'closed';
            $penjualan->save();
        }elseif($jenis == 'penagihan' && $id_jenis != null){
            $penjualan = Penjualan::find($id_jenis);
            $penjualan->status = 'closed';
            $penjualan->save();
        }

        if($jenis == 'pengiriman'){
            $penjualan = Penjualan::find($this->id);
            if($penjualan->id_penawaran){
                $penawaran = Penjualan::find($penjualan->id_penawaran);
                $penawaran->id_pengiriman = $penjualan->id;
                $penawaran->save();
            }
        }else if($jenis == 'penagihan'){
            $penjualan = Penjualan::find($this->id);
            if($penjualan->id_penawaran){
                $penawaran = Penjualan::find($penjualan->id_penawaran);
                $penawaran->id_penagihan = $penjualan->id;
                $penawaran->save();
            }
            if($penjualan->id_pemesanan){
                $pemesanan = Penjualan::find($penjualan->id_pemesanan);
                $pemesanan->id_penagihan = $penjualan->id;
                $pemesanan->save();
            }
            if($penjualan->id_pengiriman){
                $pengiriman = Penjualan::find($penjualan->id_pengiriman);
                $pengiriman->id_penagihan = $penjualan->id;
                $pengiriman->save();
            }
        }

        $this->insertDetailPenjualan($request, $tipe, $jenis,$this->id_gudang);


        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'penjualan';
        $log->aksi = 'insert';
        $log->save();

    }

    protected function insertDetailPenjualan(Request $request, $tipe, $jenis, $id_gudang)
    {
        $index = $request->input('produk') ? $request->input('produk') : $request->input('produk_penawaran');

        $multiple_gudang = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                    ->where('fitur','Multiple gudang')
                                                    ->where('status','active')
                                                    ->first();

        $gudang = Gudang::where('id_company',Auth::user()->id_company)->get();
        
        for ($i = 0; $i < count($index); $i++) {
            $kuantitas = 0;
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;

            $detail_penjualan = new Detail_penjualan;
            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            if($request->input('produk')){
                $detail_penjualan->id_produk = $request->input('produk')[$i];
            }
            if($request->input('produk_penawaran')){
                $detail_penjualan->id_produk_penawaran = $request->input('produk_penawaran')[$i];
            }
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];

            if(($jenis == 'pengiriman' ) && isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                foreach($gudang as $v){
                    $kuantitas += (int) $request->input('kuantitas_'.$v->id)[$i];
                }
                $detail_penjualan->kuantitas = $kuantitas;
            }else{
                $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            }
            $detail_penjualan->harga_satuan = $harga_satuan;
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i] ? $request->input('diskon_per_baris')[$i] : 0;
            $detail_penjualan->nilai_diskon_per_baris = $request->input('nilai_diskon_per_baris')[$i] ? $request->input('nilai_diskon_per_baris')[$i] : 0;
            $detail_penjualan->pajak = $jumlah * $pajak / 100;
            $detail_penjualan->jumlah = $jumlah;
            $detail_penjualan->save();

            $jenis_transaksi = "";

            if($jenis == 'penawaran'){
                $produk_penawaran = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                        ->where('fitur','Produk penawaran')
                                                        ->where('status','active')
                                                        ->first();
                if(isset($produk_penawaran)){
                    $transaksi_produk_penawaran = new Transaksi_produk_penawaran;
                    $transaksi_produk_penawaran->id_company = Auth::user()->id_company;
                    $transaksi_produk_penawaran->id_transaksi = $this->id;
                    $transaksi_produk_penawaran->id_produk = $request->input('produk_penawaran')[$i];

                    $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                    if ($date) {
                        $transaksi_produk_penawaran->tanggal = $date->format('Y-m-d');
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                        $transaksi_produk_penawaran->tanggal = $date ? $date->format('Y-m-d') : null;
                    }

                    $transaksi_produk_penawaran->tipe = $tipe;
                    $transaksi_produk_penawaran->jenis = 'penjualan';
                    $transaksi_produk_penawaran->qty = -$request->input('kuantitas')[$i];
        
                    $produk_penawaran = Produk_penawaran::where('id',$request->input('produk_penawaran')[$i])->first();
                    $transaksi_produk_penawaran->unit = $produk_penawaran->unit;
                    $transaksi_produk_penawaran->save();
    
                    $jenis_transaksi = $transaksi_produk_penawaran->jenis;
                }else{
                    $transaksi_produk = new Transaksi_produk;
                    $transaksi_produk->id_company = Auth::user()->id_company;
                    $transaksi_produk->id_transaksi = $this->id;
                    $transaksi_produk->id_produk = $request->input('produk')[$i];

                    $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                    if ($date) {
                        $transaksi_produk->tanggal = $date->format('Y-m-d');
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                        $transaksi_produk->tanggal = $date ? $date->format('Y-m-d') : null;
                    }

                    $transaksi_produk->tipe = $tipe;
                    $transaksi_produk->jenis = 'penjualan';
                    $transaksi_produk->qty = -$detail_penjualan->kuantitas;

                    $produk = Produk::where('id',$request->input('produk')[$i])->first();
                    $transaksi_produk->unit = $produk->unit;
                    $transaksi_produk->save();

                    $jenis_transaksi = $transaksi_produk->jenis;
                }
            }else{
                $transaksi_produk = new Transaksi_produk;
                $transaksi_produk->id_company = Auth::user()->id_company;
                $transaksi_produk->id_transaksi = $this->id;
                $transaksi_produk->id_produk = $request->input('produk')[$i];
                
                $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                if ($date) {
                    $transaksi_produk->tanggal = $date->format('Y-m-d');
                } else {
                    $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                    $transaksi_produk->tanggal = $date ? $date->format('Y-m-d') : null;
                }

                $transaksi_produk->tipe = $tipe;
                $transaksi_produk->jenis = 'penjualan';
                $transaksi_produk->qty = -$detail_penjualan->kuantitas;

                $produk = Produk::where('id',$request->input('produk')[$i])->first();
                $transaksi_produk->unit = $produk->unit;
                $transaksi_produk->save();

                $jenis_transaksi = $transaksi_produk->jenis;
            }
            if($jenis == 'pengiriman'){
                if(isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                    foreach($gudang as $v){
                        if($request->input('kuantitas_'.$v->id)[$i]){
                            $this->insertStokGudang(
                                $this->id,
                                $detail_penjualan->id,
                                $request->input('produk')[$i],
                                $v->id,
                                $request->input('kuantitas_'.$v->id)[$i],
                                DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d'),
                                $tipe,
                                $jenis_transaksi
                            );
                        }
                    }
                    $this->updateStok($request->input('produk')[$i], $kuantitas);
                }else{
                    $this->insertStokGudang(
                        $this->id,
                        $detail_penjualan->id,
                        $request->input('produk')[$i],
                        $id_gudang,
                        $request->input('kuantitas')[$i],
                        DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d'),
                        $tipe,
                        $jenis_transaksi
                    );
                    $this->updateStok($request->input('produk')[$i], $request->input('kuantitas')[$i]);
                }
            }
        }
    }

    public function updateStok($id_produk, $kuantitas)
    {
        $produk = Produk::find((int)$id_produk);
        $produk->stok = $produk->stok - $kuantitas;
        $produk->save();
    }

    public function insertStokGudang($id_transaksi, $id_detail_transaksi, $produk, $gudang, $kuantitas, $tanggal, $tipe, $jenis)
    {
        $stok_gudang = new Stok_gudang;
        $stok_gudang->id_company = Auth::user()->id_company;
        $stok_gudang->id_transaksi = $id_transaksi;
        $stok_gudang->id_detail_transaksi = $id_detail_transaksi;
        $stok_gudang->id_produk = $produk;
        $stok_gudang->id_gudang = $gudang;
        $stok_gudang->stok = $kuantitas;
        $stok_gudang->tanggal = $tanggal;
        $stok_gudang->tipe = $tipe;
        $stok_gudang->jenis = $jenis;
        $stok_gudang->save();
    }

    public function ubah($request, $jenis = null, $id_jenis=null)
    {
        $this->tanggal_transaksi = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d');
        if($jenis == 'penagihan'){
            $this->no_str = 'Sales Invoice #' . $this->no;
            $tipe = 'Penagihan Penjualan #' . $this->no;
        }else if($jenis == 'pengiriman'){
            $this->no_str = 'Sales Delivery #' . $this->no;
            $tipe = 'Pengiriman Penjualan #' . $this->no;
        }else if($jenis == 'penawaran'){
            $this->no_str = 'Sales Quote #' . $this->no;
            $tipe = 'Penawaran Penjualan #' . $this->no;
        }else if($jenis == 'pemesanan'){
            $this->no_str = 'Sales Order #' . $this->no;
            $tipe = 'Pemesanan Penjualan #' . $this->no;
        }
        
        $this->id_pelanggan = $request->input('pelanggan');
        $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_jatuh_tempo);

        if ($date) {
            $this->tanggal_jatuh_tempo = $date->format('Y-m-d');
        } else {
            $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_jatuh_tempo);
            $this->tanggal_jatuh_tempo = $date ? $date->format('Y-m-d') : null;
        }
        $this->subtotal = $request->input('input_subtotal');
        $this->diskon_per_baris = $request->input('input_diskon_per_baris');
        $this->ppn = $request->input('input_ppn');
        $this->sisa_tagihan = $request->input('input_total') - $this->jumlah_terbayar;
        $this->total = $request->input('input_total');
        $this->alamat = $request->input('alamat');
        $this->detail_alamat = $request->input('detail_alamat');
        $this->email = $request->input('email');
        $this->no_rfq = $request->input('no_rfq');
        if($request->input('pic')){
            $this->pic = $request->input('pic');
        }
        if($this->sisa_tagihan == $this->total){
            $this->status = 'open';
        }elseif($this->sisa_tagihan < $this->total && $this->sisa_tagihan > 0){
            $this->status = 'partial';
        }else{
            $this->status = 'paid';
        }

        if($jenis == 'pemesanan'){
            $penawaran = Penjualan::where('id_pemesanan',$this->id)->where('jenis','penawaran')->first();
            $this->no_rfq = $penawaran->no_rfq;
            $this->pic = $penawaran->pic;

            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
            $this->info_pengiriman = $request->input('info_pengiriman');
            $this->sama_dengan_penagihan = $request->input('sama_dengan_penagihan');
            $this->tanggal_pengiriman = DateTime::createFromFormat('d/m/Y', $request->tanggal_pengiriman)->format('Y-m-d') ? DateTime::createFromFormat('d/m/Y', $request->tanggal_pengiriman)->format('Y-m-d') : null;
            $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
        }else if ($jenis == 'pengiriman'){
            $penawaran = Penjualan::where('id_pemesanan',$this->id_pemesanan)->where('jenis','penawaran')->first();
            $this->no_rfq = $penawaran->no_rfq;
            $this->pic = $penawaran->pic;

            $this->kirim_melalui = $request->input('kirim_melalui') ? $request->input('kirim_melalui') : null;
            $this->no_pelacakan = $request->input('no_pelacakan') ? $request->input('no_pelacakan') : null;
            $this->info_pengiriman = $request->input('info_pengiriman');
            $this->sama_dengan_penagihan = $request->input('sama_dengan_penagihan');
            $this->tanggal_pengiriman = DateTime::createFromFormat('d/m/Y', $request->tanggal_pengiriman)->format('Y-m-d') ? DateTime::createFromFormat('d/m/Y', $request->tanggal_pengiriman)->format('Y-m-d') : null;
            $this->alamat_pengiriman = $request->input('sama_dengan_penagihan') ? $this->alamat : $request->input('alamat_pengiriman');
        }else if ($jenis == 'penagihan'){
            $penawaran = Penjualan::where('id_pemesanan',$this->id_pemesanan)->where('jenis','penawaran')->first();
            $this->no_rfq = $penawaran->no_rfq;
            $this->pic = $penawaran->pic;
        }
        // if($request->input('gudang')){
        //     $gudang = Gudang::find((int)$request->input('gudang'));
        //     $this->id_gudang = $gudang->id;
        //     $this->nama_gudang = $gudang->nama;
        // }else{
        //     $this->id_gudang = null;
        //     $this->nama_gudang = null;
        // }
        $this->ongkos_kirim = $request->input('input_ongkos_kirim') ? $request->input('input_ongkos_kirim') : null;
        $this->pesan = $request->input('pesan') ? $request->input('pesan') : null;
        $this->memo = $request->input('memo') ? $request->input('memo') : null;
        $this->save();
        if($jenis == 'pemesanan' && isset($_POST['id_dokumen']) && $request->file($_POST['id_dokumen'][0])){
            Dokumen_penjualan::where('id_pemesanan',$this->id)->delete();
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_pemesanan = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }
        if($jenis == 'pengiriman' && isset($_POST['id_dokumen']) && $request->file($_POST['id_dokumen'][0])){
            Dokumen_penjualan::where('id_pengiriman',$this->id)->delete();
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_pengiriman = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }

        if($jenis == 'penagihan' && isset($_POST['id_dokumen']) && $request->file($_POST['id_dokumen'])){
            Dokumen_penjualan::where('id_penagihan',$this->id)->delete();
            for($i = 0; $i < count($_POST['id_dokumen']) ; $i++ ){
                if($request->file($_POST['id_dokumen'][$i])){
                    $fileName = $request->file($_POST['id_dokumen'][$i])->getClientOriginalName();
                    $uniqueFileName = time() . '.' . $fileName;
    
                    $filePath = $request->file($_POST['id_dokumen'][$i])->storeAs('uploads', $uniqueFileName, 'public');
                    $dokumen_penjualan = new Dokumen_penjualan();
                    $dokumen_penjualan->id_company = Auth::user()->id_company;
                    $dokumen_penjualan->id_penagihan = $this->id;
                    $dokumen_penjualan->id_dokumen =$_POST['id_dokumen'][$i];
                    $dokumen_penjualan->tanggal_upload = date('Y-m-d');
                    $dokumen_penjualan->nama = $uniqueFileName;
                    $dokumen_penjualan->save();
                }
            }
        }
        
        $this->editDetailPenjualan($request, $tipe, $jenis,$this->id_gudang);

        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->id_transaksi = $this->id;
        $log->transaksi = 'penjualan';
        $log->aksi = 'edit';
        $log->save();
    }

    protected function editDetailPenjualan(Request $request, $tipe, $jenis = null, $id_gudang)
    {
        $index = $request->input('produk') ? $request->input('produk') : $request->input('produk_penawaran');

        $multiple_gudang = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                    ->where('fitur','Multiple gudang')
                                                    ->where('status','active')
                                                    ->first();

        $gudang = Gudang::where('id_company',Auth::user()->id_company)->get();

        Transaksi_produk_penawaran::where('id_transaksi',$this->id)->delete();
        Transaksi_produk::where('id_transaksi',$this->id)->delete();
        Stok_gudang::where('id_transaksi',$this->id)->delete();

        $detail_penjualan = Detail_penjualan::where('id_penjualan',$this->id);
        foreach($detail_penjualan->get() as $v){
            if($jenis != 'penawaran'){
                $produk = Produk::find($v->id_produk);
                $produk->stok = $produk->stok + $v->kuantitas;
                $produk->save();
            }
        }
        $detail_penjualan->delete();

        for ($i = 0; $i < count($index); $i++) {
            $kuantitas = 0;
            $harga_satuan = $request->input('harga_satuan')[$i] != '' || $request->input('harga_satuan')[$i] != null ? number_format((float)str_replace(",", "", $_POST['harga_satuan'][$i]), 2, '.', '') : 0;
            $jumlah = $request->input('jumlah')[$i] != '' || $request->input('jumlah')[$i] != null ? number_format((float)str_replace(",", "", $_POST['jumlah'][$i]), 2, '.', '') : 0;
            $pajak = $request->input('pajak')[$i] != '' || $request->input('pajak')[$i] != null ? number_format((float)str_replace(",", "", $_POST['pajak'][$i]), 2, '.', '') : 0;

            $detail_penjualan = new Detail_penjualan;
            $detail_penjualan->id_company = Auth::user()->id_company;
            $detail_penjualan->id_penjualan = $this->id;
            if($request->input('produk')){
                $detail_penjualan->id_produk = $request->input('produk')[$i];
            }
            if($request->input('produk_penawaran')){
                $detail_penjualan->id_produk_penawaran = $request->input('produk_penawaran')[$i];
            }
            $detail_penjualan->deskripsi = $request->input('deskripsi')[$i];

            if($jenis == 'pengiriman' && isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                foreach($gudang as $v){
                    $kuantitas += $request->input('kuantitas_'.$v->id)[$i];
                }
                $detail_penjualan->kuantitas = $kuantitas;
            }else{
                $detail_penjualan->kuantitas = $request->input('kuantitas')[$i];
            }
            $detail_penjualan->harga_satuan = $harga_satuan;
            $detail_penjualan->diskon_per_baris = $request->input('diskon_per_baris')[$i] ? $request->input('diskon_per_baris')[$i] : 0;
            $detail_penjualan->nilai_diskon_per_baris = $request->input('nilai_diskon_per_baris')[$i] ? $request->input('nilai_diskon_per_baris')[$i] : 0;
            $detail_penjualan->pajak = $jumlah * $pajak / 100;
            $detail_penjualan->jumlah = $jumlah;

            if($jenis == 'pengiriman'){
                if(isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                    $this->updateStok($request->input('produk')[$i], $kuantitas, 'update', $this->id);
                }else{
                    $this->updateStok($request->input('produk')[$i], $request->input('kuantitas')[$i], 'update', $this->id);
                }
            }

            $detail_penjualan->save();

            $jenis_transaksi = "";

            if($jenis == 'penawaran'){
                $produk_penawaran = Pengaturan_produk::where('id_company',Auth::user()->id_company)
                                                        ->where('fitur','Produk penawaran')
                                                        ->where('status','active')
                                                        ->first();
                if(isset($produk_penawaran)){                    
                    $transaksi_produk_penawaran = new Transaksi_produk_penawaran;
                    $transaksi_produk_penawaran->id_company = Auth::user()->id_company;
                    $transaksi_produk_penawaran->id_transaksi = $this->id;
                    $transaksi_produk_penawaran->id_produk = $request->input('produk_penawaran')[$i];

                    $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                    if ($date) {
                        $transaksi_produk_penawaran->tanggal = $date->format('Y-m-d');
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                        $transaksi_produk_penawaran->tanggal = $date ? $date->format('Y-m-d') : null;
                    }

                    $transaksi_produk_penawaran->tipe = $tipe;
                    $transaksi_produk_penawaran->jenis = 'penjualan';
                    $transaksi_produk_penawaran->qty = -$request->input('kuantitas')[$i];
        
                    $produk_penawaran = Produk_penawaran::where('id',$request->input('produk_penawaran')[$i])->first();
                    $transaksi_produk_penawaran->unit = $produk_penawaran->unit;
                    $transaksi_produk_penawaran->save();
    
                    $jenis_transaksi = $transaksi_produk_penawaran->jenis;
                }else{
                    $transaksi_produk = new Transaksi_produk;
                    $transaksi_produk->id_company = Auth::user()->id_company;
                    $transaksi_produk->id_transaksi = $this->id;
                    $transaksi_produk->id_produk = $request->input('produk')[$i];

                    $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                    if ($date) {
                        $transaksi_produk->tanggal = $date->format('Y-m-d');
                    } else {
                        $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                        $transaksi_produk->tanggal = $date ? $date->format('Y-m-d') : null;
                    }

                    $transaksi_produk->tipe = $tipe;
                    $transaksi_produk->jenis = 'penjualan';
                    $transaksi_produk->qty = -$detail_penjualan->kuantitas;

                    $produk = Produk::where('id',$request->input('produk')[$i])->first();
                    $transaksi_produk->unit = $produk->unit;
                    $transaksi_produk->save();

                    $jenis_transaksi = $transaksi_produk->jenis;
                }
            }else{
                $transaksi_produk = new Transaksi_produk;
                $transaksi_produk->id_company = Auth::user()->id_company;
                $transaksi_produk->id_transaksi = $this->id;
                $transaksi_produk->id_produk = $request->input('produk')[$i];

                $date = DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi);

                if ($date) {
                    $transaksi_produk->tanggal = $date->format('Y-m-d');
                } else {
                    $date = DateTime::createFromFormat('Y-m-d', $request->tanggal_transaksi);
                    $transaksi_produk->tanggal = $date ? $date->format('Y-m-d') : null;
                }

                $transaksi_produk->tipe = $tipe;
                $transaksi_produk->jenis = 'penjualan';
                $transaksi_produk->qty = -$detail_penjualan->kuantitas;

                $produk = Produk::where('id',$request->input('produk')[$i])->first();
                $transaksi_produk->unit = $produk->unit;
                $transaksi_produk->save();
            }
            if($jenis == 'pengiriman'){
                if(isset($multiple_gudang) && $multiple_gudang && $gudang->count() > 0){
                    foreach($gudang as $v){
                        if($request->input('kuantitas_'.$v->id)[$i]){
                            $this->updateStokGudang(
                                $this->id,
                                $detail_penjualan->id,
                                $request->input('produk')[$i],
                                $v->id,
                                $request->input('kuantitas_'.$v->id)[$i],
                                DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d'),
                                $tipe,
                                $jenis_transaksi
                            );
                        }
                    }
                    $this->updateStok($request->input('produk')[$i], $kuantitas);
                }else{
                    $this->updateStokGudang(
                        $this->id,
                        $detail_penjualan->id,
                        $request->input('produk')[$i],
                        $id_gudang,
                        $request->input('kuantitas')[$i],
                        DateTime::createFromFormat('d/m/Y', $request->tanggal_transaksi)->format('Y-m-d'),
                        $tipe,
                        $jenis_transaksi
                    );
                    $this->updateStok($request->input('produk')[$i], $request->input('kuantitas')[$i]);
                }
            }
        }
    }

    public function updateStokGudang($id_transaksi, $id_detail_transaksi, $produk, $gudang, $kuantitas, $tanggal, $tipe, $jenis)
    {
        $stok_gudang = new Stok_gudang;
        $stok_gudang->id_company = Auth::user()->id_company;
        $stok_gudang->id_transaksi = $id_transaksi;
        $stok_gudang->id_detail_transaksi = $id_detail_transaksi;
        $stok_gudang->id_produk = $produk;
        $stok_gudang->id_gudang = $gudang;
        // if($jenis == 'pemesanan'){
            $stok_gudang->stok = $kuantitas;
        // }else{
            // $stok_gudang->stok = $stok_gudang->stok - $kuantitas;
        // }
        $stok_gudang->tanggal = $tanggal;
        $stok_gudang->tipe = $tipe;
        $stok_gudang->jenis = $jenis;
        $stok_gudang->save();
    }

    public function selesai($id)
    {
        $penjualan = Penjualan::find($id);
        
        $no = $this->no('selesai');
        $selesai = $penjualan->replicate()->fill([
            'id_penagihan' => $id, 
            'id_jurnal' => null,
            'tanggal_transaksi' => date('Y-m-d'),
            'no' => $no,
            'no_str' => 'Sales Finish #' .$no,
            'tanggal_jatuh_tempo' => null,
            'status' => 'open',
            'jenis' => 'selesai',
        ]);
        $selesai->save();

        $penjualan->selesai = 'selesai';
        $penjualan->save();

        Dokumen_penjualan::where('id_pemesanan',$penjualan->id_pemesanan)->update(['id_penjualan' => $selesai->id]);
        Dokumen_penjualan::where('id_pengiriman',$penjualan->id_pengiriman)->update(['id_penjualan' => $selesai->id]);
        Dokumen_penjualan::where('id_penagihan',$id)->update(['id_penjualan' => $selesai->id]);
    }
}
