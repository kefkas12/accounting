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
                                <strong><span style="font-size: 1.5rem;">Daftar Pembelian</span></strong> (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Tipe Transaksi</th>
                                    <th scope="col">Nomor Transaksi </th>
                                    <th scope="col">Nama Panggilan</th>
                                    <th scope="col">Status Hari Ini</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Sisa Tagihan</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @php
                                $total = 0;
                                $sisa_tagihan = 0;
                                @endphp
                                @foreach($pembelian as $v)
                                <tr>
                                    <td>{{ $v->tanggal_transaksi }}</td>
                                    <td>{{ $v->jenis }}</td>
                                    <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no }}</a></td>
                                    <td>{{ $v->nama }}</td>
                                    <td>{{ $v->status }}</td>
                                    <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                    <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                </tr>
                                @php
                                $total += $v->total;
                                $sisa_tagihan += $v->sisa_tagihan;
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td>Rp {{ number_format($total,2,',','.') }}</td>
                                    <td>Rp {{ number_format($sisa_tagihan,2,',','.') }}</td>
                                </tr>
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
