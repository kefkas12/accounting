@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0" style="padding: 1rem 0.5rem">
                        <div class="row mb-3">
                            <div class="col">
                                <b>Penjualan</b>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat penjualan baru</button>
                                    <div class="dropdown-menu">
                                      <a class="dropdown-item" href="{{ url('penjualan/penagihan') }}">Penagihan Penjualan</a>
                                      <a class="dropdown-item" href="{{ url('penjualan/pemesanan') }}">Pemesanan Penjualan</a>
                                      <a class="dropdown-item" href="{{ url('penjualan/penawaran') }}">Penawaran Penjualan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-warning">
                                    <div class="card-header border-warning" style="padding: 0.5rem 0.75rem !important;background:#FBF3DD;">
                                        Belum dibayar
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp {{ $belum_dibayar }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-danger">
                                    <div class="card-header border-danger" style="padding: 0.5rem 0.75rem !important; background:#FDECEE;">
                                        Jatuh tempo
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. 0,00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-success">
                                    <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB;">
                                        Pelunasan 30 hari terakhir
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. 0,00</span>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                            </div>
                        </div>
                    </div>
                    <div class='container-fluid' style="padding-left: 1.45rem !important;">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-penagihan-tab" data-toggle="tab" data-target="#nav-penagihan"
                                    type="button" role="tab" aria-controls="nav-penagihan"
                                    aria-selected="true">Penagihan</button>
                                <button class="nav-link" id="nav-pengiriman-tab" data-toggle="tab" data-target="#nav-pengiriman"
                                    type="button" role="tab" aria-controls="nav-pengiriman"
                                    aria-selected="true">Pengiriman</button>
                                <button class="nav-link" id="nav-pesanan-tab" data-toggle="tab" data-target="#nav-pesanan"
                                    type="button" role="tab" aria-controls="nav-pesanan"
                                    aria-selected="false">Pesanan</button>
                                <button class="nav-link" id="nav-penawaran-tab" data-toggle="tab" data-target="#nav-penawaran"
                                    type="button" role="tab" aria-controls="nav-penawaran"
                                    aria-selected="false">Penawaran</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-penagihan" role="tabpanel"
                                aria-labelledby="nav-penagihan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penagihan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pengiriman" role="tabpanel"
                                aria-labelledby="nav-pengiriman-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pengiriman as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pesanan" role="tabpanel"
                                aria-labelledby="nav-pesanan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pemesanan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-penawaran" role="tabpanel"
                                aria-labelledby="nav-penawaran-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Berlaku Hingga</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penawaran as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
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
    </script>
@endsection
