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
                                <a href="{{ url('kas_bank') }}">Transaksi</a>
                                <h2>Terima Uang</h2>
                            </div>
                        </div>
                        <form method="POST" id="insertForm"
                            @if(isset($terima_uang))
                                action="{{ url('kas_bank/terima_uang').'/'.$terima_uang->id }}"
                            @else
                                action="{{ url('kas_bank/terima_uang') }}"
                            @endif
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="card-body" style="padding: 0px !important;">
                                <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;border-bottom: 2px solid #B3D7E5;">
                                    <div class="row">
                                        <div class="col-sm-3 mt-2">Setor Ke</div>
                                        <div class="col-sm-3 mt-2"></div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-3">
                                            <select class="form-control form-control-sm" name="setor_ke" id="setor_ke">
                                                @foreach ($akun as $v)
                                                    <option value="{{ $v->id }}">({{ $v->nomor }}) - {{ $v->nama }}
                                                        ({{ $v->nama_kategori }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-9 d-flex justify-content-end">
                                            <h2>Total Amount <span id="total_amount">Rp. 0,00</span></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-4">
                                    <div class="row">
                                        <div class="col-sm-3">Yang Membayar</div>
                                        <div class="col-sm-3">Tgl Transaksi</div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <select class="form-control form-control-sm" name="yang_membayar" id="yang_membayar">
                                                <option value="" hidden selected disabled>Pilih penerima</option>
                                                @foreach ($kontak as $v)
                                                    <option value="{{ $v->id }}_{{ $v->tipe }}">{{ $v->nama }} - ({{ $v->tipe }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3"><input type="date" class="form-control form-control-sm" id="tanggal_transaksi"
                                                name="tanggal_transaksi" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}"></div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                                <div style="overflow-x: auto">
                                    <table class="table">
                                        <thead>
                                            <tr style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;border-bottom: 2px solid #B3D7E5;">
                                                <th scope="col" style="min-width: 300px !important;padding: 10px !important;">Terima dari</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Deskripsi</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Pajak</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">jumlah</th>
                                                <th scope="col" style="min-width: 25px !important;padding: 10px !important;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="list">
                                            <tr>
                                                <th style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="akun[]" id="akun_1" required>
                                                        <option value="" hidden selected disabled>Pilih akun</option>
                                                        @foreach ($akun_terima_dari as $v)
                                                            <option value="{{ $v->id }}">({{ $v->nomor }})
                                                                {{ $v->nama }} ({{ $v->nama_kategori }})</option>
                                                        @endforeach
                                                    </select>
                                                    @if (isset($jurnal)) 
                                                        <input type="number" name="id_detail_jurnal[]" id="id_detail_jurnal_1" hidden>
                                                    @endif
                                                </th>
                                                <td style="padding: 10px !important;">
                                                    <textarea class="form-control form-control-sm" id="deskripsi_1" name="deskripsi[]" rows="1"></textarea>
                                                </td>
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" id="pajak_1" name="pajak[]" onchange="get_pajak(this, 1)" required>
                                                        <option value="0" data-persen="0">Pilih pajak</option>
                                                        <option value="11" data-persen="11">PPN</option>
                                                    </select>
                                                </td>
                                                <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_1" name="jumlah[]"
                                                        value="0" onblur="change_jumlah(1)"></td>
                                                <td style="padding: 10px !important;">
                                                    <a href="javascript:;" onclick="clear_row(1)"><i class="fa fa-trash text-primary"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="akun[]" id="akun_2" required>
                                                        <option value="" hidden selected disabled>Pilih akun</option>
                                                        @foreach ($akun_terima_dari as $v)
                                                            <option value="{{ $v->id }}">({{ $v->nomor }})
                                                                {{ $v->nama }} ({{ $v->nama_kategori }})</option>
                                                        @endforeach
                                                    </select>
                                                    @if (isset($jurnal)) 
                                                        <input type="number" name="id_detail_jurnal[]" id="id_detail_jurnal_2" hidden>
                                                    @endif
                                                </th>
                                                <td style="padding: 10px !important;">
                                                    <textarea class="form-control form-control-sm" id="deskripsi_2" name="deskripsi[]" rows="1"></textarea>
                                                </td>
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" id="pajak_2" name="pajak[]" onchange="get_pajak(this, 2)" required>
                                                        <option value="0" data-persen="0">Pilih pajak</option>
                                                        <option value="11" data-persen="11">PPN</option>
                                                    </select>
                                                </td>
                                                <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_2" name="jumlah[]"
                                                        value="0" onblur="change_jumlah(2)"></td>
                                                <td style="padding: 10px !important;">
                                                    <a href="javascript:;" onclick="clear_row(2)"><i class="fa fa-trash text-primary"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="create_row();">+ Tambah Data</button>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="form-group col-md-6 pr-2">
                                        <label for="memo">Memo</label>
                                        <textarea class="form-control form-control-sm" name="memo" id="memo"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>Subtotal</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="subtotal">Rp 0,00</span>
                                                <input type="text" id="input_subtotal" name="input_subtotal" hidden>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col">
                                                <span>PPN</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="ppn">Rp 0,00</span>
                                                <input type="text" id="input_ppn" name="input_ppn" hidden>
                                            </div>
                                        </div>
                                        <div class="row mb-2 mt-2 pt-1 border-top ">
                                            <div class="col">
                                                <span>Total</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="total">Rp 0,00</span>
                                                <input type="text" id="input_total" name="input_total" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <a href="{{ url('kas_bank') }}" class="btn btn-light">Batal</a>
                                        @if(isset($terima_uang))
                                        <button type="submit" class="btn btn-primary">Edit Penerimaan</button>
                                        @else
                                        <button type="submit" class="btn btn-primary">Buat Penerimaan</button>
                                        @endif
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
        var x = 0;
        var i = 2;
        var ppn = {};
        var jumlah = {};
        var result_jumlah = 0;
        var result_ppn = 0;

        function load() {
            result_jumlah = 0;
            for (var key in jumlah) {
                result_jumlah += jumlah[key];
            }

            result_ppn = 0;
            for (var key in ppn) {
                result_ppn += ppn[key];
            }

            $('#subtotal').text(rupiah(result_jumlah));
            $('#ppn').text(rupiah(result_ppn));
            $('#total').text(rupiah(result_jumlah + result_ppn));
            $('#total_amount').text(rupiah(result_jumlah + result_ppn));

            $('#input_subtotal').val(result_jumlah);
            $('#input_ppn').val(result_ppn);
            $('#input_total').val(result_jumlah + result_ppn);
        }

        function load_select_2(id) {
            $("#akun_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih akun'
            });

            new AutoNumeric("#jumlah_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });

            document.getElementById("jumlah_" + id).addEventListener("paste", function (e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('Text');
                AutoNumeric.set(this, pastedData);
            });

            // ==== Anti drag-copy ====
            const $jumlah = $("#jumlah_" + id);

            // 1) Cegah mulai drag dari field AutoNumeric (sumber)
            [$jumlah].forEach($el => {
                $el.attr("draggable", "false")                 // hint untuk browser
                .on("dragstart", e => e.preventDefault());  // benar-benar blok
            });

            // 2) Cegah drop ke field angka lain (target)
            //    Sesuaikan selector target sesuai form kamu.
            $(document).on("drop", "input[type=number], input.autonum, #jumlah_"+id, function(e){
                e.preventDefault();
            });

            // (Opsional) Safari/WebKit agar makin kuat
            [$jumlah].forEach($el => {
                $el.css("-webkit-user-drag", "none");
            });
        }

        $( document ).ready(function() {
            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));

            load_select_2(1);
            load_select_2(2);

            @if(isset($terima_uang))
                $('#transfer_dari').val('{{ $terima_uang->id_transfer_dari }}').trigger('change');
                $('#setor_ke').val('{{ $terima_uang->id_setor_ke }}').trigger('change');
                $('#jumlah').val('{{ $terima_uang->jumlah }}');
                $('#memo').val(`{!! $terima_uang->memo !!}`);
                $('#tanggal_transaksi').val('{{ date("Y-m-d",strtotime($terima_uang->tanggal_transaksi)) }}');
                change_jumlah();
            @endif
        });

        function change_jumlah(no) {
            jumlah[no] = parseFloat(AutoNumeric.getNumber('#jumlah_' + no));
            get_pajak($('#pajak_' + no), no);
            load();
        }

        function get_pajak(thisElement, no) {
            var selected = parseFloat($(thisElement).find('option:selected').data('persen'));

            if (selected != 0) {
                ppn[no] = selected * parseFloat(AutoNumeric.getNumber('#jumlah_' + no)) / 100;
            } else {
                ppn[no] = 0;
            }

            load();
        }

        function hapus(no) {
            $('#list_' + no).remove();
            jumlah[no] = 0;
            load();
        }

        function clear_row(no) {
            $('#akun_'+no).val('').trigger('change');
            $('#id_detail_jurnal_'+no).val('');
            $('#deskripsi_'+no).val('');
            AutoNumeric.set('#jumlah_' + no,0);
            jumlah[no] = 0;
            load();
        }

        function create_row() {
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="akun[]" id="akun_${i}" required>
                            <option value="" hidden selected disabled>Pilih akun</option>
                            @foreach ($akun as $v)
                                <option value="{{ $v->id }}" >({{ $v->nomor }}) {{ $v->nama }} ({{ $v->nama_kategori }})</option>
                            @endforeach
                        </select>
                        @if (isset($jurnal)) 
                            <input type="number" name="id_detail_jurnal[]" id="id_detail_jurnal_${i}" hidden>
                        @endif
                    </th>
                    <td style="padding: 10px !important;"><textarea class="form-control form-control-sm" id="deskripsi_${i}" name="deskripsi[]" rows="1"></textarea></td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0">Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;"><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
            load_select_2(i);
        };

    </script>
@endsection
