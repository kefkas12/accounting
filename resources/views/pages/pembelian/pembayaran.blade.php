@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Page content -->
    <style>
        .table th,
        .table td {
            padding: 10px !important;
        }
    </style>
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-body border-0 text-sm">
                        <div class="form-row">
                            <div class="form-group col-md-9 pr-2">
                                <a href="{{ url('pembelian') }}">Transaksi</a>
                                <h2>Pengiriman Pembayaran</h2>
                            </div>
                        </div>
                    </div>
                    <form method="POST" id="insertForm"
                        action="{{ url('pembelian/pembayaran') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <div class="card-body " style="padding: 0px !important;">
                            <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;">
                                <div class="row">
                                    <div class="col-sm-3 mt-2">Supplier</div>
                                    <div class="col-sm-4 mt-2">Bayar Dari</div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end"></div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-3"><strong>{{ $pembayaran->nama_supplier }}</strong></div>
                                    <div class="col-sm-5">
                                        <select class="form-control form-control-sm" name="setor_ke" id="setor_ke">
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
                                        <select class="form-control form-control-sm" name="cara_pembayaran" id="cara_pembayaran">
                                            <option>Kas Tunai</option>
                                            <option>Cek & Giro</option>
                                            <option>Transfer Bank</option>
                                            <option>Kartu Kredit</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3"><input type="date" class="form-control form-control-sm" id="tanggal_transaksi"
                                            name="tanggal_transaksi" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}"></div>
                                    <div class="col-sm-3"><input type="date" class="form-control form-control-sm" id="tanggal_jatuh_tempo"
                                            name="tanggal_jatuh_tempo" style="background-color: #ffffff !important;"></div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table my-4" >
                                    <thead style="background-color: #E0F7FF">
                                        <tr>
                                            <th>Number</th>
                                            <th>Deskripsi</th>
                                            <th>Tgl Jatuh Tempo</th>
                                            <th>Total</th>
                                            <th>Sisa Tagihan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembayaran->pembelian as $v)
                                            @if($v->sisa_tagihan != 0)
                                            <tr>
                                                <td>{{ $v->no_str }}</td>
                                                <td></td>
                                                <td>{{ date('d-m-Y',strtotime($v->tanggal_jatuh_tempo)) }}</td>
                                                <td>{{ number_format($v->total, 2, ',', '.') }}</td>
                                                <td>{{ number_format($v->sisa_tagihan, 2, ',', '.') }}</td>
                                                <td>
                                                    <input type="text" name="id_pembelian[]" value="{{ $v->id }}" hidden>
                                                    <input type="text" class="form-control form-control-sm" name="total[]" 
                                                        id="total_{{ $v->id }}" onkeyup="change_total({{ $v->id }})" step="any">
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
                                    <a href="{{ url('pembelian') }}" class="btn btn-outline-danger">Batal</a>
                                    <button class="btn btn-primary">Kirim Pembayaran</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var total = {};
        var result_total = 0;

        function load() {

            result_total = 0;
            for (var key in total) {
                result_total += total[key];
            }

            $('#subtotal').text(rupiah(result_total));
            $('#total_pembayaran').text(rupiah(result_total));

            $('#input_subtotal').val(result_total);
        }

        $( document ).ready(function() {
            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));

            const fp_jatuh_tempo = flatpickr("#tanggal_jatuh_tempo", {
                dateFormat: "d/m/Y"
            });
            fp_jatuh_tempo.setDate(new Date('{{ date("Y-m-d") }}'));

            $('#total_{{ $pembelian->id }}').val('{{ $v->sisa_tagihan }}');

            load_select_2({{ $pembelian->id }});
            change_total({{ $pembelian->id }});
        });
        var total = {};

        function change_total(no) {
            total[no] = $('#total_' + no).val() ? parseFloat(AutoNumeric.getNumber('#total_' + no)) : 0 ;
            load();
        }

        function load_select_2(id) {
            new AutoNumeric("#total_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });

            // ==== Anti drag-copy ====
            const $total  = $("#total_" + id);

            // 1) Cegah mulai drag dari field AutoNumeric (sumber)
            [$total].forEach($el => {
                $el.attr("draggable", "false")                 // hint untuk browser
                .on("dragstart", e => e.preventDefault());  // benar-benar blok
            });

            // 2) Cegah drop ke field angka lain (target)
            //    Sesuaikan selector target sesuai form kamu.
            $(document).on("drop", "input[type=number], input.autonum, #total_"+id, function(e){
                e.preventDefault();
            });

            // (Opsional) Safari/WebKit agar makin kuat
            [$total].forEach($el => {
                $el.css("-webkit-user-drag", "none");
            });
        }

    </script>
@endsection
