@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row">
                            <div class="col-sm-6">Detail Produk Penawaran</div>
                            @hasanyrole('Admin Marketing/sales')
                            @else
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('produk_penawaran/edit').'/'.$produk_penawaran->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('produk_penawaran/hapus').'/'.$produk_penawaran->id }}" class="btn btn-outline-danger">Hapus</a>
                            </div>
                            @endhasanyrole
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        <div class="row mb-4">
                            <div class="col-sm-12"><h3>{{ $produk_penawaran->nama }}</h3></div>
                            <div class="col-sm-12"><small>Data di bawah berdasarkan tanggal {{ $produk_penawaran->updated_at }}, kecuali ada pernyataan lain</small></div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-12 mb-2" ><h4>Info Produk Penawaran</h4></div>
                            <div class="col-sm-2">Kode produk</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->kode != null)
                                    {{ $produk_penawaran->kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @if (isset($produk_penawaran) && $produk_penawaran->batas_stok_minimum != null)
                        <div class="row my-4">
                            <div class="col-sm-2">Harga rata-rata</div>
                            <div class="col-sm-4">
                                Rp {{ $produk_penawaran->harga_beli }}
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Stok di gudang</div>
                            <div class="col-sm-4">
                                {{ $produk_penawaran->stok }} {{ $produk_penawaran->unit }}
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Batas stok minimum</div>
                            <div class="col-sm-4">
                                {{ $produk_penawaran->batas_stok_minimum }} {{ $produk_penawaran->unit }}
                            </div>
                        </div>
                        @endif
                        <div class="row my-4">
                            <div class="col-sm-2">Kategori produk</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->kategori != null)
                                    {{ $produk_penawaran->kategori }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Deskripsi</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->deskripsi != null)
                                    {{ $produk_penawaran->deskripsi }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @if (isset($produk_penawaran) && $produk_penawaran->batas_stok_minimum == null)
                        <div class="row my-4">
                            <div class="col-sm-2">Stok</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) )
                                    {{ $produk_penawaran->stok }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Unit</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->unit != null)
                                    {{ $produk_penawaran->unit }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-sm-2"><h4>Info Pembelian</h4></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2"><h4>Info Penjualan</h4></div>
                            <div class="col-sm-4"></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-2">Harga Beli</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->harga_beli != '')
                                    Rp {{ number_format($produk_penawaran->harga_beli,2,',','.') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Harga Jual</div>
                            <div class="col-sm-4">
                                @if (isset($produk_penawaran) && $produk_penawaran->harga_jual != null)
                                    Rp {{ number_format($produk_penawaran->harga_jual,2,',','.') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div style="overflow: auto">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi_produk as $v)
                                    <tr>
                                        <th>{{ $v->tanggal }}</th>
                                        <th><a href="@if($v->jenis == 'pembelian'){{ url('pembelian/detail').'/'.$v->id_transaksi }}@elseif($v->jenis == 'penjualan'){{ url('penjualan/detail').'/'.$v->id_transaksi }}@endif">{{ $v->tipe }}</a></th>
                                        <th>{{ $v->qty }} {{ $v->unit }}</th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
