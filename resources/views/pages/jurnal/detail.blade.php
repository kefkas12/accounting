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
                            <div class="col-sm-6">
                                <h2>
                                    <small style="display: block;">Transaksi</small>
                                    {{ $jurnal->no_str }}
                                </h2>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end py-2">
                                <h1>Selesai</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-sm"> 
                        <hr style="margin-top:0px !important;">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="col-sm-12"><strong>Tgl Transaksi:</strong></div>
                                <div class="col-sm-12">{{ $jurnal->tanggal_transaksi }}</div>
                            </div>
                            <div class="col">
                                <div class="col-sm-12"><strong>No Transaksi:</strong></div>
                                <div class="col-sm-12">{{ $jurnal->no }}</div>
                            </div>
                            <div class="col"></div>
                            <div class="col"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table my-4">
                                <thead style="background-color: #E0F7FF">
                                    <tr>
                                        <th>Nomor Akun</th>
                                        <th>Akun</th>
                                        <th>Deskripsi</th>
                                        <th class="text-right">Debit (in IDR)</th>
                                        <th class="text-right">Kredit (in IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $debit = 0;
                                        $kredit = 0;
                                    @endphp
                                    @foreach($jurnal->detail_jurnal as $v)
                                        <tr>
                                            <td>{{ $v->akun->nomor }}</td>
                                            <td>{{ $v->akun->nama }}</td>
                                            <td>{{ $v->deskripsi }}</td>
                                            <td class="text-right">{{ number_format($v->debit,2,',','.') }}</td>
                                            <td class="text-right">{{ number_format($v->kredit,2,',','.') }}</td>
                                        </tr>
                                        @php
                                        $debit += $v->debit;
                                        $kredit += $v->kredit;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-right">
                                            Total Debit
                                            <div class="text-right">{{ number_format($debit,2,',','.') }}</div>
                                        </td>
                                        <td class="text-right">
                                            Total Kredit
                                            <div class="text-right">{{ number_format($kredit,2,',','.') }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
