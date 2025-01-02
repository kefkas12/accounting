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
                                <a href="{{ url('kas_bank/penerimaan/insert') }}"class="btn btn-primary" >
                                    Terima Uang / Penerimaan
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
                                    <th><strong>Tanggal</strong></th>
                                    <th><strong>Kas/Bank</strong></th>
                                    <th><strong>Nilai</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penerimaan as $v)
                                <tr>
                                    <!-- <td><a href="{{ url('kas_bank/penerimaan/detail').'/'.$v->id }}">{{ $v->nama }}</a></td> -->
                                    <td>{{ $v->tanggal }}</td>
                                    <td>{{ $v->nama }}</td>
                                    <td>{{ $v->nilai }}</td>
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
