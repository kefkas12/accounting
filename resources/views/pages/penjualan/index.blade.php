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
                                <button class="nav-link" id="nav-jatuh-tempo-tab" data-toggle="tab" data-target="#nav-jatuh-tempo"
                                    type="button" role="tab" aria-controls="nav-jatuh-tempo"
                                    aria-selected="true">Jatuh Tempo</button>
                                <button class="nav-link" id="nav-selesai-tab" data-toggle="tab" data-target="#nav-selesai"
                                    type="button" role="tab" aria-controls="nav-selesai"
                                    aria-selected="true">Selesai</button>
                                <button class="nav-link" id="nav-membutuhkan-persetujuan-tab" data-toggle="tab" data-target="#nav-membutuhkan-persetujuan"
                                    type="button" role="tab" aria-controls="nav-membutuhkan-persetujuan"
                                    aria-selected="false">Membutuhkan persetujuan @php $count_membutuhkan_persetujuan = 0; if(count($membutuhkan_persetujuan) > 0) $count_membutuhkan_persetujuan = count($membutuhkan_persetujuan); @endphp @if($count_membutuhkan_persetujuan > 0) <span class="badge badge-primary">{{ $count_membutuhkan_persetujuan }} </span> @else <span class="badge badge-secondary">{{ $count_membutuhkan_persetujuan }} </span> @endif</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-penawaran" role="tabpanel"
                                aria-labelledby="nav-penawaran-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="penawaranTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No RFQ</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Berlaku Hingga</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Produk</th>
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
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <span class="badge badge-dark text-white">
                                                    @else
                                                    <span class="badge badge-success">
                                                    @endif
                                                    {{ $v->status }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>@foreach($v->detail_penjualan as $w) {{ $w->produk->nama }} @endforeach</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pesanan" role="tabpanel"
                                aria-labelledby="nav-pesanan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="pesananTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pemesanan as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <span class="badge badge-dark text-white">
                                                    @else
                                                    <span class="badge badge-success">
                                                    @endif
                                                    {{ $v->status }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>@foreach($v->detail_penjualan as $w) {{ $w->produk->nama }} @endforeach</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-pengiriman" role="tabpanel"
                                aria-labelledby="nav-pengiriman-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="pengirimanTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Surat Jalan</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($pengiriman as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>
                                                    @if($v->status == 'closed')
                                                    <span class="badge badge-dark text-white">
                                                    @else
                                                    <span class="badge badge-success">
                                                    @endif
                                                    {{ $v->status }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>@foreach($v->detail_penjualan as $w) {{ $w->produk->nama }} @endforeach</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-penagihan" role="tabpanel"
                                aria-labelledby="nav-penagihan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="penagihanTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tgl. Penagihan / Tgl. Kirim</th>
                                                <th scope="col">No</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Checklist</th>
                                                <th scope="col">Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($penagihan as $v)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($v->tanggal_transaksi)) }} / @if($v->tanggal_transaksi_pengiriman) {{ date('d-m-Y', strtotime($v->tanggal_transaksi_pengiriman)) }} @else - @endif</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>
                                                    @if($v->selesai == 'selesai') <span class="badge badge-dark text-white">closed</span>
                                                    @else @if($v->status =='open') <span class="badge badge-warning">Menunggu pembayaran</span> 
                                                    @else <span class="badge badge-success"> {{ $v->status }} </span> @endif 
                                                    @endif</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>
                                                @if($v->status == 'paid' && $v->selesai != 'selesai')
                                                <form action="{{ url('penjualan/selesai').'/'.$v->id }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Selesai</button>
                                                </form>
                                                @endif
                                                </td>
                                                <td>@foreach($v->detail_penjualan as $w) {{ $w->produk->nama }} @endforeach</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-jatuh-tempo" role="tabpanel"
                                aria-labelledby="nav-jatuh-tempo-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="jatuhTempoTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tgl. Penagihan / Tgl. Kirim</th>
                                                <th scope="col">No</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">Pelanggan </th>
                                                <th scope="col">Tgl. Jatuh Tempo</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Sisa Tagihan</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Checklist</th>
                                                <th scope="col">Produk</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach($jatuh_tempo as $v)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($v->tanggal_transaksi)) }} / @if($v->tanggal_transaksi_pengiriman) {{ date('d-m-Y', strtotime($v->tanggal_transaksi_pengiriman)) }} @else - @endif</td>
                                                <td><a href="{{ url('penjualan/detail').'/'.$v->id }}">{{ $v->no_str }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                <td>{{ $v->nama_pelanggan }}</td>
                                                <td>@if($v->tanggal_jatuh_tempo) {{ date('d/m/Y',strtotime($v->tanggal_jatuh_tempo)) }} @else - @endif</td>
                                                <td>
                                                    @if($v->selesai == 'selesai') <span class="badge badge-dark text-white">closed</span>
                                                    @else @if($v->status =='open') <span class="badge badge-warning">Menunggu pembayaran</span> 
                                                    @else <span class="badge badge-success"> {{ $v->status }} </span> @endif 
                                                    @endif</td>
                                                <td>Rp {{ number_format($v->sisa_tagihan,2,',','.') }}</td>
                                                <td>Rp {{ number_format($v->total,2,',','.') }}</td>
                                                <td>
                                                @if($v->status == 'paid' && $v->selesai != 'selesai')
                                                <form action="{{ url('penjualan/selesai').'/'.$v->id }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Selesai</button>
                                                </form>
                                                @endif
                                                </td>
                                                <td>@foreach($v->detail_penjualan as $w) {{ $w->produk->nama }} @endforeach</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-selesai" role="tabpanel"
                                aria-labelledby="nav-selesai-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="selesaiTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">No Penawaran</th>
                                                <th scope="col">No PO</th>
                                                <th scope="col">Tanggal Pembayaran</th>
                                                <th scope="col">Dokumen</th>
                                                <th scope="col">Status Selesai</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @php $id_dokumen = array(); @endphp
                                            @php $nama_dokumen = array(); @endphp
                                            @foreach($selesai as $v)
                                                @php $id_dokumen[$v->id] = array(); @endphp
                                                @php $nama_dokumen[$v->id] = array(); @endphp
                                                <tr>
                                                    <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                                    <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_penawaran }}">{{ $v->no_str_penawaran }}</a></td>
                                                    <td><a class="text-dark" href="{{ url('penjualan/detail').'/'.$v->id_pemesanan }}">{{ $v->no_str_pemesanan }}</a></td>
                                                    <td>{{ date('d/m/Y', strtotime($v->tanggal_pembayaran)) }}</td>
                                                    <td>
                                                        @foreach($v->dokumen_penjualan as $w)
                                                            @php array_push($id_dokumen[$v->id], $w->id_dokumen) @endphp
                                                            @php array_push($nama_dokumen[$v->id], $w->nama) @endphp
                                                        @endforeach
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="upload_dokumen({{ $v->id }}, '{{ implode(';',$id_dokumen[$v->id]) }}', '{{ implode(';',$nama_dokumen[$v->id]) }}')">
                                                            Upload
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                        @foreach($v->dokumen_penjualan as $w)
                                                            <li>{{ $w->dokumen->nama }}</li>
                                                        @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-membutuhkan-persetujuan" role="tabpanel"
                                aria-labelledby="nav-membutuhkan-persetujuan-tab">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush" id="membutuhkanPersetujuanTable">
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data" id="upload_dokumen">
                    @csrf
                    <div class="modal-body" >
                        @foreach($pengaturan_dokumen as $v)
                        <div class="form-group">
                            <label for="{{ $v->nama }}" id="label_{{ $v->id }}">{{ $v->nama }}</label>
                            <input type="file" class="form-control" name="{{ $v->id }}" id="file_{{ $v->id }}">
                            <br>
                            <a href="#" id="link_{{ $v->id }}" style="display:none;" target="_blank"></a>
                            <input type="number" name="id_dokumen[]" value="{{ $v->id }}" hidden id="id_{{ $v->id }}">
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script>
        let penawaranTable = new DataTable('#penawaranTable', {
                                columnDefs: [
                                    {
                                        target: 7,
                                        visible: false
                                    }
                                ]
                            });
        let pesananTable = new DataTable('#pesananTable', {
                                columnDefs: [
                                    {
                                        target: 7,
                                        visible: false
                                    }
                                ]
                            });
        let pengirimanTable = new DataTable('#pengirimanTable', {
                                columnDefs: [
                                    {
                                        target: 8,
                                        visible: false
                                    }
                                ]
                            });
        let penagihanTable = new DataTable('#penagihanTable', {
                                columnDefs: [
                                    {
                                        target: 10,
                                        visible: false
                                    }
                                ]
                            });
        let jatuhTempoTable = new DataTable('#jatuhTempoTable', {
                                columnDefs: [
                                    {
                                        target: 10,
                                        visible: false
                                    }
                                ]
                            });
        let selesaiTable = new DataTable('#selesaiTable');
        let membutuhkanPersetujuanTable = new DataTable('#membutuhkanPersetujuanTable');

        function upload_dokumen(id, id_dokumen, nama_dokumen){
            $('.form-control').show();
            id_dokumen = id_dokumen.split(";");
            nama_dokumen = nama_dokumen.split(";");
            
            for (let i = 0; i < id_dokumen.length; i++) {
                $('#file_'+id_dokumen[i]).hide();
                $('#link_'+id_dokumen[i]).show();
                $('#link_'+id_dokumen[i]).text(nama_dokumen[i]);
                $('#link_'+id_dokumen[i]).attr('href','{{ asset("storage/uploads") }}/'+nama_dokumen[i]);
            }

            $('#upload_dokumen').attr('action','{{ url("penjualan/upload/dokumen") }}/'+id);
        }
    </script>
@endsection
