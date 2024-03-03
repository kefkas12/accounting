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
                                    <small style="display: block;">Akun - {{ $akun->nama_kategori }}</small>
                                     ({{ $akun->nomor }}) {{ $akun->nama }}
                                </h2>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end py-2">
                                <a href="{{ url('akun/edit').'/'.$akun->id }}" class="btn btn-outline-primary">Ubah</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        Transaksi Akun
                        <div class="card-body mt-4" style="padding: 0px !important;">    
                            <div class="table-responsive">
                                <table class="table my-4">
                                    <thead style="background-color: #E0F7FF">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nomor</th>
                                            <th>Kontak</th>
                                            <th class="text-right">Debit (dalam IDR)</th>
                                            <th class="text-right">Kredit (dalam IDR)</th>
                                            <th class="text-right">Saldo (dalam IDR)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $saldo = 0;
                                        @endphp
                                        @foreach($transaksi_akun as $v)
                                        <tr>
                                            <td>{{ $v->jurnal->tanggal_transaksi }}</td>
                                            <td><a href="{{ url('jurnal/detail').'/'.$v->jurnal->id  }}" >{{ $v->jurnal->no_str }}</a></td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($v->debit,2,',','.') }}</td>
                                            <td class="text-right">{{ number_format($v->kredit,2,',','.') }}</td>
                                            @php
                                            $saldo = $saldo + $v->debit - $v->kredit;
                                            @endphp
                                            <td class="text-right">{{ number_format($saldo,2,',','.') }}</td>
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
@endsection
