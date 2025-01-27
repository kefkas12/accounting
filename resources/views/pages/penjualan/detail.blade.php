@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row justify-content-between">
                            <div class="col-sm-6">
                                <h2>
                                    @if ($penjualan->jenis == 'penagihan')
                                        Penagihan
                                    @elseif($penjualan->jenis == 'penawaran')
                                        Penawaran
                                    @elseif($penjualan->jenis == 'pemesanan')
                                        Pemesanan
                                    @elseif($penjualan->jenis == 'pengiriman')
                                        Pengiriman
                                    @endif
                                    Penjualan #{{ $penjualan->no }}
                                    @if ($penjualan->status == 'open') 
                                    <button class="btn btn-sm text-white ml-2" style="background-color: #F59E0B">
                                    @elseif($penjualan->status == 'partial')
                                    <button class="btn btn-sm btn-info ml-2">
                                    @elseif($penjualan->status == 'paid')
                                    <button class="btn btn-sm btn-success ml-2">
                                    @elseif($penjualan->status == 'overdue')
                                    <button class="btn btn-sm btn-danger ml-2">
                                    @elseif($penjualan->status == 'closed')
                                    <button class="btn btn-sm btn-dark ml-2">
                                    @elseif($penjualan->status == 'draf')
                                    <button class="btn btn-sm ml-2 text-white" style="background-color: #71717A;">
                                    @endif
                                    
                                        @if ($penjualan->status == 'open')
                                            Belum ditagih
                                        @elseif($penjualan->status == 'partial')
                                            Terbayar Sebagian
                                        @elseif($penjualan->status == 'paid')
                                            Lunas
                                        @elseif($penjualan->status == 'overdue')
                                            Lewat Jatuh Tempo
                                        @elseif($penjualan->status == 'closed')
                                            Selesai
                                        @elseif($penjualan->status == 'draf')
                                            Draf
                                        @endif
                                    </button>
                                </h2>
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;"></div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <a href="{{ URL::previous() }}" class="btn btn-dark">Kembali ke <span class="text-capitalize">{{ $penjualan->jenis }}</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body " style="font-size: 14px;">
                        <div class="row">
                            <div class="col-sm-2">Pelanggan <br> PIC</div>
                            <div class="col-sm-2"><strong>{{ $penjualan->nama_pelanggan }}</strong> <br> <strong>{{ $penjualan->pic }}</strong></div>
                            <div class="col-sm-2">Email</div>
                            <div class="col-sm-2"><strong>{{ $penjualan->email }}</strong></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">@if($penjualan->jenis != 'pengiriman') @if($penjualan->jenis == 'penawaran') Total @else Sisa tagihan @endif @else Ongkos Kirim @endif</div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                @if($penjualan->jenis != 'pengiriman')<strong> Rp. {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }}</strong> @else <strong> Rp. {{ number_format($penjualan->ongkos_kirim, 2, ',', '.') }}</strong> @endif
                            </div>
                        </div>
                        @if($penjualan->no_rfq || $jurnal)
                        <div class="row mb-2">
                            <div class="col-sm-2">@if($penjualan->no_rfq)No RFQ @endif</div>
                            <div class="col-sm-2">@if($penjualan->no_rfq)<strong>{{ $penjualan->no_rfq }}</strong>@endif</div>
                            <div class="col-sm-6" style="margin-right: -25px !important;"></div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                @if ($jurnal)
                                <a href="#" data-toggle="modal" data-target="#jurnalEntryModal">Lihat Jurnal
                                        Entry</a>
                                @endif
                            </div>
                        </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-sm-2">Alamat penagihan</div>
                            <div class="col-sm-2">
                                @if ($penjualan->alamat)
                                    <strong>{{ $penjualan->alamat }}</strong>
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Tgl. Transaksi</div>
                            <div class="col-sm-2">
                                <strong>{{ date('d/m/Y', strtotime($penjualan->tanggal_transaksi)) }}</strong>
                            </div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">No Transaksi </div>
                            <div class="col-sm-2 d-flex justify-content-end"><strong>{{ $penjualan->no_str }}</strong></div>
                        </div>
                        <div class="row my-3">
                            @if($penjualan->jenis == 'pemesanan' || $penjualan->jenis == 'pengiriman')
                            <div class="col-sm-2">Alamat pengiriman</div>
                            <div class="col-sm-2">
                                @if ($penjualan->alamat_pengiriman)
                                    <strong>{{ $penjualan->alamat_pengiriman }}</strong>
                                @else
                                    -
                                @endif
                            </div>
                            @endif
                            <div class="col-sm-2">
                                @if(isset($penjualan->tanggal_pengiriman))
                                    Tgl. pengiriman
                                @endif
                            </div>
                            <div class="col-sm-2">
                                @if(isset($penjualan->tanggal_pengiriman))
                                <strong>{{ date('d/m/Y',strtotime($penjualan->tanggal_pengiriman)) }}</strong>
                                @endif
                            </div>
                            @if($penjualan->penawaran)
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                No. Penawaran
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <a href="{{ url('penjualan/detail').'/'.$penjualan->penawaran->id }}">{{ $penjualan->penawaran->no_str }}</a>
                            </div>
                            @endif
                            @if($penjualan->jenis != 'pengiriman')
                            @if($penjualan->pemesanan)
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                No. Pemesanan
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <a href="{{ url('penjualan/detail').'/'.$penjualan->pemesanan->id }}">{{ $penjualan->pemesanan->no_str }}</a>
                            </div>
                            @endif
                            @endif
                        </div>
                        @if($penjualan->jenis == 'pemesanan' || $penjualan->jenis == 'pengiriman')
                        <div class="row my-3">
                            <div class="col-sm-2">
                                @if($penjualan->kirim_melalui)
                                Kirim melalui
                                @endif
                            </div>
                            <div class="col-sm-2">
                                @if($penjualan->kirim_melalui)
                                <strong>{{ $penjualan->kirim_melalui }}</strong>
                                @else
                                -
                                @endif
                            </div>
                            @if($penjualan->id_gudang)
                            <div class="col-sm-2">Gudang</div>
                            <div class="col-sm-2">
                                <a href="{{ url('gudang/detail').'/'.$penjualan->id_gudang }}">{{ $penjualan->nama_gudang }}</a>
                            </div>
                            @endif
                            @if($penjualan->pemesanan)
                            <div class="col-sm-2" >
                                No. Pemesanan
                            </div>
                            <div class="col-sm-2 @if($penjualan->jenis != 'pengiriman') d-flex justify-content-end @endif">
                                <a href="{{ url('penjualan/detail').'/'.$penjualan->pemesanan->id }}">{{ $penjualan->pemesanan->no_str }}</a>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($penjualan->no_pelacakan || ($penjualan->jenis == 'pengiriman' && isset($penjualan->nama_gudang)))
                        <div class="row my-3">
                            <div class="col-sm-2">
                                @if($penjualan->no_pelacakan)
                                No. pelacakan
                                @endif
                            </div>
                            <div class="col-sm-2">
                                @if($penjualan->no_pelacakan)
                                <strong>{{ $penjualan->no_pelacakan }}</strong>
                                @else
                                -
                                @endif
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table my-4">
                                <thead class="thead-light">
                                    <tr>
                                        @if(isset($produk_penawaran))
                                        <th>Produk Penawaran</th>
                                        @endif
                                        <th>Produk</th>
                                        <th>Deskripsi</th>
                                        <th>Kuantitas</th>
                                        <th>Unit</th>
                                        @if($penjualan->jenis != 'pengiriman')
                                        <th>Harga Satuan</th>
                                        <th>Diskon</th>
                                        <th style="margin-right: -25px !important;">Pajak</th>
                                        <th style="margin-right: 25px !important;">Jumlah</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualan->detail_penjualan as $v)
                                        <tr>
                                            @if(isset($produk_penawaran))
                                            <th>@if(isset($v->produk_penawaran))<a href="{{ url('produk_penawaran').'/detail/'.$v->produk_penawaran->id }}">{{ $v->produk_penawaran->nama }}</a>@else - @endif</th>
                                            @endif
                                            <th>@if(isset($v->produk))<a href="{{ url('produk').'/detail/'.$v->produk->id }}">{{ $v->produk->nama }}</a>@else - @endif</th>
                                            <td>{{ $v->deskripsi }}</td>
                                            <td>{{ $v->kuantitas }}</td>
                                            <td>Buah</td>
                                            @if($penjualan->jenis != 'pengiriman')
                                            <td>Rp. {{ number_format($v->harga_satuan, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($v->diskon_per_baris)
                                                    {{ $v->diskon_per_baris }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td>
                                                @if ($v->pajak != 0)
                                                    PPN
                                                @endif
                                            </td>
                                            <td style="margin-right: 25px !important;">Rp. {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        @if($penjualan->jenis == 'pengiriman')
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Log Pengiriman
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <a href="#" data-toggle="modal" data-target="#logPengirimanModal">Lihat Log Pengiriman</a>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-2">Pesan</div>
                            <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->pesan }} @else - @endif</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Ongkos Kirim</div>
                            <div class="col-sm-2 d-flex justify-content-end"><strong>Rp. {{ number_format($penjualan->ongkos_kirim, 2, ',', '.') }}</strong></div>
                        </div>
                        <form method="POST" action="{{ url('penjualan/status_pengiriman') }}">
                            @csrf
                            <div class="row my-3">
                                <div class="col-sm-2">Memo</div>
                                <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->memo }} @else - @endif</div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-2" style="margin-right: -25px !important;">Update Status Pengiriman</div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="status_pengiriman" id="status_pengiriman">
                                        @foreach($pengaturan_status_pengiriman as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-2" style="margin-right: -25px !important;">Gudang</div>
                                <div class="col-sm-2">
                                    <select class="form-control" name="gudang" id="gudang">
                                        @foreach($gudang as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="id_pengiriman_penjualan" value="{{ $penjualan->id }}">
                                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="row my-3">
                            <div class="col-sm-2">Pesan</div>
                            <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->pesan }} @else - @endif</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Subtotal
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->subtotal, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-2">Memo</div>
                            <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->memo }} @else - @endif</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Diskon per baris
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->diskon_per_baris, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if ($penjualan->diskon_per_baris)
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                PPN 11%
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->ppn, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Total
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->total, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if($penjualan->jenis == 'penagihan')
                        <hr>
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Jumlah Terbayar
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->jumlah_terbayar, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($penjualan->jumlah_terbayar != 0)
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Sisa Tagihan
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <strong>Rp. {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }}</strong>
                            </div>
                        </div>
                        @endif
                        @endif
                {{-- </div>
                </div> --}}
                        <hr>
                        @if(isset($log))
                        <div class="row my-3">
                            <div class="col-sm-7">Terakhir diubah oleh {{ $log->name }} pada {{ date('d F Y h:i:s', strtotime($log->created_at)) }}</div>
                        </div>
                        @endif
                        @if(isset($pengiriman))
                        <div class="table-responsive mt-3">
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
                                                            href="{{ url('penjualan/detail') . '/' . $v->id }}">{{ $v->no_str }}</a>
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
                        <div class="row my-4">
                            <div class="col-sm-6">
                                <form id="deleteForm" action="{{ url('penjualan/hapus') . '/' . $penjualan->id }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger"onclick="confirmDelete(event)">Hapus</button>
                                </form>
                            </div>
                            @if($penjualan->jenis == 'penagihan' && $penjualan->status != 'paid')
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('penjualan').'/'.$penjualan->jenis.'/'.$penjualan->id }}" class="btn btn-outline-primary">Ubah</a>
                                <div class="btn-group dropup mr-2">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                        Cetak
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Dropdown menu links -->
                                        @if($penjualan->jenis == 'penagihan')
                                            <a class="dropdown-item" href="{{ url('penjualan/cetak/penagihan') . '/' . $penjualan->id }}" target="_blank">Cetak Faktur</a>
                                            <a class="dropdown-item" href="{{ url('penjualan/cetak/surat_jalan') . '/' . $penjualan->id }}" target="_blank">Cetak Surat Jalan</a>
                                        @elseif($penjualan->jenis == 'penawaran')
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pemesanan/' . $penjualan->id }}">Buat Pemesanan</a>
                                        @elseif($penjualan->jenis == 'pemesanan')
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pengiriman/' . $penjualan->id }}">Buat Pengiriman</a>
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                        @endif
                                    </div>
                                </div>
                                @if($penjualan->status != 'draf')
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                        Tindakan
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Dropdown menu links -->
                                        @if($penjualan->jenis == 'penagihan')
                                            <a class="dropdown-item" href="{{ url('penjualan/pembayaran') . '/' . $penjualan->id }}">Terima Pembayaran</a>
                                        @elseif($penjualan->jenis == 'penawaran')
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pemesanan/' . $penjualan->id }}">Buat Pemesanan</a>
                                        @elseif($penjualan->jenis == 'pemesanan')
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pengiriman/' . $penjualan->id }}">Buat Pengiriman</a>
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('penjualan').'/'.$penjualan->jenis.'/'.$penjualan->id }}" class="btn btn-outline-primary">Ubah</a>
                                @if($penjualan->jenis == 'penawaran' || $penjualan->jenis == 'pemesanan')
                                <div class="btn-group dropup mr-2">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                        Cetak
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($penjualan->jenis == 'penawaran')
                                        <a target="_blank" class="dropdown-item" href="{{ url('penjualan/penawaran/cetak') . '/' . $penjualan->id }}">Cetak Penawaran</a>
                                        @elseif($penjualan->jenis == 'pemesanan')
                                        <a target="_blank" class="dropdown-item" href="{{ url('penjualan/pemesanan/cetak') . '/' . $penjualan->id }}">Cetak Pemesanan</a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($penjualan->status != 'draf')
                                @if($penjualan->jenis != 'pengiriman')
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                        Tindakan
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- Dropdown menu links -->
                                        @if($penjualan->jenis == 'penagihan')
                                            <a class="dropdown-item" href="{{ url('penjualan/pembayaran') . '/' . $penjualan->id }}">Terima Pembayaran</a>
                                        @elseif($penjualan->jenis == 'penawaran')
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                            <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pemesanan/' . $penjualan->id }}">Buat Pemesanan</a>
                                        @elseif($penjualan->jenis == 'pemesanan')
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/pengiriman/' . $penjualan->id }}">Buat Pengiriman</a>
                                        <a class="dropdown-item" href="{{ url('penjualan') .'/'.$penjualan->jenis . '/penagihan/' . $penjualan->id }}">Buat Penagihan</a>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <a class="btn btn-outline-primary" href="{{ url('penjualan/cetak/surat_jalan') . '/' . $penjualan->id }}" target="_blank">Cetak Surat Jalan</a>
                                <a href="{{ url('penjualan').'/pengiriman/penagihan/'.$penjualan->id }}" class="btn btn-primary">Buat penagihan</a>
                                @endif
                                @endif
                            </div>
                            @endif
                        </div>
                        @if(count($penjualan->detail_pembayaran_penjualan) != 0)
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
                                            @foreach ($penjualan->detail_pembayaran_penjualan as $v)
                                                <tr>
                                                    <td>{{ date('d/m/Y', strtotime($v->pembayaran_penjualan->tanggal_transaksi)) }}</td>
                                                    <td>
                                                        <div>
                                                            <div class="row"><a
                                                                    href="{{ url('penjualan/receive_payment') . '/' . $v->pembayaran_penjualan->id }}">{{ $v->pembayaran_penjualan->no_str }}</a>
                                                            </div>
                                                            <div class="row text-xs">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $v->pembayaran_penjualan->setor }}</td>
                                                    <td>{{ $v->pembayaran_penjualan->cara_pembayaran }}</td>
                                                    <td><button class="btn btn-sm btn-success">{{ $v->pembayaran_penjualan->status_pembayaran }}</button></td>
                                                    <td>Rp {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($penagihan) && count($penagihan) > 0)
                        <div class="row my-4">
                            <div class="col">
                                Penagihan Penjualan\
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    
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
                                            @foreach ($penagihan as $v)
                                                <tr>
                                                    <td>{{ $v->tanggal_transaksi }}</td>
                                                    <td>
                                                        <div>
                                                            <div class="row"><a
                                                                    href="{{ url('penjualan/detail') . '/' . $v->id }}">{{ $v->no_str }}</a>
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
                            </div>
                        </div>
                        @endif
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @if ($jurnal)
        <div class="modal fade" id="jurnalEntryModal" tabindex="-1" aria-labelledby="jurnalEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog @if($penjualan->status != 'draf') modal-lg @else modal-sm text-center @endif">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jurnalEntryModalLabel">@if($penjualan->status == 'draf')Journal entry belum tersedia @else {{ $jurnal->no_str }} @endif</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($penjualan->status == 'draf')
                        Anda dapat melihat journal entry setelah transaksi ini disetujui.
                        @else
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
                                @foreach ($jurnal->detail_jurnal as $v)
                                    <tr>
                                        <td>{{ $v->akun->nomor }} - {{ $v->akun->nama }}</td>
                                        <td>Rp. {{ number_format($v->debit, 2, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($v->kredit, 2, ',', '.') }}</td>
                                    </tr>
                                    @php
                                        $total_debit += $v->debit;
                                        $total_kredit += $v->kredit;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td>Rp. {{ number_format($total_debit, 2, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($total_kredit, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(isset($status_pengiriman))
    <div class="modal fade" id="logPengirimanModal" tabindex="-1" aria-labelledby="logPengirimanModalLabel" aria-hidden="true">
        <div class="modal-dialog @if($penjualan->status != 'draf') modal-lg @else modal-sm text-center @endif">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logPengirimanModalLabel">Log Pengiriman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($status_pengiriman as $v)
                                <tr>
                                    <td>
                                        <h6 class="mb-0 text-xs username"><a href="#">{{ $v->nama_status_pengiriman }}</a></h6>
                                        <p class="text-xs mb-0 email">{{ $v->nama_gudang }}</p>
                                    </td>
                                    <td>{{ $v->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
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
