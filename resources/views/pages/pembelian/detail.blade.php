@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
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
                                @endif
                            </button>
                        </h2>
                    </div>
                    <div class="card-body " style="font-size: 12px;">
                        <div class="row">
                            <div class="col-sm-2">Supplier</div>
                            <div class="col-sm-2"><strong>{{ $pembelian->nama_supplier }}</strong></div>
                            <div class="col-sm-2">Email</div>
                            <div class="col-sm-2"><strong>{{ $pembelian->email }}</strong></div>
                            @hasanyrole('pemilik')
                            <div class="col-sm-4 d-flex justify-content-end">
                                <h3>Sisa tagihan Rp. {{ number_format($pembelian->sisa_tagihan, 2, ',', '.') }} <br>
                                    @if ($jurnal)
                                    <a href="#" data-toggle="modal" data-target="#exampleModal">Lihat Jurnal Entry</a>
                                    @endif
                                </h3>
                            </div>
                            @endhasallroles
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-2">@if($pembelian->jenis == 'pengiriman') Alamat pengiriman @else Alamat supplier @endif</div>
                            <div class="col-sm-2"><strong>{{ $pembelian->alamat }}</strong></div>
                            <div class="col-sm-2">Tgl. Transaksi</div>
                            <div class="col-sm-2">
                                <strong>{{ date('d/m/Y', strtotime($pembelian->tanggal_transaksi)) }}</strong>
                            </div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">No Transaksi </div>
                            <div class="col-sm-2"><strong>{{ $pembelian->no_str }}</strong></div>
                        </div>
                        <div class="row my-2">
                            <div class="col-sm-2">@if($pembelian->alamat_pengiriman) Alamat pengiriman @endif</div>
                            <div class="col-sm-2"><strong>@if($pembelian->alamat_pengiriman) {{ $pembelian->alamat_pengiriman }} @endif </strong></div>
                            <div class="col-sm-2">
                                @if ($pembelian->jenis == 'faktur' || $pembelian->jenis == 'pemesanan')
                                    Tgl. Jatuh Tempo
                                @elseif($pembelian->jenis == 'penawaran')
                                    Tgl. kedaluarsa
                                @endif
                            </div>
                            <div class="col-sm-2">
                                @if($pembelian->tanggal_jatuh_tempo)
                                <strong>{{ date('d/m/Y', strtotime($pembelian->tanggal_jatuh_tempo)) }}</strong>
                                @endif
                            </div>
                            @if($pembelian->penawaran)
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                No. Penawaran
                            </div>
                            <div class="col-sm-2">
                                <a href="{{ url('pembelian/detail').'/'.$pembelian->penawaran->id }}">{{ $pembelian->penawaran->no_str }}</a>
                            </div>
                            @endif
                            @if($pembelian->pemesanan)
                            <div class="col-sm-2" style="margin-right: -25px !important;">
                                No. Pemesanan
                            </div>
                            <div class="col-sm-2">
                                <a href="{{ url('pembelian/detail').'/'.$pembelian->pemesanan->id }}">{{ $pembelian->pemesanan->no_str }}</a>
                            </div>
                            @endif
                        </div>
                        @if($pembelian->tanggal_pengiriman)
                        <div class="row my-2">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">Tgl. pengiriman</div>
                            <div class="col-sm-2">
                                <strong>{{ date('d/m/Y', strtotime($pembelian->tanggal_pengiriman)) }}</strong>
                            </div>
                        </div>
                        @else
                        <div class="row my-2">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">Gudang</div>
                            <div class="col-sm-2"><a href="{{ url('gudang/detail').'/'.$pembelian->id_gudang }}">{{ $pembelian->nama_gudang }}</a></div>
                        </div>
                        @endif
                        @if($pembelian->kirim_melalui)
                        <div class="row my-2">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">Kirim Melalui</div>
                            <div class="col-sm-2">
                                <strong>{{ $pembelian->kirim_melalui }}</strong>
                            </div>
                        </div>
                        @endif
                        @if($pembelian->no_pelacakan)
                        <div class="row my-2">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-2">No. pelacakan</div>
                            <div class="col-sm-2">
                                <strong>{{ $pembelian->no_pelacakan }}</strong> 
                            </div>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table my-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Deskripsi</th>
                                        <th>Kuantitas</th>
                                        <th>Unit</th>
                                        @if($pembelian->jenis != 'pengiriman')
                                        @hasanyrole('pemilik')
                                        <th>Harga Satuan</th>
                                        <th>Pajak</th>
                                        <th>Jumlah</th>
                                        @endhasallroles
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
                                            @hasanyrole('pemilik')
                                            <td>Rp. {{ number_format($v->harga_satuan, 2, ',', '.') }}</td>
                                            <td>@if($v->pajak != 0) PPN @endif</td>
                                            <td>Rp. {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                            @endhasallroles
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-7">
                                <div class="row my-3">
                                    <div class="col-sm-3">Pesan</div>
                                    <div class="col-sm-3">-</div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-sm-3">Memo</div>
                                    <div class="col-sm-3">-</div>
                                </div>
                            </div>
                            @if($pembelian->jenis == 'pengiriman')
                            <div class="col-sm-5">
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Ongkos kirim</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->ongkos_kirim, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                            @else
                            @hasanyrole('pemilik')
                            <div class="col-sm-5">
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Subtotal</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->subtotal, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                @if($pembelian->ppn)
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>PPN 11%</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->ppn, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                @endif
                                <hr>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Total</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->total, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                @if($pembelian->jumlah_terbayar != 0)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4>Jumlah Terbayar</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($pembelian->jumlah_terbayar, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                @endif
                                <hr>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h2>Sisa Tagihan</h2>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h2>Rp. {{ number_format($pembelian->sisa_tagihan, 2, ',', '.') }}</h2>
                                    </div>
                                </div>
                            </div>
                            @endhasallroles
                            @endif
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-7">Terakhir diubah oleh pada {{ $pembelian->updated_at }}</div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-6">
                                @hasanyrole('pemilik')
                                <form id="deleteForm" action="{{ url('pembelian/hapus') . '/' . $pembelian->id }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger"onclick="confirmDelete(event)">Hapus</button>
                                </form>
                                @endhasallroles
                            </div>
                            @if($pembelian->jenis == 'faktur' && $pembelian->status != 'paid')
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('pembelian').'/'.$pembelian->jenis.'/'.$pembelian->id }}" class="btn btn-outline-primary">Ubah</a>
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
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Penagihan</a>
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pemesanan/' . $pembelian->id }}">Buat Pemesanan</a>
                                        @elseif($pembelian->jenis == 'pemesanan')
                                        <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                        <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Penagihan</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('pembelian').'/'.$pembelian->jenis.'/'.$pembelian->id }}" class="btn btn-outline-primary">Ubah</a>
                                <div class="btn-group dropup mr-2">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                        Cetak
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('pembelian/penawaran/cetak') . '/' . $pembelian->id }}">Cetak Penawaran</a>
                                    </div>
                                </div>
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
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Penagihan</a>
                                            <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pemesanan/' . $pembelian->id }}">Buat Pemesanan</a>
                                        @elseif($pembelian->jenis == 'pemesanan')
                                        <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                        <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/faktur/' . $pembelian->id }}">Buat Penagihan</a>
                                        @endif
                                        @endhasallroles
                                        @hasanyrole('pergudangan')
                                        <a class="dropdown-item" href="{{ url('pembelian') .'/'.$pembelian->jenis . '/pengiriman/' . $pembelian->id }}">Buat Pengiriman</a>
                                        @endhasallroles
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(count($pembelian->detail_pembayaran_pembelian) != 0)
                        <div class="table-responsive">
                            Pembayaran
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
                                            <td>{{ $v->pembayaran_pembelian->status_pembayaran }}</td>
                                            <td>{{ $v->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @if(isset($faktur))
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $jurnal->no_str }}</h5>
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
