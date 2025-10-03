@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0" style="padding: 1rem 0.5rem">
                        <div class="row mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">
                            <div class="col">
                                <h2 class="text-primary"><strong>Pembelian</strong></h2>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat pembelian</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('pembelian/faktur') }}">Faktur Pembelian</a>
                                    <a class="dropdown-item" href="{{ url('pembelian/pemesanan') }}">Pemesanan Pembelian</a>
                                    <a class="dropdown-item" href="{{ url('pembelian/penawaran') }}" hidden>Penawaran Pembelian</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-warning">
                                    <div class="card-header border-warning" style="padding: 0.5rem 0.75rem !important;background:#FBF3DD;">
                                        Belum dibayar
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp {{ $belum_dibayar }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-danger">
                                    <div class="card-header border-danger"  style="padding: 0.5rem 0.75rem !important; background:#FDECEE;">
                                        Telat dibayar
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. {{ $telat_dibayar }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-success">
                                    <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB;">
                                        Pelunasan 30 hari terakhir
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. {{ $pelunasan_30_hari_terakhir }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                            </div>
                        </div>
                    </div>
                    <div class='container-fluid' style="padding-left: 1.45rem !important;">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                @hasanyrole('pemilik|pergudangan|Admin Gudang')
                                <button class="nav-link" id="nav-pemesanan-pembelian-tab" data-toggle="tab" data-target="#nav-pemesanan-pembelian"
                                    type="button" role="tab" aria-controls="nav-pemesanan-pembelian"
                                    aria-selected="false">Pemesanan</button>
                                <button class="nav-link @if(auth()->user()->hasRole('pergudangan')) active @endif" id="nav-pengiriman-tab" data-toggle="tab" data-target="#nav-pengiriman"
                                    type="button" role="tab" aria-controls="nav-pengiriman"
                                    aria-selected="false">Pengiriman</button>
                                @endhasallroles
                                @hasanyrole('pemilik')
                                <button class="nav-link active" id="nav-faktur-pembelian-tab" data-toggle="tab" data-target="#nav-faktur-pembelian"
                                    type="button" role="tab" aria-controls="nav-faktur-pembelian"
                                    aria-selected="true">Faktur Pembelian</button>
                                @endhasallroles
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade @if(!auth()->user()->hasRole('pergudangan')) show active @endif" id="nav-faktur-pembelian" role="tabpanel"
                                aria-labelledby="nav-faktur-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="fakturTable">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($faktur as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <button class="btn btn-sm" style="background-color:#D0D6DD;">
                                                    @elseif($v->status == 'open')
                                                    <button class="btn btn-sm" style="background-color:#FBF3DD; color:#DB8000;">
                                                    @endif
                                                    {{ $v->status }}
                                                    </button>
                                                </td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade @if(auth()->user()->hasRole('pergudangan')) show active @endif" id="nav-pengiriman" role="tabpanel"
                                aria-labelledby="nav-pengiriman-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="pengirimanTable">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pengiriman as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <button class="btn btn-sm" style="background-color:#D0D6DD;">
                                                    @elseif($v->status == 'open')
                                                    <button class="btn btn-sm" style="background-color:#FBF3DD; color:#DB8000;">
                                                    @endif
                                                    {{ $v->status }}
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pemesanan-pembelian" role="tabpanel"
                                aria-labelledby="nav-pemesanan-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="pemesananTable">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Jumlah DP</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pemesanan as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <button class="btn btn-sm" style="background-color:#D0D6DD;">
                                                    @elseif($v->status == 'open')
                                                    <button class="btn btn-sm" style="background-color:#FBF3DD; color:#DB8000;">
                                                    @endif
                                                    {{ $v->status }}
                                                    </button>
                                                </td>
                                                <td>Rp {{ number_format($v->dp,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div hidden class="tab-pane fade" id="nav-penawaran-pembelian" role="tabpanel"
                                aria-labelledby="nav-penawaran-pembelian-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="penawaranTable">
                                        <thead >
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Supplier </th>
                                                <th scope="col">Tgl. kedaluarsa</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penawaran as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('pembelian/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_supplier }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <button class="btn btn-sm" style="background-color:#D0D6DD;">
                                                    @elseif($v->status == 'open')
                                                    <button class="btn btn-sm" style="background-color:#FBF3DD; color:#DB8000;">
                                                    @endif
                                                    {{ $v->status }}
                                                    </button>
                                                </td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
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
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script>
        let penawaranTable = new DataTable('#penawaranTable', {
                                columnDefs: [
                                    {
                                        target: 6,
                                        visible: false
                                    }
                                ]
                            });
        let pemesananTable = new DataTable('#pemesananTable', {
                                columnDefs: [
                                    {
                                        target: 6,
                                        visible: false
                                    }
                                ]
                            });
        let pengirimanTable = new DataTable('#pengirimanTable', {
                                columnDefs: [
                                    {
                                        target: 3,
                                        visible: false
                                    }
                                ]
                            });
        let fakturTable = new DataTable('#fakturTable', {
                                columnDefs: [
                                    {
                                        target: 6,
                                        visible: false
                                    }
                                ]
                            });

    </script>
@endsection
