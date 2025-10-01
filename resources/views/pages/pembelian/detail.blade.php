@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body border-0 text-sm">
                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <h2>
                                @if ($pembelian->jenis == 'faktur')
                                    Faktur 
                                @elseif($pembelian->jenis == 'penawaran')
                                    Penawaran 
                                @elseif($pembelian->jenis == 'pemesanan')
                                    Pemesanan 
                                @elseif($pembelian->jenis == 'pengiriman')
                                    Pengiriman
                                @endif
                                Pembelian #{{ $pembelian->no }}
                                    @if($pembelian->status == 'open')
                                    <button class="btn btn-sm btn-warning ml-2" style="background-color: #F59E0B">
                                    @elseif($pembelian->status == 'partial')
                                    <button class="btn btn-sm btn-info ml-2">
                                    @elseif($pembelian->status == 'paid')
                                    <button class="btn btn-sm btn-success ml-2">
                                    @elseif($pembelian->status == 'overdue')
                                    <button class="btn btn-sm btn-danger ml-2">
                                    @elseif($pembelian->status == 'closed')
                                    <button class="btn btn-sm btn-dark ml-2">
                                    @endif
                                    
                                        @if ($pembelian->status == 'open')
                                        @if ($pembelian->jenis == 'pemesanan')
                                            Belum ditagih
                                        @else
                                            Belum Dibayar
                                        @endif
                                        @elseif($pembelian->status == 'partial')
                                            Terbayar Sebagian
                                        @elseif($pembelian->status == 'paid')
                                            Lunas
                                        @elseif($pembelian->status == 'overdue')
                                            Lewat Jatuh Tempo
                                        @elseif($pembelian->status == 'closed')
                                            Selesai
                                        @elseif($pembelian->status == 'draf')
                                            Draf
                                        @endif
                                    </button>
                                </h2>
                            </div>
                            <div class="form-group col-md-2">
                                <a href="{{ url('pembelian') }}" class="btn btn-dark">Kembali ke Pembelian</a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-2">Supplier <br> @if($pembelian->nama_supplier)<strong>{{ $pembelian->nama_supplier }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-sm-2">Tgl. Transaksi <br> @if($pembelian->tanggal_transaksi)<strong>{{ date('d/m/Y', strtotime($pembelian->tanggal_transaksi)) }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-sm-2">Alamat <br> @if($pembelian->alamat)<strong>{{ $pembelian->alamat }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-sm-2">Detail Alamat <br> @if($pembelian->detail_alamat)<strong>{{ $pembelian->detail_alamat }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-2">No Transaksi <br> @if($pembelian->no_str)<strong>{{ $pembelian->no_str }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-2">@if ($jurnal) Jurnal <br> <a href="#" data-toggle="modal" data-target="#jurnalEntryModal">Lihat Jurnal Entry</a> @endif </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-2">Kirim Melalui <br> @if($pembelian->kirim_melalui)<strong>{{ $pembelian->kirim_melalui }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-sm-2">No. Pelacakan <br> @if($pembelian->no_pelacakan)<strong>{{ $pembelian->no_pelacakan }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-sm-2"></div>
                            <div class="form-group col-sm-2"></div>
                            <div class="form-group col-sm-2"></div>
                            <div class="form-group col-sm-2"></div>
                        </div>
                        @if($pembelian->jenis != 'penawaran')
                        <div class="form-row" style="display:none">
                            <div class="form-group col-md-3" >Gudang <br> @if($pembelian->nama_gudang)<strong><a href="{{ url('gudang/detail').'/'.$pembelian->id_gudang }}">{{ $pembelian->nama_gudang }}</a></strong>@else <strong> - </strong> @endif</div>
                        </div>
                        @endif
                        <div class="form-row">
                            @if($pembelian->pengiriman)
                            <div class="form-group col-md-2">
                                No. Pengiriman <br>
                                <a href="{{ url('pembelian/detail').'/'.$pembelian->pengiriman->id }}">{{ $pembelian->pengiriman->no_str }}</a>
                            </div>
                            @endif
                            @if($pembelian->pemesanan)
                            <div class="form-group col-md-2">
                                No. Pemesanan <br>
                                <a href="{{ url('pembelian/detail').'/'.$pembelian->pemesanan->id }}">{{ $pembelian->pemesanan->no_str }}</a>
                            </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Deskripsi</th>
                                        <th>Kuantitas</th>
                                        <th>Unit</th>
                                        @if($pembelian->jenis != 'pengiriman')
                                        <th>Harga Satuan</th>
                                        <th>Pajak</th>
                                        <th>Jumlah</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian->detail_pembelian as $v)
                                        <tr>
                                            <th><a href="{{ url('produk').'/detail/'.$v->produk->id }}">{{ $v->produk->nama }}</a></th>
                                            <td>{{ $v->deskripsi }}</td>
                                            <td>{{ $v->kuantitas }}</td>
                                            <td>Buah</td>
                                            @if($pembelian->jenis != 'pengiriman')
                                            <td>Rp. {{ number_format($v->harga_satuan, 2, ',', '.') }}</td>
                                            <td>@if($v->pajak != 0) PPN @endif</td>
                                            <td style="margin-right: 25px !important;">Rp. {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body border-0 text-sm">
                            @if($pembelian->jenis == 'pengiriman')
                                <div class="form-row">
                                    <div class="form-group col-md-4 pr-2">
                                        <div class="row mb-1">
                                            <div class="col">
                                                Pesan
                                            </div>
                                            <div class="col">
                                                <strong>@if($pembelian->pesan){{ $pembelian->pesan }} @else - @endif</strong>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col">
                                                Memo
                                            </div>
                                            <div class="col">
                                                <strong>@if($pembelian->pesan){{ $pembelian->memo }} @else - @endif</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="row my-1">
                                            <div class="col">
                                                Ongkos kirim
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <h4>Rp. {{ number_format($pembelian->ongkos_kirim, 2, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($log))
                                <div class="form-row">
                                    <div class="form-group col-md-6 pr-2">
                                        <a href="#" data-toggle="modal" data-target="#logUpdateModal">Terakhir diproses oleh {{ $log->name }} pada {{ date('d F Y h:i:s', strtotime($log->created_at)) }}</a>
                                    </div>
                                </div>
                                @endif
                            @else
                                @hasanyrole('pemilik')
                                <div class="form-row">
                                    <div class="form-group col-md-4 pr-2">
                                        <div class="row mb-1">
                                            <div class="col">
                                                Pesan
                                            </div>
                                            <div class="col">
                                                <strong>@if($pembelian->pesan){{ $pembelian->pesan }} @else - @endif</strong>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col">
                                                Memo
                                            </div>
                                            <div class="col">
                                                <strong>@if($pembelian->pesan){{ $pembelian->memo }} @else - @endif</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>Subtotal</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->subtotal, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @if(isset($pembelian->ppn) && $pembelian->ppn > 0)
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>PPN 11%</span>
                                            </div>
                                            <div class="col-sm-6 d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->ppn, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($pembelian->ongkos_kirim) && $pembelian->ongkos_kirim > 0)
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>Ongkos Kirim</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->ongkos_kirim, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>Total</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->total, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @if($pembelian->jenis == 'faktur')
                                        <div class="row mb-2">
                                            <div class="col">
                                                <span>Jumlah Terbayar</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->jumlah_terbayar, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @endif
                                        @if($pembelian->jumlah_terbayar != 0)
                                        <div class="row mb-2">
                                            <div class="col">
                                                <span>Sisa Tagihan</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <strong>Rp. {{ number_format($pembelian->sisa_tagihan, 2, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endhasallroles
                                @if(isset($log))
                                <div class="form-row">
                                    <div class="form-group col-md-6 pr-2">
                                        <a href="#" data-toggle="modal" data-target="#logUpdateModal">Terakhir diproses oleh {{ $log->name }} pada {{ date('d F Y h:i:s', strtotime($log->created_at)) }}</a>
                                    </div>
                                </div>
                                @endif
                            @endif
                            @if(isset($pengiriman))
                            <div class="table-responsive mt-3" style="display:none">
                                <div class="row">
                                    <div class="col">Pengiriman</div>
                                </div>
                                <table class="table my-4">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tgl. pengiriman</th>
                                            <th>No.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengiriman as $v)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v->tanggal_transaksi)) }}</td>
                                            <td>
                                                <div>
                                                    <div class="row"><a
                                                            href="{{ url('pembelian/detail') . '/' . $v->id }}">{{ $v->no_str }}</a>
                                                    </div>
                                                    <div class="row text-xs">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm 
                                                @if ($v->status == 'open') btn-warning
                                                @elseif($v->status == 'partial') btn-info
                                                @elseif($v->status == 'paid') btn-success
                                                @elseif($v->status == 'overdue') btn-danger 
                                                @elseif($v->status == 'closed') btn-dark @endif
                                                ml-2">
                                                    {{ $v->status }}
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-6">
                                    @hasanyrole('pemilik')
                                        @if($pembelian->status == 'draf' || $pembelian->status == 'open')
                                        <form id="deleteForm" action="{{ url('pembelian/hapus') . '/' . $pembelian->id }}"
                                            method="post">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-outline-danger"onclick="confirmDelete(event)">Hapus</button>
                                        </form>
                                        @endif
                                    @endhasallroles
                                </div>
                                @if($pembelian->jenis == 'faktur' && $pembelian->status != 'paid')
                                <div class="col-sm-6 d-flex justify-content-end">
                                    <a href="{{ url('pembelian').'/'.$pembelian->jenis.'/'.$pembelian->id }}" class="btn btn-outline-primary">Ubah</a>
                                    <div class="btn-group dropup mr-2">
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                            Cetak
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- Dropdown menu links -->
                                            @if($pembelian->jenis == 'faktur')
                                                <a class="dropdown-item" href="{{ url('pembelian/cetak/faktur') . '/' . $pembelian->id }}" target="_blank">Cetak Faktur</a>
                                                <a class="dropdown-item" href="{{ url('pembelian/cetak/surat_jalan') . '/' . $pembelian->id }}" target="_blank">Cetak Surat Jalan</a>
                                            @elseif($pembelian->jenis == 'penawaran')
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pemesanan/' . $pembelian->id }}">Buat Pemesanan</a>
                                            @elseif($pembelian->jenis == 'pemesanan')
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                            @endif
                                        </div>
                                    </div>
                                    @if($pembelian->status != 'draf' && $pembelian->status != 'closed')
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                            Tindakan
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- Dropdown menu links -->
                                            @if($pembelian->jenis == 'faktur')
                                                <a class="dropdown-item" href="{{ url('pembelian/pembayaran') . '/' . $pembelian->id }}">Kirim Pembayaran</a>
                                            @elseif($pembelian->jenis == 'penawaran')
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pemesanan/' . $pembelian->id }}">Buat Pemesanan</a>
                                            @elseif($pembelian->jenis == 'pemesanan')
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="col-sm-6 d-flex justify-content-end">
                                    @if($pembelian->status == 'draf' || $pembelian->status == 'open')
                                    <a href="{{ url('pembelian').'/'.$pembelian->jenis.'/'.$pembelian->id }}" class="btn btn-outline-primary">Ubah</a>
                                    @endif
                                    @if($pembelian->jenis == 'penawaran' || $pembelian->jenis == 'pemesanan')
                                    <div class="btn-group dropup mr-2">
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                            Cetak
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($pembelian->jenis == 'penawaran')
                                            <a target="_blank" class="dropdown-item" href="{{ url('pembelian/penawaran/cetak') . '/' . $pembelian->id }}">Cetak Penawaran</a>
                                            @elseif($pembelian->jenis == 'pemesanan')
                                            <a target="_blank" class="dropdown-item" href="{{ url('pembelian/pemesanan/cetak') . '/' . $pembelian->id }}">Cetak Pemesanan</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if($pembelian->status != 'draf' && $pembelian->status != 'closed')
                                        @if($pembelian->jenis != 'pengiriman')
                                        <div class="btn-group dropup">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-expanded="false">
                                                Tindakan
                                            </button>
                                            <div class="dropdown-menu">
                                                <!-- Dropdown menu links -->
                                                @hasanyrole('pemilik')
                                                @if($pembelian->jenis == 'faktur')
                                                    <a class="dropdown-item" href="{{ url('pembelian/pembayaran') . '/' . $pembelian->id }}">Terima Pembayaran</a>
                                                @elseif($pembelian->jenis == 'penawaran')
                                                    <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                                    <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pemesanan/' . $pembelian->id }}">Buat Pemesanan</a>
                                                @elseif($pembelian->jenis == 'pemesanan')
                                                    <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                                    <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                                @elseif($pembelian->jenis == 'pengiriman')
                                                    <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis. '/faktur/' . $pembelian->id }}">Buat Faktur</a>
                                                @endif
                                                @endhasallroles
                                                @hasanyrole('pergudangan')
                                                <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                                @endhasallroles
                                            </div>
                                        </div>
                                        @else
                                        <a class="btn btn-outline-primary" href="{{ url('pembelian/cetak/surat_jalan') . '/' . $pembelian->id }}" target="_blank">Cetak Surat Jalan</a>
                                        <a href="{{ url('pembelian').'/pengiriman/faktur/'.$pembelian->id }}" class="btn btn-primary">Buat Faktur</a>
                                        @endif
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @if(count($pembelian->detail_pembayaran_pembelian) != 0)
                        <div class="row my-4">
                            <div class="col">
                                Pembayaran
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table my-4">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>No.</th>
                                                <th>Setor Ke</th>
                                                <th>Cara pembayaran</th>
                                                <th>Status pembayaran</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pembelian->detail_pembayaran_pembelian as $v)
                                                <tr>
                                                    <th>{{ $v->pembayaran_pembelian->tanggal_transaksi }}</th>
                                                    <td>
                                                        <div>
                                                            <div class="row"><a href="{{ url('pembelian/receive_payment').'/'.$v->pembayaran_pembelian->id }}">{{ $v->pembayaran_pembelian->no_str }}</a></div>
                                                            <div class="row text-xs">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $v->pembayaran_pembelian->setor }}</td>
                                                    <td>{{ $v->pembayaran_pembelian->cara_pembayaran }}</td>
                                                    <td><button class="btn btn-sm btn-success">{{ $v->pembayaran_pembelian->status_pembayaran }}</button></td>
                                                    <td>Rp {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($faktur) && count($faktur) > 0)
                        <div class="table-responsive">
                            Faktur Pembelian
                            <table class="table my-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No.</th>
                                        <th>Tgl. jatuh tempo</th>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faktur as $v)
                                        <tr>
                                            <td>{{ $v->tanggal_transaksi }}</td>
                                            <td>
                                                <div>
                                                    <div class="row"><a
                                                            href="{{ url('pembelian/detail') . '/' . $v->id }}">{{ $v->no_str }}</a>
                                                    </div>
                                                    <div class="row text-xs">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $v->tanggal_jatuh_tempo }}</td>
                                            <td>
                                                <button class="btn btn-sm 
                                                @if ($v->status == 'open') btn-warning
                                                @elseif($v->status == 'partial') btn-info
                                                @elseif($v->status == 'paid') btn-success
                                                @elseif($v->status == 'overdue') btn-danger 
                                                @elseif($v->status == 'closed') btn-dark @endif
                                                ml-2">
                                                    {{ $v->status }}
                                                </button>
                                            </td>
                                            <td>Rp {{ number_format($v->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @if ($jurnal)
    <div class="modal fade" id="jurnalEntryModal" tabindex="-1" aria-labelledby="jurnalEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jurnalEntryModalLabel">{{ $jurnal->no_str }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Akun</th>
                                <th scope="col">Debit</th>
                                <th scope="col">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_debit = 0;
                                $total_kredit = 0;
                            @endphp
                            @foreach($jurnal->detail_jurnal as $v)
                            <tr>
                                <td>{{ $v->akun->nomor }} - {{ $v->akun->nama }}</td>
                                <td>Rp. {{ number_format($v->debit,2,',','.') }}</td>
                                <td>Rp. {{ number_format($v->kredit,2,',','.') }}</td>
                            </tr>
                            @php
                                $total_debit += $v->debit;
                                $total_kredit += $v->kredit;
                            @endphp
                            @endforeach
                            <tr>
                                <td>Total</td>
                                <td>Rp. {{ number_format($total_debit,2,',','.') }}</td>
                                <td>Rp. {{ number_format($total_kredit,2,',','.')    }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="modal fade" id="logUpdateModal" tabindex="-1" aria-labelledby="logUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog @if($pembelian->status != 'draf') modal-lg @else modal-sm text-center @endif">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logUpdateModalLabel">Log Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($status_update as $v)
                                <tr>
                                    <td>
                                        <h6 class="mb-0 text-xs username"><a href="#">{{ $v->name }}</a></h6>
                                        <p class="text-xs mb-0 email">{{ $v->email }}</p>
                                    </td>
                                    <td>{{ date('d M Y H:i:s', strtotime($v->created_at)) }}</td>
                                    <td>{{ $v->aksi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endsection
