@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <style>
        .form-control {
            height: 40px !important;

        }

        .table th,
        .table td {
            padding: 10px !important;
        }
    </style>
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row">
                            <div class="col-sm-8">Transaksi <br>
                                <h2>{{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->no_str }}</h2></div>
                            <div class="col-sm-4 d-flex justify-content-end">
                                <h1 class="text-success">LUNAS</h1>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body" style="padding: 0px !important;">
                        <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;">
                            <div class="row">
                                <div class="col-sm-3 mt-2">Supplier</div>
                                <div class="col-sm-4 mt-2">Setor Ke</div>
                                <div class="col-sm-4 mt-2 d-flex justify-content-end"></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3"><strong><a href="{{ url('supplier/detail').'/'.$detail_pembayaran_pembelian[0]->pembelian->id_supplier }}">{{ $detail_pembayaran_pembelian[0]->pembelian->kontak->nama }}</a></strong></div>
                                <div class="col-sm-5"><strong>{{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->setor }}</strong>
                                </div>
                                <div class="col-sm-4 d-flex justify-content-end">
                                    <h2>Total <span id="total_pembayaran" style="color:#2980b9">Rp. {{ number_format($detail_pembayaran_pembelian[0]->pembayaran_pembelian->subtotal,2,',','.') }}</span><br> 
                                        <a href="#" class="text-xs" data-toggle="modal" data-target="#exampleModal">Lihat Jurnal Entry</a></h2>
                                </div>
                            </div>
                            <div class="row">
                            </div>
                        </div>
                        <div class="mt-3 mb-5">
                            <div class="row">
                                <div class="col-sm-3"><strong>Cara Pembayaran</strong></div>
                                <div class="col-sm-3"><strong>Tgl Transaksi Pembayaran</strong></div>
                                <div class="col-sm-3"><strong>Tgl. Jatuh Tempo</strong></div>
                                <div class="col-sm-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    {{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->cara_pembayaran }}
                                </div>
                                <div class="col-sm-3">{{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->tanggal_transaksi }}</div>
                                <div class="col-sm-3">{{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->tanggal_jatuh_tempo }}</div>
                                <div class="col-sm-3"></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table my-4">
                                <thead style="background-color: #E0F7FF">
                                    <tr>
                                        <th>Number</th>
                                        <th>Deskripsi</th>
                                        <th class="d-flex justify-content-end">Jumlah (in IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $subtotal = 0;
                                    @endphp
                                    @foreach($detail_pembayaran_pembelian as $v)
                                    <tr>
                                        <td><a href="{{ url('pembelian/detail').'/'.$v->pembelian->id }}">{{ $v->pembelian->no_str }}</a></td>
                                        <td>{{ $v->pembelian->memo }}</td>
                                        <td class="d-flex justify-content-end">Rp. {{ number_format($v->jumlah,2,',','.') }}</td>
                                    </tr>
                                    @php
                                        $subtotal += $v->jumlah;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col ">
                                <div class="row mb-3">
                                    <div class="col">
                                        <b><span>Total</span></b>
                                    </div>
                                    <div class="col d-flex justify-content-end">
                                        <b><span id="subtotal">Rp. {{ number_format($subtotal,2,',','.') }}</span></b>
                                        <input type="text" id="input_subtotal" name="subtotal" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                @hasanyrole('pemilik')
                                <form id="deleteForm" action="{{ url('pembelian/receive_payment/hapus') . '/' . $pembayaran_pembelian->id }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger"onclick="confirmDelete(event)">Hapus</button>
                                </form>
                                @endhasallroles
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('pembelian/detail') . '/' . $detail_pembayaran_pembelian[0]->id_pembelian }}" class="btn btn-dark">Kembali</a>
                                <a href="{{ url('pembelian/pembayaran') . '/' . $pembayaran_pembelian->id }}" class="btn btn-outline-primary">Ubah</a>
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
                    <h4 class="modal-title" id="exampleModalLabel"><div class="text-xs mb-1">Laporan Jurnal</div><strong>{{ $detail_pembayaran_pembelian[0]->pembayaran_pembelian->no_str }}</strong></h4>
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

        var total = {};

        function change_total(no) {
            total[no] = parseInt($('#total_' + no).val());
            load();
        }

        function load() {
            result_total = 0;
            for (var key in total) {
                result_total += total[key];
            }
            $('#subtotal').text(rupiah(result_total));
            $('#total_pembayaran').text(rupiah(result_total));

            $('#input_subtotal').val(result_total);
        }
    </script>
@endsection
