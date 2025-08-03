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
                        <div class="row mb-3">
                            <div class="col">
                                <a href="{{ url('pelanggan/insert') }}"class="btn btn-primary" >
                                    Tambah Pelanggan
                                </a>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col">
                                <div class="card border-warning">
                                    <div class="card-header border-warning">
                                        Piutang belum dibayar
                                    </div>
                                    <div class="card-body">
                                        <p>Total</p>
                                        <b>Rp. 1.110.000,00</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-danger">
                                    <div class="card-header border-danger">
                                        Piutang jatuh tempo
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
                                    <th>Nama</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Alamat </th>
                                    <th>Email</th>
                                    <th>No. Handphone</th>
                                    <th hidden>Sisa Tagihan</th>
                                    <th hidden>Total</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($pelanggan as $v)
                                <tr>
                                    <td><a href="{{ url('pelanggan/detail').'/'.$v->id }}">{{ $v->nama }}</a></td>
                                    <td>{{ $v->nama_perusahaan }}</td>
                                    <td>@foreach($v->additional_alamat as $w) {{ $loop->index+1 }}. {{ $w->alamat }} <br> @endforeach</td>
                                    <td>{{ $v->email }}</td>
                                    <td>{{ $v->nomor_handphone }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
