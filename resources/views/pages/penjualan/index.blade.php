@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0" style="padding: 1rem 0.5rem">
                        <div class="row mb-3">
                            <div class="col">
                                <b>Penjualan</b>
                            </div>
                            <div class="col d-flex justify-content-end ">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Buat penjualan baru</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penagihan') }}">@if($pengaturan_nama->count()> 0) {{ $pengaturan_nama[0]->nama_diubah }} @else Penagihan Penjualan @endif</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/pemesanan') }}">@if($pengaturan_nama->count()> 0) {{ $pengaturan_nama[1]->nama_diubah }} @else Pemesanan Penjualan @endif</a>
                                    <a class="dropdown-item text-capitalize" href="{{ url('penjualan/penawaran') }}">@if($pengaturan_nama->count()> 0) {{ $pengaturan_nama[2]->nama_diubah }} @else Penawaran Penjualan @endif</a>
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
                                    <div class="card-header border-danger" style="padding: 0.5rem 0.75rem !important; background:#FDECEE;">
                                        Jatuh tempo
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. 0,00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col" style="padding-right: 0px !important;">
                                <div class="card border-success">
                                    <div class="card-header border-success" style="padding: 0.5rem 0.75rem !important; background:#E8F5EB;">
                                        Pelunasan 30 hari terakhir
                                    </div>
                                    <div class="card-body" style="padding: 0.5rem 0.75rem !important;">
                                        Total <br> <span style="font-weight:900">Rp. 0,00</span>
                                        
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
                                
                                <button class="nav-link active" id="nav-penawaran-tab" data-toggle="tab" data-target="#nav-penawaran"
                                    type="button" role="tab" aria-controls="nav-penawaran"
                                    aria-selected="false">Penawaran</button>
                                <button class="nav-link" id="nav-pesanan-tab" data-toggle="tab" data-target="#nav-pesanan"
                                    type="button" role="tab" aria-controls="nav-pesanan"
                                    aria-selected="false">Pesanan</button>
                                <button class="nav-link" id="nav-pengiriman-tab" data-toggle="tab" data-target="#nav-pengiriman"
                                    type="button" role="tab" aria-controls="nav-pengiriman"
                                    aria-selected="true">Pengiriman</button>
                                <button class="nav-link" id="nav-penagihan-tab" data-toggle="tab" data-target="#nav-penagihan"
                                    type="button" role="tab" aria-controls="nav-penagihan"
                                    aria-selected="true">Penagihan</button>
                                <button class="nav-link" id="nav-selesai-tab" data-toggle="tab" data-target="#nav-selesai"
                                    type="button" role="tab" aria-controls="nav-selesai"
                                    aria-selected="true">Selesai</button>
                                <button class="nav-link" id="nav-membutuhkan-persetujuan-tab" data-toggle="tab" data-target="#nav-membutuhkan-persetujuan"
                                    type="button" role="tab" aria-controls="nav-membutuhkan-persetujuan"
                                    aria-selected="false">Membutuhkan persetujuan @php $count_membutuhkan_persetujuan = 0; if(count($membutuhkan_persetujuan) > 0) $count_membutuhkan_persetujuan = count($membutuhkan_persetujuan); @endphp @if($count_membutuhkan_persetujuan > 0) <span class="badge badge-primary">{{ $count_membutuhkan_persetujuan }} </span> @else <span class="badge badge-secondary">{{ $count_membutuhkan_persetujuan }} </span> @endif</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade" id="nav-selesai" role="tabpanel"
                                aria-labelledby="nav-selesai-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">Tanggal Pembayaran</th>
                                                <th scope="col">Dokumen</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-penagihan" role="tabpanel"
                                aria-labelledby="nav-penagihan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tgl. Penagihan</th>
                                                <th scope="col">Tgl. Kirim</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No </th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Checklist</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penagihan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi_pengiriman)) }}</td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>@if($v->status =='open') <span class="badge badge-warning">Menunggu pembayaran</span> @else {{ $v->status }} @endif</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>
                                                @if($v->status == 'paid')
                                                <form action="{{ url('penjualan/selesai').'/'.$v->id }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">Selesai</button>
                                                </form>
                                                @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pengiriman" role="tabpanel"
                                aria-labelledby="nav-pengiriman-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">No Surat Jalan</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pengiriman as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pesanan" role="tabpanel"
                                aria-labelledby="nav-pesanan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pemesanan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="nav-penawaran" role="tabpanel"
                                aria-labelledby="nav-penawaran-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No RFQ</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Berlaku Hingga</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penawaran as $v)
                                            <tr>
                                                <td>{{ $v->tanggal_transaksi }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td>{{ $v->no_rfq }}</td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-membutuhkan-persetujuan" role="tabpanel"
                                aria-labelledby="nav-membutuhkan-persetujuan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. kedaluarsa</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($membutuhkan_persetujuan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a href="{{ url('pelanggan/detail').'/'.$v->id }}">{{ $v->nama_pelanggan }}</a></td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>{{ $v->status }}</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>@if($is_approver)<a class="btn btn-outline-primary btn-sm" href="{{ url('penjualan/approve').'/'.$v->id }}">Setujui</a>@endif</td>
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



    <script>
    </script>
@endsection
