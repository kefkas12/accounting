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
                            <div class="col-sm-6">Detail gudang</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <strong>
                                @if (isset($gudang))
                                    {{ $gudang->nama }}
                                @else
                                    -
                                @endif
                                </strong>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('gudang/edit').'/'.$gudang->id }}" class="btn btn-outline-primary">Ubah</a>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4"><i class="fa fa-store text-primary mr-4"></i><strong>Info gudang</strong></div>
                            <div class="col-sm-4">
                                
                            </div>  
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Kode gudang</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->kode != null)
                                    {{ $gudang->kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Alamat</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->alamat != null)
                                    {{ $gudang->alamat }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Keterangan</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->keterangan != null)
                                    {{ $gudang->keterangan }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-produk-tab" data-toggle="tab" data-target="#nav-produk"
                                    type="button" role="tab" aria-controls="nav-produk"
                                    aria-selected="true">Daftar produk</button>
                                <button class="nav-link" id="nav-transaksi-tab" data-toggle="tab" data-target="#nav-transaksi"
                                    type="button" role="tab" aria-controls="nav-transaksi"
                                    aria-selected="false">Daftar transaksi</button>
                            </div>
                        </nav>
                        <div class="tab-content mt-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-produk" role="tabpanel"
                                aria-labelledby="nav-produk-tab">
                                <div hidden class="row mb-3" >
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-success">
                                            <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB">
                                                Stok Tersedia
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-warning">
                                            <div class="card-header border-warning" style="padding: 0.5rem 0.75rem !important; background:#FBF3DD">
                                                Stok segera habis
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-danger">
                                            <div class="card-header border-danger" style="padding: 0.5rem 0.75rem !important; background:#FDECEE">
                                                Stok habis
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-primary">
                                            <div class="card-header border-primary" style="padding: 0.5rem 0.75rem !important;">
                                                Gudang
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Terdaftar<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div hidden style="overflow: auto">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nama produk</th>
                                                <th>barcode produk</th>
                                                <th>Kode produk</th>
                                                <th>Kategori produk</th>
                                                <th>Total stok</th>
                                                <th>Batas minimum</th>
                                                <th>Unit</th>
                                                <th>Harga rata-rata</th>
                                                <th>Harga beli terakhir</th>
                                                <th>Harga beli</th>
                                                <th>Harga jual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-transaksi" role="tabpanel"
                                aria-labelledby="nav-transaksi-tab">
                                <div hidden class="row mb-3" >
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-success">
                                            <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB;">
                                                Stok Tersedia
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-warning">
                                            <div class="card-header border-warning" style="padding: 0.5rem 0.75rem !important; background:#FBF3DD;">
                                                Stok segera habis
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-danger">
                                            <div class="card-header border-danger" style="padding: 0.5rem 0.75rem !important; background:#FDECEE;">
                                                Stok habis
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Total produk<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" style="padding-right: 0px !important;">
                                        <div class="card border-primary">
                                            <div class="card-header border-primary" style="padding: 0.5rem 0.75rem !important;">
                                                Gudang
                                            </div>
                                            <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                                Terdaftar<br> <span style="font-weight:900">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="overflow: auto">
                                    <table id="table_gudang" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Kode gudang</th>
                                                <th>Nama gudang</th>
                                                <th>Alamat</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
