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
                            <div class="col-sm-6">Informasi Produk</div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('produk/edit').'/'.$produk->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('produk/hapus').'/'.$produk->id }}" class="btn btn-outline-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        Informasi Produk
                        <div class="row my-4">
                            <div class="col-sm-2">Nama</div>
                            <div class="col-sm-4">
                                @if (isset($produk))
                                    {{ $produk->nama }}
                                @else
                                    -
                                @endif
                            </div>  
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Kode</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->kode != null)
                                    {{ $produk->kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Kategori</div>
                            <div class="col-sm-4">
                                @if (isset($produk) && $produk->kategori != null)
                                    {{ $produk->kategori }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
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
                        <hr>
                        <div class="row my-4">
                            <div class="col-sm-2">Info Pembelian</div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">Info Penjualan</div>
                            <div class="col-sm-4"></div>
                        </div>
                        <div class="row my-4">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
