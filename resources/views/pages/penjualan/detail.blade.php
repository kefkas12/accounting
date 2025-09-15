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
                            <div class="form-group col-md-9 pr-2">
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
                            <div class="form-group col-md-3 pr-2 d-flex justify-content-end">
                                <a href="{{ url('penjualan') }}" class="btn btn-dark">Kembali ke Penjualan</a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">Pelanggan <br> @if($penjualan->nama_pelanggan)<strong>{{ $penjualan->nama_pelanggan }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">Tgl. Transaksi <br> @if($penjualan->tanggal_transaksi)<strong>{{ date('d/m/Y', strtotime($penjualan->tanggal_transaksi)) }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">Alamat <br> @if($penjualan->alamat)<strong>{{ $penjualan->alamat }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">Detail Alamat <br> @if($penjualan->detail_alamat)<strong>{{ $penjualan->detail_alamat }}</strong>@else <strong> - </strong> @endif</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">No RFQ <br> @if($penjualan->no_rfq)<strong>{{ $penjualan->no_rfq }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">PIC <br> @if($penjualan->pic)<strong>{{ $penjualan->pic }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">No Transaksi <br> @if($penjualan->no_str)<strong>{{ $penjualan->no_str }}</strong>@else <strong> - </strong> @endif</div>
                            
                        </div>
                        @if($penjualan->jenis != 'penawaran')
                        <div class="form-row">
                            <div class="form-group col-md-3" style="display:none">
                                Gudang <br>
                                @if($penjualan->nama_gudang)
                                <strong><a href="{{ url('gudang/detail').'/'.$penjualan->id_gudang }}">{{ $penjualan->nama_gudang }}</a></strong>
                                @else
                                <strong> - </strong> 
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                Kirim melalui <br> 
                                @if($penjualan->kirim_melalui)
                                <strong>{{ $penjualan->kirim_melalui }}</strong>
                                @else
                                <strong> - </strong>
                                @endif
                            </div>
                            <div class="form-group col-md-3">No. pelacakan <br> @if($penjualan->no_pelacakan)<strong>{{ $penjualan->no_pelacakan }}</strong>@else <strong> - </strong> @endif</div>
                            <div class="form-group col-md-3">
                                @if ($jurnal)
                                    <a href="#" data-toggle="modal" data-target="#jurnalEntryModal">Lihat Jurnal Entry</a>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($penjualan->penawaran)
                        <div class="form-row">
                            @if($penjualan->pemesanan)
                            <div class="form-group col-md-3">
                                No. Pemesanan <br>
                                <a href="{{ url('penjualan/detail').'/'.$penjualan->pemesanan->id }}">{{ $penjualan->pemesanan->no_str }}</a>
                            </div>
                            @endif
                            <div class="form-group col-md-3">
                                No. Penawaran <br>
                                <a href="{{ url('penjualan/detail').'/'.$penjualan->penawaran->id }}">{{ $penjualan->penawaran->no_str }}</a>
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        @if($penjualan->jenis == 'penawaran' && isset($produk_penawaran))
                                        <th>Produk Penawaran</th>
                                        @endif
                                        <th>Produk</th>
                                        <th>Deskripsi</th>
                                        @if($penjualan->jenis == 'penawaran')
                                        <th>Kuantitas</th>
                                        @else
                                            @if(isset($multiple_gudang))
                                                @if(isset($gudang))
                                                    @foreach($gudang as $v)
                                                    <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas {{ $v->nama }}</th>
                                                    @endforeach
                                                @else
                                                <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                                @endif
                                            @else
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                            @endif
                                        @endif
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
                                            @if($penjualan->jenis == 'penawaran' && isset($produk_penawaran))
                                            <th>@if(isset($v->produk_penawaran))<a href="{{ url('produk_penawaran').'/detail/'.$v->produk_penawaran->id }}">{{ $v->produk_penawaran->nama }}</a>@else - @endif</th>
                                            @endif
                                            <th>@if(isset($v->produk))<a href="{{ url('produk').'/detail/'.$v->produk->id }}">{{ $v->produk->nama }}</a>@else - @endif</th>
                                            <td>{{ $v->deskripsi }}</td>
                                            @if(isset($multiple_gudang))
                                            @php
                                                $stokMap = [];
                                                foreach ($v->stok_gudang as $w) {
                                                    $stokMap[$w->id_gudang] = $w->stok;
                                                }
                                            @endphp
                                            @foreach($gudang as $g)
                                            <td>{{ $stokMap[$g->id] ?? 0 }}</td>
                                            @endforeach
                                            @else
                                            <td>{{ $v->kuantitas }}</td>
                                            @endif
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
                        @if($penjualan->jenis == 'pengiriman')
                        <div class="row my-3">
                            <div class="col-sm-2">Pesan</div>
                            <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->pesan }} @else - @endif</div>
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
                            <div class="col-sm-2">Memo</div>
                            <div class="col-sm-2">@if($penjualan->pesan){{ $penjualan->memo }} @else - @endif</div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                Ongkos Kirim</div>
                            <div class="col-sm-2 d-flex justify-content-end"><strong>Rp. {{ number_format($penjualan->ongkos_kirim, 2, ',', '.') }}</strong></div>
                        </div>
                        <form method="POST" action="{{ url('penjualan/status_pengiriman') }}">
                            @csrf
                            <div class="row my-3">
                                <div class="col-sm-2">
                                @if(isset($dokumen_penjualan))
                                    @foreach($dokumen_penjualan as $v) 
                                        Dokumen {{ $loop->index+1 }} <br>
                                    @endforeach
                                @endif
                                </div>
                                <div class="col-sm-2">
                                @if(isset($dokumen_penjualan))
                                    @foreach($dokumen_penjualan as $v) 
                                    <a href="{{ asset('storage/uploads') }}/{{ $v->nama }}" target="_blank"> {{ $v->dokumen->nama }}</a> <br>
                                    @endforeach
                                @endif
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4 has-float-label">
                                    <span>Update Status Pengiriman  </span>
                                    <select class="form-control" name="status_pengiriman" id="status_pengiriman">
                                        @foreach($pengaturan_status_pengiriman as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4 has-float-label">
                                    <span>Gudang </span>
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
                        <div class="card-body border-0 text-sm">
                            <div class="form-row">
                                <label class="form-group col-md-3 pr-2">
                                    <label for="pesan">Pesan <br> <strong>@if($penjualan->pesan){{ $penjualan->pesan }} @else - @endif</strong></label>
                                </label>
                                <label class="form-group col-md-5 pr-2">
                                    <label for="memo">Memo <br> <strong>@if($penjualan->pesan){{ $penjualan->memo }} @else - @endif</strong></label>
                                </label>
                                <div class="form-group col-md-4">
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Subtotal</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->subtotal, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Diskon per baris</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->diskon_per_baris, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>PPN 11%</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->ppn, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Total</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->total, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    @if($penjualan->jenis == 'penagihan')
                                    <div class="row mb-2">
                                        <div class="col">
                                            <span>Jumlah Terbayar</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->jumlah_terbayar, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    @endif
                                    @if($penjualan->jumlah_terbayar != 0)
                                    <div class="row mb-2">
                                        <div class="col">
                                            <span>Sisa Tagihan</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <strong>Rp. {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                            @if ($penjualan->diskon_per_baris && isset($dokumen_penjualan))
                            <div class="row my-3">
                                <div class="col-sm-2">
                                    @foreach($dokumen_penjualan as $v) 
                                        {{ $v->dokumen->nama }} 
                                    @endforeach
                                </div>
                                <div class="col-sm-2">
                                    @foreach($dokumen_penjualan as $v) 
                                    <a href="{{ asset('storage/uploads') }}/{{ $v->nama }}" target="_blank">Dokumen {{ $loop->index+1 }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if (!$penjualan->diskon_per_baris && isset($dokumen_penjualan))
                            <div class="row my-3">
                                <div class="col-sm-2">
                                    @foreach($dokumen_penjualan as $v) 
                                        {{ $v->dokumen->nama }} 
                                    @endforeach
                                </div>
                                <div class="col-sm-2">
                                    @foreach($dokumen_penjualan as $v) 
                                    <a href="{{ asset('storage/uploads') }}/{{ $v->nama }}" target="_blank">Dokumen {{ $loop->index+1 }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endif
                        @if(isset($log))
                        <div class="row my-3">
                            <div class="col-sm-7">
                                <a href="#" data-toggle="modal" data-target="#logUpdateModal">Terakhir diproses oleh {{ $log->name }} pada {{ date('d F Y h:i:s', strtotime($log->created_at)) }}</a>
                            </div>
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
                                <a href="#" class="btn btn-outline-primary">Upload</a>
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
                                @if($penjualan->status != 'draf' && $penjualan->status != 'closed')
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
                                @if($penjualan->status == 'draf' || $penjualan->status == 'open')
                                <a href="{{ url('penjualan').'/'.$penjualan->jenis.'/'.$penjualan->id }}" class="btn btn-outline-primary">Ubah</a>
                                @endif
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
                                @if($penjualan->status != 'draf' && $penjualan->status != 'closed')
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
                                Penagihan Penjualan
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
    <div class="modal fade" id="logUpdateModal" tabindex="-1" aria-labelledby="logUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog @if($penjualan->status != 'draf') modal-lg @else modal-sm text-center @endif">
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
                                    <td>{{ $v->created_at }}</td>
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
