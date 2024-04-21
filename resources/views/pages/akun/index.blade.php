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
                            <div class="col" style="padding: 0">Daftar Akun</div>
                            <div class="col d-flex justify-content-end">
                                <a href="{{ url('jurnal/insert') }}"class="btn btn-primary" >
                                    Buat Jurnal Umum
                                </a>
                                <a href="{{ url('akun/insert') }}"class="btn btn-primary" >
                                    Buat Akun Umum
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        
                        <table class="table align-items-center table-flush">
                            <thead>
                                <tr>
                                    <th>Nomor Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Nama Kategori</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($akun as $v)
                                <tr>
                                    <td>{{ $v->nomor }}</td>
                                    <td><a href="{{ url('akun/detail').'/'.$v->id }}">{{ $v->nama }}</a></td>
                                    <td>{{ $v->nama_kategori }}</td>
                                    <td>@if($v->saldo_akun < 0 )( {{ number_format(abs($v->saldo_akun),2,',','.') }} ) @else {{ number_format($v->saldo_akun,2,',','.')  }} @endif</td>
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
