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
                                <a href="{{ url('penjualan/faktur') }}"class="btn btn-primary" >
                                    Tambah Penjualan
                                </a>
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
                                <div class="card border-success">
                                    <div class="card-header border-success">
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
                                @foreach($penjualan as $v)
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



    <script>
    </script>
@endsection
