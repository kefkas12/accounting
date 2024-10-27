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
                            <div class="col-sm-6">Detail Produk</div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('produk/edit').'/'.$produk->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('produk/hapus').'/'.$produk->id }}" class="btn btn-outline-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        <div class="row mb-4">
                            <div class="col-sm-12"><h3>{{ $produk->nama }}</h3></div>
                            <div class="col-sm-12"><small>Data di bawah berdasarkan tanggal {{ $produk->updated_at }}, kecuali ada pernyataan lain</small></div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-12 mb-2" ><h4>Info Produk</h4></div>
                            <div class="col-sm-2">Kode produk</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->kode != null)
                                    {{ $produk->kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @if (isset($produk) && $produk->batas_stok_minimum != null)
                        <div class="row my-4">
                            <div class="col-sm-2">Harga rata-rata</div>
                            <div class="col-sm-4">
                                Rp {{ $produk->harga_beli }}
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Stok di gudang</div>
                            <div class="col-sm-4">
                                {{ $produk->stok }} {{ $produk->unit }}
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Batas stok minimum</div>
                            <div class="col-sm-4">
                                {{ $produk->batas_stok_minimum }} {{ $produk->unit }}
                            </div>
                        </div>
                        @endif
                        <div class="row my-4">
                            <div class="col-sm-2">Kategori produk</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->kategori != null)
                                    {{ $produk->kategori }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Deskripsi</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->deskripsi != null)
                                    {{ $produk->deskripsi }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        @if (isset($produk) && $produk->batas_stok_minimum == null)
                        <div class="row my-4">
                            <div class="col-sm-2">Stok</div>
                            <div class="col-sm-4">
                                @if (isset($produk) )
                                    {{ $produk->stok }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Unit</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->unit != null)
                                    {{ $produk->unit }}
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
                                @if (isset($produk) && $produk->harga_beli != '')
                                    Rp {{ number_format($produk->harga_beli,2,',','.') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Harga Jual</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->harga_jual != null)
                                    Rp {{ number_format($produk->harga_jual,2,',','.') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <nav>
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
                            <div class="tab-pane fade" id="nav-info-gudang" role="tabpanel"
                                aria-labelledby="nav-info-gudang-tab">
                                <div style="overflow: auto">
                                    <table id="table_gudang" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
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
