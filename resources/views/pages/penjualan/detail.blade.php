@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        Penagihan Penjualan #{{ $penjualan->no }}
                        <button
                            class="btn btn-sm 
                        @if ($penjualan->status == 'open') btn-warning
                        @elseif($penjualan->status == 'partial') btn-info
                        @elseif($penjualan->status == 'paid') btn-success
                        @elseif($penjualan->status == 'overdue') btn-danger @endif
                        ml-2">
                            @if ($penjualan->status == 'open')
                                Belum Dibayar
                            @elseif($penjualan->status == 'partial')
                                Terbayar Sebagian
                            @elseif($penjualan->status == 'paid')
                                Lunas
                            @elseif($penjualan->status == 'overdue')
                                Lewat Jatuh Tempo
                            @endif
                        </button>
                    </div>
                    <div class="card-body " style="font-size: 12px;">
                        <div class="row">
                            <div class="col-sm-2">Pelanggan</div>
                            <div class="col-sm-2"><strong>{{ $penjualan->nama_pelanggan }}</strong></div>
                            <div class="col-sm-2">Email</div>
                            <div class="col-sm-2"><strong>{{ $penjualan->email }}</strong></div>
                            <div class="col-sm-4 d-flex justify-content-end">
                                <h3>Sisa tagihan Rp. {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }} <br> 
                                    <a href="#" data-toggle="modal" data-target="#exampleModal">Lihat Jurnal Entry</a></h3>
                                
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-2">Alamat penagihan</div>
                            <div class="col-sm-2"><strong>{{ $penjualan->alamat }}</strong></div>
                            <div class="col-sm-2">Tgl. Transaksi</div>
                            <div class="col-sm-2">
                                <strong>{{ date('d/m/Y', strtotime($penjualan->tanggal_transaksi)) }}</strong></div>
                            <div class="col-sm-2" style="margin-right: -25px !important;">No Transaksi </div>
                            <div class="col-sm-2"><strong>{{ $penjualan->no_str }}</strong></div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2">Tgl. Jatuh Tempo</div>
                            <div class="col-sm-2">
                                <strong>{{ date('d/m/Y', strtotime($penjualan->tanggal_jatuh_tempo)) }}</strong></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table my-4">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kuantitas</th>
                                        <th>Unit</th>
                                        <th>Harga Satuan</th>
                                        <th>Diskon</th>
                                        <th>Pajak</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualan->detail_penjualan as $v)
                                        <tr>
                                            <th>{{ $v->produk->nama }}</th>
                                            <td>{{ $v->kuantitas }}</td>
                                            <td>Buah</td>
                                            <td>Rp. {{ number_format($v->harga_satuan, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($v->diskon_per_baris)
                                                    {{ $v->diskon_per_baris }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                            <td>PPN</td>
                                            <td>Rp. {{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-7"></div>
                            <div class="col-sm-5">
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Subtotal</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($penjualan->subtotal, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>PPN 11%</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($penjualan->ppn, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h4>Total</h4>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h4>Rp. {{ number_format($penjualan->total, 2, ',', '.') }}</h4>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col-sm-6">
                                        <h2>Sisa Tagihan</h2>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <h2>Rp. {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-6">
                                <a href="{{ url('penjualan/hapus') . '/' . $penjualan->id }}"
                                    class="btn btn-outline-danger ">Hapus</a>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <button class="btn btn-outline-primary">Ubah</button>
                                <a href="{{ url('penjualan/pembayaran') . '/' . $penjualan->id }}"
                                    class="btn btn-primary">Terima Pembayaran</a>
                            </div>
                        </div>
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
                                    @foreach ($penjualan->detail_pembayaran_penjualan as $v)
                                        <tr>
                                            <td>{{ $v->pembayaran_penjualan->tanggal_transaksi }}</td>
                                            <td>
                                                <div>
                                                    <div class="row"><a href="{{ url('penjualan/receive_payment').'/'.$v->pembayaran_penjualan->id }}">{{ $v->pembayaran_penjualan->no_str }}</a></div>
                                                    <div class="row text-xs">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $v->pembayaran_penjualan->setor }}</td>
                                            <td>{{ $v->pembayaran_penjualan->cara_pembayaran }}</td>
                                            <td>{{ $v->pembayaran_penjualan->status_pembayaran }}</td>
                                            <td>{{ $v->jumlah }}</td>
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
@endsection
