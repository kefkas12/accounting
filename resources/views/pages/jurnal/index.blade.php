@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6 mb-5">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">
                        <div class="row mb-3">
                            <div class="col">
                                Laporan Jurnal (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        
                        <table class="table align-items-center table-flush">
                            <thead>
                                <tr class="bg-info">
                                    <th><h5><strong>Akun</h5></strong></th>
                                    <th><h5><strong>Debit</h5></strong></th>
                                    <th><h5><strong>Kredit</h5></strong></th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_debit = 0;
                                    $total_kredit = 0;
                                @endphp
                                @foreach($jurnal as $v)
                                @php
                                    $debit = 0;
                                    $kredit = 0;
                                @endphp
                                <tr>
                                    <td colspan="3" style="background: #f8f8f8;"><h4><strong>{{ $v->no_str }} | {{ $v->tanggal_transaksi }} (created on {{ $v->created_at }})</strong></h4></td>
                                </tr>
                                @foreach($v->detail_jurnal as $w)
                                <tr>
                                    <td width="500px">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-xs username">({{ $w->akun->nomor }}) - {{ $w->akun->nama }}</h6>
                                                <p class="text-xs mb-0 email">{{ $w->deskripsi }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="150px">{{ number_format($w->debit,2,',','.') }}</td>
                                    <td width="150px">{{ number_format($w->kredit,2,',','.') }}</td>
                                </tr>
                                @php
                                    $debit += $w->debit;
                                    $kredit += $w->kredit;
                                @endphp
                                @endforeach
                                <tr>
                                    <td style="text-align: end">
                                        <strong>total</strong>
                                    </td>
                                    <td width="150px"><strong>{{ number_format($debit,2,',','.') }}</strong></td>
                                    <td width="150px"><strong>{{ number_format($kredit,2,',','.') }}</strong></td>
                                </tr>
                                @php
                                    $total_debit += $debit;
                                    $total_kredit += $kredit;
                                @endphp
                                @endforeach
                                <tr>
                                    <td style="text-align: end">
                                        <strong>Grand Total</strong>
                                    </td>
                                    <td width="150px"><strong>{{ number_format($total_debit,2,',','.') }}</strong></td>
                                    <td width="150px"><strong>{{ number_format($total_kredit,2,',','.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
