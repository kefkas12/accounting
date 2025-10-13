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

        .select2-container {
            width: 250px !important;
        }

        /* Ubah tombol dropdown selectpicker jadi mirip input bootstrap */
        .bootstrap-select .dropdown-toggle {
            border: 1px solid #ced4da !important; /* Border Bootstrap */
            border-radius: 0.25rem !important;     /* Radius Bootstrap */
            background-color: #fff !important;     /* Background putih */
            color: #495057 !important;             /* Warna teks Bootstrap */
            height: calc(1.5em + .5rem + 2px) !important; /* Tinggi form-control-sm */
            padding: .25rem .5rem !important;      /* Padding form-control-sm */
        }

        /* Placeholder abu-abu */
        .bootstrap-select .dropdown-toggle.bs-placeholder,
        .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
            color: #6c757d !important; /* Warna placeholder */
        }

        .bootstrap-select .dropdown-toggle,
        .bootstrap-select .dropdown-toggle:focus,
        .bootstrap-select .dropdown-toggle:hover {
            box-shadow: none !important;   /* Hilangkan shadow */
            outline: none !important;      /* Hilangkan outline biru */
            background-color: #fff !important; /* Tetap putih saat hover */
            border-color: #ced4da !important;  /* Border tetap sama */
        }
    </style>
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-body border-0 text-sm">
                        <div class="form-row">
                            <div class="form-group col-md-9 pr-2">
                                <a href="{{ url('penjualan') }}">Transaksi</a>
                                <h2>Transfer Uang</h2>
                            </div>
                        </div>
                        <form method="POST" id="insertForm"
                            @if(isset($transfer_uang))
                                action="{{ url('kas_bank/transfer_uang').'/'.$transfer_uang->id }}"
                            @else
                                action="{{ url('kas_bank/transfer_uang') }}"
                            @endif
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="card-body" style="padding: 0px !important;">
                                <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;border-bottom: 2px solid #B3D7E5;">
                                    <div class="row">
                                        <div class="col-sm-3 mt-2">Transfer Dari</div>
                                        <div class="col-sm-3 mt-2">Setor Ke</div>
                                        <div class="col-sm-3 mt-2">Jumlah</div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-3">
                                            <select class="form-control form-control-sm" name="transfer_dari" id="transfer_dari">
                                                @foreach ($akun as $v)
                                                    <option value="{{ $v->id }}">({{ $v->nomor }}) - {{ $v->nama }}
                                                        ({{ $v->nama_kategori }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control form-control-sm" name="setor_ke" id="setor_ke">
                                                @foreach ($akun as $v)
                                                    <option value="{{ $v->id }}">({{ $v->nomor }}) - {{ $v->nama }}
                                                        ({{ $v->nama_kategori }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" name="jumlah" 
                                                id="jumlah" onkeyup="change_jumlah()" step="any" placeholder="Rp 0,00" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3">
                                    <div class="row">
                                        <div class="col-sm-3">Memo</div>
                                        <div class="col-sm-3">Tgl Transaksi</div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <textarea class="form-control form-control-sm" name="memo" id="memo"></textarea>
                                        </div>
                                        <div class="col-sm-3"><input type="date" class="form-control form-control-sm" id="tanggal_transaksi"
                                                name="tanggal_transaksi" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}"></div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>

                                <div class="row my-4">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <a href="{{ url('penjualan') }}" class="btn btn-outline-danger">Batal</a>
                                        <button class="btn btn-primary">Buat Transferan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var jumlah = 0;

        function load() {
            $('#jumlah').text(rupiah(jumlah));
        }

        $( document ).ready(function() {
            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));

            load_select_2();
        });

        function change_jumlah() {
            jumlah = $('#jumlah').val() ? parseFloat(AutoNumeric.getNumber('#jumlah')) : 0 ;
            load();
        }

        function load_select_2() {
            $("#transfer_dari").select2({
                allowClear: true,
                placeholder: 'Pilih Akun Pembayaran',
                width: '100px'
            });

            $("#setor_ke").select2({
                allowClear: true,
                placeholder: 'Pilih Akun Pembayaran',
                width: '100px'
            });

            new AutoNumeric("#jumlah", {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });

            // ==== Anti drag-copy ====
            const $jumlah  = $("#jumlah");

            // 1) Cegah mulai drag dari field AutoNumeric (sumber)
            [$jumlah].forEach($el => {
                $el.attr("draggable", "false")                 // hint untuk browser
                .on("dragstart", e => e.preventDefault());  // benar-benar blok
            });

            // 2) Cegah drop ke field angka lain (target)
            //    Sesuaikan selector target sesuai form kamu.
            $(document).on("drop", "input[type=number], input.autonum, #jumlah", function(e){
                e.preventDefault();
            });

            // (Opsional) Safari/WebKit agar makin kuat
            [$jumlah].forEach($el => {
                $el.css("-webkit-user-drag", "none");
            });
        }

    </script>
@endsection
