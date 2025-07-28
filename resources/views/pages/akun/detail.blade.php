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
                            <div class="col-sm-6 d-flex justify-content-end py-2" >
                                <a href="{{ url('akun/edit').'/'.$akun->id }}" class="btn btn-outline-primary" hidden>Ubah</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        Transaksi Akun
                        <div class="card-body mt-4" style="padding: 0px !important;">
                            <form>
                                @csrf
                                <div class="form-row">
                                    <div class="col-sm-2">
                                        <input type="date" class="form-control" name="dari" @if(isset($_GET['dari'])) value="{{ $_GET['dari'] }}" @endif>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="date" class="form-control" name="sampai" @if(isset($_GET['sampai'])) value="{{ $_GET['sampai'] }}" @endif>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="{{ url('akun/detail').'/'.$id }}" class="btn btn-primary">Clear</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table my-4">
                                    <thead style="background-color: #E0F7FF">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nomor</th>
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
                                            <td>{{ date('d/m/Y',strtotime($v->jurnal->tanggal_transaksi)) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-xs">
                                                            @if($v->jurnal->kategori == 'sales_invoice')
                                                            <a href="{{ url('penjualan/detail').'/'.$v->id_penjualan }}">{{ $v->jurnal->no_str }}</a>
                                                            @elseif($v->jurnal->kategori == 'purchase_invoice')
                                                            <a href="{{ url('pembelian/detail').'/'.$v->id_pembelian }}">{{ $v->jurnal->no_str }}</a>
                                                            @elseif($v->jurnal->kategori == 'receive_payment')
                                                            <a href="{{ url('penjualan/receive_payment').'/'.$v->id_pembayaran_penjualan }}">{{ $v->jurnal->no_str }}</a>
                                                            @elseif($v->jurnal->kategori == 'purchase_payment')
                                                            <a href="{{ url('pembelian/receive_payment').'/'.$v->id_pembayaran_pembelian }}">{{ $v->jurnal->no_str }}</a>
                                                            @else
                                                            <a href="{{ url('jurnal/detail').'/'.$v->jurnal->id }}">{{ $v->jurnal->no_str }}</a>
                                                            @endif
                                                        </h6>
                                                        <p class="text-xs mb-0 email">{{ $v->deskripsi }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">{{ number_format($v->debit,2,',','.') }}</td>
                                            <td class="text-right text-danger">{{ number_format($v->kredit,2,',','.') }}</td>
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
