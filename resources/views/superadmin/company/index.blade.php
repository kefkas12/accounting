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
                                <a href="{{ url('company/insert') }}"class="btn btn-primary" >
                                    Tambah Company
                                </a>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col">
                                <div class="card border-warning">
                                    <div class="card-header border-warning">
                                        Hutang belum dibayar
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
                                        Hutang jatuh tempo
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
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><strong>No</strong></th>
                                    <th><strong>Nama Perusahaan</strong></th>
                                    <th><strong>Alamat</strong></th>
                                    <th><strong>Email</strong></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company as $v)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $v->nama_perusahaan }}</td>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->email }}</td>
                                    <td><a class="btn btn-primary" href="{{ url('/company/refresh_akun').'/'.$v->id_company }}">Refresh Akun</a></td>
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
