@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">
                        <div class="row">
                            <div class="col">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Tindakan</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('produk/insert') }}">Tambah produk baru</a>
                                        <a class="dropdown-item" href="{{ url('gudang/insert') }}">Tambah gudang baru</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-barang-dan-jasa-tab" data-toggle="tab" data-target="#nav-barang-dan-jasa"
                                    type="button" role="tab" aria-controls="nav-barang-dan-jasa"
                                    aria-selected="true">Barang & jasa</button>
                                <button class="nav-link" id="nav-gudang-tab" data-toggle="tab" data-target="#nav-gudang"
                                    type="button" role="tab" aria-controls="nav-gudang"
                                    aria-selected="false">Gudang</button>
                            </div>
                        </nav>
                        <div class="tab-content mt-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-barang-dan-jasa" role="tabpanel"
                                aria-labelledby="nav-barang-dan-jasa-tab">
                                <div class="row mb-3" >
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
                                <div style="overflow: auto">
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
                                            @foreach ($produk as $v)
                                                <tr>
                                                    <td><a href="{{ url('produk/detail') . '/' . $v->id }}">{{ $v->nama }}</a>
                                                    </td>
                                                    <td>
                                                        {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($v->id, 'C39') }}" alt="" srcset=""> --}}
                                                        <a class="btn btn-sm btn-primary" download="barcode_{{ $v->nama }}.png" href="data:image/png;base64,{{ DNS1D::getBarcodePNG($v->id, 'C39+',5,55) }}">Generate</a>
                                                    </td>
                                                    <td>{{ $v->kode }}</td>
                                                    <td>{{ $v->kategori }}</td>
                                                    <td>{{ $v->stok }}</td>
                                                    <td>{{ $v->batas_stok_minimum }}</td>
                                                    <td>{{ $v->unit }}</td>
                                                    <td>@if($v->harga_rata_rata) {{ number_format($v->harga_rata_rata, 2, ',', '.') }} @else 0,00 @endif</td>
                                                    <td>@if($v->harga_beli_terakhir) {{ number_format($v->harga_beli_terakhir, 2, ',', '.') }} @else 0,00 @endif</td>
                                                    <td>@if($v->harga_beli) {{ number_format($v->harga_beli, 2, ',', '.') }} @else 0,00 @endif</td>
                                                    <td>@if($v->harga_jual) {{ number_format($v->harga_jual, 2, ',', '.') }} @else 0,00 @endif</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-gudang" role="tabpanel"
                                aria-labelledby="nav-gudang-tab">
                                <div class="row mb-3" >
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
                                            @foreach ($gudang as $v)
                                                <tr>
                                                    <td>{{ $v->kode }}</td>
                                                    <td><a href="{{ url('gudang/detail') . '/' . $v->id }}">{{ $v->nama }}</a>
                                                    </td>
                                                    <td>{{ $v->alamat }}</td>
                                                    <td>{{ $v->keterangan }}</td>
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                columnDefs: [{
                    type: 'formatted-num',
                    targets: [5, 6]
                }],
                language: {
                    thousands: ".",
                    decimal: ","
                }
            })
            $('#table_gudang').DataTable()
        });

        $.fn.dataTable.ext.type.order['formatted-num-pre'] = function(data) {
            var numeric = data.replace('.', '').replace(',', '.');
            return parseFloat(numeric);
        };
    </script>
@endsection
