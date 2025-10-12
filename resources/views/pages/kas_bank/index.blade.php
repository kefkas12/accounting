@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0" style="padding: 1rem 0.5rem">
                        <div class="row mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">
                            <div class="col">
                                <h2 class="text-primary"><strong>Kas & bank</strong></h2>
                            </div>
                            <div class="col d-flex justify-content-end ">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat Akun / Transaksi</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/pemesanan') }}">Transfer Uang</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penawaran') }}">Terima Uang</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penawaran') }}">Kirim Uang</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-success">
                                    <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB;">
                                        Pemasukan
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-danger">
                                    <div class="card-header border-danger" style="padding: 0.5rem 0.75rem !important; background:#FDECEE;">
                                        Pengeluaran
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-primary">
                                    <div class="card-header border-primary" style="padding: 0.5rem 0.75rem !important;background:#4b61dd;">
                                        Saldo Kas & Bank
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                            </div>
                        </div>
                    </div>
                    <div class='container-fluid' style="padding-left: 1.45rem !important;">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th scope="col">Kode akun</th>
                                        <th scope="col">Nama akun</th>
                                        <th scope="col">Saldo</th>
                                        <th scope="col"></th>
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="4">Kas & Bank</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach($kas_bank as $v)
                                        <tr>
                                            <td>{{ $v->nomor }}</td>
                                            <td>{{ $v->nama }}</td>
                                            <td>Rp {{ number_format($v->saldo,2,',','.') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat Akun / Transaksi</button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item text-capitalize" href="{{ url('penjualan/pemesanan') }}">Transfer Uang</a>
                                                        <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penawaran') }}">Terima Uang</a>
                                                        <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penawaran') }}">Kirim Uang</a>
                                                    </div>
                                                </div>
                                            </td>
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
@endsection