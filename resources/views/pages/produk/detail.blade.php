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
                            <h2>Detail Produk</h2>
                        </div>
                        <div class="p-2 bd-highlight">
                            @hasanyrole('Admin Marketing/sales')
                            @else
                            <a href="{{ url('produk/edit').'/'.$produk->id }}" class="btn btn-outline-primary">Ubah</a>
                            <a href="{{ url('produk/hapus').'/'.$produk->id }}" class="btn btn-outline-danger">Hapus</a>
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
                                    Nama produk
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->nama != null)
                                    {{ $produk->nama }}
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
                                    Nama Produk Penawaran
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->nama_produk_penawaran != null)
                                    <a href="{{ url('produk_penawaran/detail').'/'.$produk->id_produk_penawaran }}">
                                        {{ $produk->nama_produk_penawaran }}
                                    </a>
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
                                    Kode Produk
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->kode != null)
                                    {{ $produk->kode }}
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
                                    Deskripsi Produk
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->deskripsi != null)
                                    {{ $produk->deskripsi }}
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
                                    Kategori Produk
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->kategori != null)
                                    {{ $produk->kategori }}
                                    @else
                                    -
                                    @endif
                                    </b>
                                </div>
                            </div>
                        </div>
                        
                        @if (isset($produk) && $produk->batas_stok_minimum == null)
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Stok
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->stok != null)
                                    {{ $produk->stok }}
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
                                    @if (isset($produk))
                                    {{ $produk->unit }}
                                    @else
                                    -
                                    @endif
                                    </b>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Harga Rata-Rata
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    Rp {{ $produk->harga_beli }}
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Stok di Gudang
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    {{ $produk->stok }} {{ $produk->unit }}
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Batas Stok Minimum
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                        {{ $produk->batas_stok_minimum }} {{ $produk->unit }}
                                    </b>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row mt-3" style="font-size: 12px;">
                        <div class="col-sm-12">
                            <h4>Info Pembelian</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Harga Beli
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                        @if (isset($produk) && $produk->harga_beli != '')
                                        Rp {{ number_format($produk->harga_beli,2,',','.') }}
                                        @else
                                        -
                                        @endif
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" style="font-size: 12px;">
                        <div class="col-sm-12">
                            <h4>Info Penjualan</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex" >
                                <div class="mr-auto p-2 bd-highlight">
                                    Harga Jual
                                </div>
                                <div class="p-2 bd-highlight">
                                    <b>
                                    @if (isset($produk) && $produk->harga_jual != '')
                                    Rp {{ number_format($produk->harga_jual,2,',','.') }}
                                    @else
                                    -
                                    @endif
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class="mt-3">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-transaksi-produk-tab" data-toggle="tab" data-target="#nav-transaksi-produk"
                                type="button" role="tab" aria-controls="nav-transaksi-produk"
                                aria-selected="true">Transaksi produk</button>
                            <button class="nav-link" id="nav-info-gudang-tab" data-toggle="tab" data-target="#nav-info-gudang"
                                type="button" role="tab" aria-controls="nav-info-gudang"
                                aria-selected="false">Info Gudang</button>
                        </div>
                    </nav>
                    <div class="tab-content mt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-transaksi-produk" role="tabpanel"
                            aria-labelledby="nav-transaksi-produk-tab">
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
                        </div>
                        <div class="tab-pane fade" id="nav-info-gudang" role="tabpanel"
                            aria-labelledby="nav-info-gudang-tab">
                            <div style="overflow: auto">
                                <table id="table_gudang" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Nama gudang</th>
                                            <th>Qty tersedia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Unassigned</td>
                                            <td>@if(isset($stok_gudang->stok)){{ $stok_gudang->stok }} @else 0 @endif</td>
                                        </tr>
                                        @foreach($gudang as $v)
                                        <tr>
                                            <td>{{ $v->nama }}</td>
                                            <td>{{ $v->stok ? $v->stok : 0 }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection