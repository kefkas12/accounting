@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat pembelian</button>
                                    <div class="dropdown-menu">
                                      <a class="dropdown-item" href="{{ url('pembelian/faktur') }}">Faktur Pembelian</a>
                                      <a class="dropdown-item" href="{{ url('pembelian/pemesanan') }}">Pemesanan Pembelian</a>
                                      <a class="dropdown-item" href="{{ url('pembelian/penawaran') }}">Penawaran Pembelian</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="card border-warning">
                                    <div class="card-header border-warning">
                                        Belum dibayar
                                    </div>
                                    <div class="card-body">
                                        <p>Total</p>
                                        <b>Rp. {{ $belum_dibayar }}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-danger">
                                    <div class="card-header border-danger">
                                        Jatuh tempo
                                    </div>
                                    <div class="card-body">
                                        <p>Total</p>
                                        <b>Rp. 0,00</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-primary">
                                    <div class="card-header border-primary">
                                        Pelunasan 30 hari terakhir
                                    </div>
                                    <div class="card-body">
                                        <p>Total</p>
                                        <b>Rp. 0,00</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='container'>
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-faktur-pembelian-tab" data-toggle="tab" data-target="#nav-faktur-pembelian"
                                    type="button" role="tab" aria-controls="nav-faktur-pembelian"
                                    aria-selected="true">Faktur Pembelian</button>
                                <button class="nav-link" id="nav-pemesanan-pembelian-tab" data-toggle="tab" data-target="#nav-pemesanan-pembelian"
                                    type="button" role="tab" aria-controls="nav-pemesanan-pembelian"
                                    aria-selected="false">Pemesanan Pembelian</button>
                                <button class="nav-link" id="nav-penawaran-pembelian-tab" data-toggle="tab" data-target="#nav-penawaran-pembelian"
                                    type="button" role="tab" aria-controls="nav-penawaran-pembelian"
                                    aria-selected="false">Penawaran Pembelian</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-faktur-pembelian" role="tabpanel"
                                aria-labelledby="nav-faktur-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($faktur as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
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
                            <div class="tab-pane fade" id="nav-pemesanan-pembelian" role="tabpanel"
                                aria-labelledby="nav-pemesanan-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Jumlah DP</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pemesanan as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
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
                            <div class="tab-pane fade" id="nav-penawaran-pembelian" role="tabpanel"
                                aria-labelledby="nav-penawaran-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. kedaluarsa</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penawaran as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
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
