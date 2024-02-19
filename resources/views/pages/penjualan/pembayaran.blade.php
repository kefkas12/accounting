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
                        Transaksi <br>
                        <h2>Penerimaan Bayaran</h2>
                    </div>
                    <form method="POST" action="{{ url('penjualan/pembayaran') }}">
                        @csrf
                        <div class="card-body" style="padding: 0px !important;">
                            <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;">
                                <div class="row">
                                    <div class="col-sm-3 mt-2">Pelanggan</div>
                                    <div class="col-sm-4 mt-2">Setor Ke</div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end"></div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-3"><strong>{{ $pembayaran->nama_pelanggan }}</strong></div>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="setor_ke" id="setor_ke">
                                            @foreach ($akun as $v)
                                                <option value="{{ $v->id }}">({{ $v->nomor }}) - {{ $v->nama }}
                                                    ({{ $v->nama_kategori }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-end">
                                        <h2>Total <span id="total_pembayaran" style="color:#2980b9">Rp 0,00</span></h2>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
                            </div>
                            <hr>
                            <div class="mb-5">
                                <div class="row">
                                    <div class="col-sm-3">Cara Pembayaran</div>
                                    <div class="col-sm-3">Tgl Transaksi Pembayaran</div>
                                    <div class="col-sm-3">Tgl. Jatuh Tempo</div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" name="cara_pembayaran" id="cara_pembayaran">
                                            <option>Kas Tunai</option>
                                            <option>Cek & Giro</option>
                                            <option>Transfer Bank</option>
                                            <option>Kartu Kredit</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3"><input type="date" class="form-control" id="tanggal_transaksi"
                                            name="tanggal_transaksi" value="{{ date('Y-m-d') }}"></div>
                                    <div class="col-sm-3"><input type="date" class="form-control" id="tanggal_jatuh_tempo"
                                            name="tanggal_jatuh_tempo"></div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table my-4">
                                    <thead style="background-color: #E0F7FF">
                                        <tr>
                                            <th>Number</th>
                                            <th>Tgl Jatuh Tempo</th>
                                            <th>Total</th>
                                            <th>Sisa Tagihan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembayaran->penjualan as $v)
                                            @if($v->sisa_tagihan != 0)
                                            <tr>
                                                <td>{{ $v->no_str }}</td>
                                                <td>{{ $v->tanggal_jatuh_tempo }}</td>
                                                <td>{{ $v->total }}</td>
                                                <td>{{ $v->sisa_tagihan }}</td>
                                                <td>
                                                    <input type="text" name="id_penjualan[]" value="{{ $v->id }}" hidden>
                                                    <input type="number" class="form-control"
                                                        name="total[]" id="total_{{ $v->id }}"
                                                        onkeyup="change_total({{ $v->id }})" 
                                                        @if($v->id == $penjualan->id) value="{{ $v->sisa_tagihan }}" @endif>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col ">
                                    <hr class="bg-white">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <span>Total</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="subtotal">Rp 0,00</span>
                                            <input type="text" id="input_subtotal" name="subtotal" hidden>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6 d-flex justify-content-end">
                                    <a href="{{ url('penjualan') }}" class="btn btn-outline-danger">Batal</a>
                                    <button class="btn btn-primary">Buat Penerimaan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $( document ).ready(function() {
            change_total({{ $penjualan->id }});
        });
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
