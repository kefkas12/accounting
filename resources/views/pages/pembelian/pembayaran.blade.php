@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        Pengiriman Pembayaran
                    </div>
                    <div class="card-body " style="font-size: 12px;">
                        <div class="row">
                            <div class="col-sm-2">Supplier</div>
                            <div class="col-sm-2"><strong>{{ $pembelian->nama_supplier }}</strong></div>
                            <div class="col-sm-2">Bayar Dari</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4 d-flex justify-content-end"><h3>Total Rp. {{ number_format($pembelian->sisa_tagihan,2,',','.') }}</h3></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-2">Cara Pembayaran</div>
                            <div class="col-sm-2"><strong>{{ $pembelian->alamat }}</strong></div>
                            <div class="col-sm-2">Tgl. Pembayaran</div>
                            <div class="col-sm-2"><strong>{{ date('d/m/Y',strtotime($pembelian->tanggal_transaksi)) }}</strong></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">No Transaksi </div>
                            <div class="col-sm-2"><strong>{{ $pembelian->no_str }}</strong></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table my-4" >
                                <thead class="thead-light">
                                <tr>
                                    <th>Number</th>
                                    <th>Deskripsi</th>
                                    <th>Tgl Jatuh Tempo</th>
                                    <th>Total</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Jumlah</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian->detail_pembelian as $v)
                                    <tr>
                                        <th>{{ $v->produk->nama }}</th>
                                        <td>{{ $v->kuantitas }}</td>
                                        <td>Buah</td>
                                        <td>Rp. {{ number_format($v->harga_satuan,2,',','.') }}</td>
                                        <td>PPN</td>
                                        <td>Rp. {{ number_format($v->jumlah,2,',','.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table my-4" hidden>
                                <thead class="thead-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kuantitas</th>
                                    <th>Unit</th>
                                    <th>Harga Satuan</th>
                                    <th>Pajak</th>
                                    <th>Jumlah</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($pembelian->detail_pembelian as $v)
                                    <tr>
                                        <th>{{ $v->produk->nama }}</th>
                                        <td>{{ $v->kuantitas }}</td>
                                        <td>Buah</td>
                                        <td>Rp. {{ number_format($v->harga_satuan,2,',','.') }}</td>
                                        <td>PPN</td>
                                        <td>Rp. {{ number_format($v->jumlah,2,',','.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-7"></div>
                            <div class="col-sm-5">
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Subtotal</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->subtotal,2,',','.') }}</h4>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>PPN 11%</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->ppn,2,',','.') }}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Total</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->total,2,',','.') }}</h4>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h2>Sisa Tagihan</h2>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h2>Rp. {{ number_format($pembelian->sisa_tagihan,2,',','.') }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-secondary ">Hapus</button>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <button class="btn btn-outline-primary">Ubah</button>
                                <button class="btn btn-primary">Kirim Pembayaran</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
