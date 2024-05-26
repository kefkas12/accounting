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
                                <strong><span style="font-size: 1.5rem;">Laporan Jurnal</span></strong> (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <div class='container-fluid' style="padding-left: 1.45rem !important;">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-jurnal-tab" data-toggle="tab" data-target="#nav-jurnal"
                                    type="button" role="tab" aria-controls="nav-jurnal"
                                    aria-selected="true">Jurnal</button>
                                <button class="nav-link" id="nav-membutuhkan-persetujuan-tab" data-toggle="tab" data-target="#nav-membutuhkan-persetujuan"
                                    type="button" role="tab" aria-controls="nav-membutuhkan-persetujuan"
                                    aria-selected="false">Membutuhkan persetujuan @php $count_membutuhkan_persetujuan = 0; if(count($membutuhkan_persetujuan) > 0) $count_membutuhkan_persetujuan = count($membutuhkan_persetujuan); @endphp @if($count_membutuhkan_persetujuan > 0) <span class="badge badge-primary">{{ $count_membutuhkan_persetujuan }} </span> @else <span class="badge badge-secondary">{{ $count_membutuhkan_persetujuan }} </span> @endif</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-jurnal" role="tabpanel"
                                aria-labelledby="nav-jurnal-tab">
                                <div class="card-body">
                                    <form action="{{ url('laporan/jurnal') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-2">Tanggal Mulai
                                                <input type="date" name="tanggal_mulai" class="form-control" @if(isset($_GET['tanggal_mulai'])) value="{{ $_GET['tanggal_mulai'] }}" @endif>
                                            </div>
                                            <div class="col-2">Tanggal Selesai
                                                <input type="date" name="tanggal_selesai" class="form-control" @if(isset($_GET['tanggal_selesai'])) value="{{ $_GET['tanggal_selesai'] }}" @endif>
                                            </div>
                                            <div class="col-2">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                        </div>
                                    </form>
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
                                                <td colspan="3" style="background: #f8f8f8;"><h4><strong>
                                                    @if($v->kategori == 'sales_invoice')
                                                    <a href="{{ url('penjualan/detail').'/'.$v->id_penjualan }}">{{ $v->no_str }}</a>
                                                    @elseif($v->kategori == 'purchase_invoice')
                                                    <a href="{{ url('pembelian/detail').'/'.$v->id_pembelian }}">{{ $v->no_str }}</a>
                                                    @elseif($v->kategori == 'receive_payment')
                                                    <a href="{{ url('penjualan/receive_payment').'/'.$v->id_pembayaran_penjualan }}">{{ $v->no_str }}</a>
                                                    @elseif($v->kategori == 'purchase_payment')
                                                    <a href="{{ url('pembelian/receive_payment').'/'.$v->id_pembayaran_pembelian }}">{{ $v->no_str }}</a>
                                                    @else
                                                    <a href="{{ url('jurnal/detail').'/'.$v->id }}">{{ $v->no_str }}</a>
                                                    @endif
                                                     | @if($v->tanggal_transaksi) {{ date('d-M-Y',strtotime($v->tanggal_transaksi)) }} @else - @endif
                                                     (created on {{ $v->created_at }})
                                                </strong></h4></td>
                                            </tr>
                                            @foreach($v->detail_jurnal as $w)
                                            <tr>
                                                <td width="500px">
                                                    <div class="d-flex">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-xs username">@if($w->akun) <a href="{{ url('akun/detail').'/'.$w->akun->id }}">({{ $w->akun->nomor }}) - {{ $w->akun->nama }}</a>@else - @endif</h6>
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
                            <div class="tab-pane fade" id="nav-membutuhkan-persetujuan" role="tabpanel"
                                aria-labelledby="nav-membutuhkan-persetujuan-tab">
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
                                            @foreach($membutuhkan_persetujuan as $v)
                                            @php
                                                $debit = 0;
                                                $kredit = 0;
                                            @endphp
                                            <tr>
                                                <td colspan="3" style="background: #f8f8f8;"><h4><strong>
                                                    <div class="row">
                                                        <div class="col-sm-10">
                                                            @if($v->kategori == 'sales_invoice')
                                                            <a href="{{ url('penjualan/detail').'/'.$v->id_penjualan }}">{{ $v->no_str }}</a>
                                                            @elseif($v->kategori == 'purchase_invoice')
                                                            <a href="{{ url('pembelian/detail').'/'.$v->id_pembelian }}">{{ $v->no_str }}</a>
                                                            @elseif($v->kategori == 'receive_payment')
                                                            <a href="{{ url('penjualan/receive_payment').'/'.$v->id_pembayaran_penjualan }}">{{ $v->no_str }}</a>
                                                            @elseif($v->kategori == 'purchase_payment')
                                                            <a href="{{ url('pembelian/receive_payment').'/'.$v->id_pembayaran_pembelian }}">{{ $v->no_str }}</a>
                                                            @else
                                                            <a href="{{ url('jurnal/detail').'/'.$v->id }}">{{ $v->no_str }}</a>
                                                            @endif
                                                             | @if($v->tanggal_transaksi) {{ date('d-M-Y',strtotime($v->tanggal_transaksi)) }} @else - @endif
                                                             (created on {{ $v->created_at }}) </div>
                                                        <div class="col-sm-2">
                                                            @if($is_approver)
                                                            <a @if($v->is_delete) href="{{ url('/jurnal/approve').'/'.$v->id.'/delete' }}" @else href="{{ url('/jurnal/approve').'/'.$v->id }}" @endif class="btn @if($v->is_delete) btn-outline-danger @else btn-outline-primary @endif btn-sm">@if($v->is_delete) Approve Delete @else Approve @endif</a>
                                                            <a href="{{ url('/jurnal/cancel').'/'.$v->id }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                </strong></h4></td>
                                            </tr>
                                            @foreach($v->detail_jurnal as $w)
                                            <tr>
                                                <td width="500px">
                                                    <div class="d-flex">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-xs username">@if($w->akun) <a href="{{ url('akun/detail').'/'.$w->akun->id }}">({{ $w->akun->nomor }}) - {{ $w->akun->nama }}</a>@else - @endif</h6>
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
    </div>

@endsection
