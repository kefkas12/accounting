@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">
                            <div class="col-sm-6">
                                <h2>
                                    <small style="display: block;">Akun - {{ $akun->nama_kategori }}</small>
                                     ({{ $akun->nomor }}) {{ $akun->nama }}
                                </h2>
                            </div>
                            <div class="col d-flex justify-content-end ">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">+ Buat Transaksi</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-capitalize" href="{{ url('kas_bank/transfer_uang') }}">Transfer Uang</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('kas_bank/terima_uang') }}">Terima Uang</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('kas_bank/kirim_uang') }}">Kirim Uang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        <table class="table my-4">
                                <thead style="background-color: #E0F7FF">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kontak</th>
                                        <th>Deskripsi</th>
                                        <th class="text-right">Terima (in IDR)</th>
                                        <th class="text-right">Kirim (in IDR)</th>
                                        <th class="text-right">Saldo (in IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $saldo = 0;
                                    @endphp
                                    @foreach($detail_jurnal as $v)
                                        @php
                                            $saldo = $saldo + $v->debit - $v->kredit;
                                        @endphp
                                        <tr>
                                            <td>{{ $v->jurnal->tanggal_transaksi }}</td>
                                            <td>
                                                @if($v->jurnal->pembayaranPenjualan != null)
                                                    <a href="{{ url('pelanggan/detail').'/'.$v->jurnal->pembayaranPenjualan->detail_pembayaran_penjualan[0]->penjualan->pelanggan->id }}">{{ $v->jurnal->pembayaranPenjualan->detail_pembayaran_penjualan[0]->penjualan->pelanggan->nama }}</a>
                                                @elseif($v->jurnal->pembayaranPembelian != null)
                                                    <a href="{{ url('supplier/detail').'/'.$v->jurnal->pembayaranPembelian->detail_pembayaran_pembelian[0]->pembelian->supplier->id }}">{{ $v->jurnal->pembayaranPembelian->detail_pembayaran_pembelian[0]->pembelian->supplier->nama }}</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($v->jurnal->kategori == 'sales_invoice')
                                                <a href="{{ url('penjualan/detail').'/'.$v->jurnal->pembayaranPenjualan->detail_pembayaran_penjualan[0]->penjualan->id }}">{{ $v->jurnal->no_str }}</a>
                                                @elseif($v->jurnal->kategori == 'purchase_invoice')
                                                <a href="{{ url('pembelian/detail').'/'.$v->jurnal->pembayaranPembelian->detail_pembayaran_pembelian[0]->pembelian->id   }}">{{ $v->jurnal->no_str }}</a>
                                                @elseif($v->jurnal->kategori == 'receive_payment')
                                                <a href="{{ url('penjualan/receive_payment').'/'.$v->id_pembayaran_penjualan }}">{{ $v->jurnal->no_str }}</a>
                                                @elseif($v->jurnal->kategori == 'purchase_payment')
                                                <a href="{{ url('pembelian/receive_payment').'/'.$v->id_pembayaran_pembelian }}">{{ $v->jurnal->no_str }}</a>
                                                @elseif($v->jurnal->kategori == 'bank_transfer')
                                                <a href="{{ url('kas_bank/transfer_uang/detail').'/'.$v->jurnal->transferUang->id }}">{{ $v->jurnal->transferUang->no_str }}</a>
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($v->debit,2,',','.') }}</td>
                                            <td class="text-right">{{ number_format($v->kredit,2,',','.') }}</td>
                                            <td class="text-right">{{ number_format($saldo,2,',','.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
