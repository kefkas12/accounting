@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="d-flex bd-highlight">
                            <div class="mr-auto p-2 bd-highlight border-bottom border-primary">
                                <h2>Detail Produk Penawaran</h2>
                            </div>
                            <div class="p-2 bd-highlight">
                                @hasanyrole('Admin Marketing/sales')
                                @else
                                <a href="{{ url('produk_penawaran/edit').'/'.$produk_penawaran->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('produk_penawaran/hapus').'/'.$produk_penawaran->id }}" class="btn btn-outline-danger">Hapus</a>
                                @endhasanyrole
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <h4>Info Produk</h4>
                            </div>
                        </div>
                        <div class="row" style="font-size: 12px;">
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="mr-auto p-2 bd-highlight">
                                        Nama produk penawaran
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran) && $produk_penawaran->nama != null)
                                        {{ $produk_penawaran->nama }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="mr-auto p-2 bd-highlight">
                                        Kode produk penawaran
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran) && $produk_penawaran->kode != null)
                                        {{ $produk_penawaran->kode }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="mr-auto p-2 bd-highlight">
                                        Deskripsi produk penawaran
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran) && $produk_penawaran->deskripsi != null)
                                        {{ $produk_penawaran->deskripsi }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex">
                                    <div class="mr-auto p-2 bd-highlight">
                                        Kategori produk penawaran
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran) && $produk_penawaran->kategori != null)
                                        {{ $produk_penawaran->kategori }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            
                            @if (isset($produk_penawaran) && $produk_penawaran->batas_stok_minimum == null)
                            <div class="col-sm-6">
                                <div class="d-flex" >
                                    <div class="mr-auto p-2 bd-highlight">
                                        Stok
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran) && $produk_penawaran->stok != null)
                                        {{ $produk_penawaran->stok }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex" >
                                    <div class="mr-auto p-2 bd-highlight">
                                        Unit
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <b>
                                        @if (isset($produk_penawaran))
                                        {{ $produk_penawaran->unit }}
                                        @else
                                        -
                                        @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div style="overflow: auto">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi_produk_penawaran as $v)
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
