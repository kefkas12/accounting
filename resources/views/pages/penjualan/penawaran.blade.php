@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .select2-container {
            width: 150px !important;
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

        /* Hilangkan bayangan saat normal maupun hover */
        .bootstrap-select .dropdown-toggle,
        .bootstrap-select .dropdown-toggle:focus,
        .bootstrap-select .dropdown-toggle:hover {
            box-shadow: none !important;   /* Hilangkan shadow */
            outline: none !important;      /* Hilangkan outline biru */
            background-color: #fff !important; /* Tetap putih saat hover */
            border-color: #ced4da !important;  /* Border tetap sama */
        }
    </style>
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body border-0 text-sm">
                        <div class="form-row">
                            <div class="form-group col-md-9 pr-2">
                                <a href="{{ url('penjualan') }}">Penjualan</a>
                                <h2>Buat Penawaran Penjualan</h2>
                            </div>
                            <div class="form-group col-md-3 pr-2">
                                <select class="form-control" onchange="location = this.value;">
                                    <option selected disabled hidden>Penawaran Penjualan</option>
                                    <option value="{{ url('penjualan/penagihan') }}">Penagihan Penjualan</option>
                                    <option value="{{ url('penjualan/penawaran') }}">Penawaran Penjualan</option>
                                    <option value="{{ url('penjualan/pemesanan') }}">Pemesanan Penjualan</option>
                                </select>
                            </div>
                        </div>
                        <form method="POST" id="insertForm"
                            @if(isset($penjualan)) 
                                action="{{ url('penjualan/penawaran').'/'.$penjualan->id }}" 
                            @else
                                action="{{ url('penjualan/penawaran') }}"
                            @endif
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="form-row border-bottom mb-3">
                                <label class="form-group col-md-3 pr-2">
                                    <label for="pelanggan">Pelanggan <span class="text-danger">*</span></label>
                                    <select class="selectpicker form-control form-control-sm" data-live-search="true" title="Pilih Pelanggan" id="pelanggan" name="pelanggan" onchange="alamat_pelanggan(this)" required>
                                        @foreach ($pelanggan as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="form-group col-md-3 pr-2" style="display:none">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email">
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="tanggal_transaksi">Tgl. Transaksi</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_transaksi"
                                        name="tanggal_transaksi" style="background-color: #ffffff !important;">
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="alamat">Pilih Alamat</label>
                                    <select class="form-control form-control-sm" id="alamat" name="alamat">
                                        <option selected disabled value="">Nothing Selected</option>
                                    </select>
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="detail_alamat">Detail Alamat</label>
                                    <textarea class="form-control form-control-sm" name="detail_alamat" id="detail_alamat" rows="1"></textarea>
                                </label>
                                <!-- <div class="form-group col-md-3 d-flex justify-content-end"> -->
                                    <!-- <h1><strong>Total &nbsp; <span id="total_faktur"> Rp 0,00</span></strong></h1> -->
                                <!-- </div> -->
                            </div>
                            <div class="form-row">
                                <label class="form-group col-md-3 pr-2">
                                    <label for="no_rfq">No RFQ</label>
                                    <input type="text" class="form-control form-control-sm" id="no_rfq" name="no_rfq">
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="pic">PIC</label>
                                    <input type="text" class="form-control form-control-sm" id="pic"
                                        name="pic">
                                </label>
                                
                                <label class="form-group has-float-label col-md-3 pr-2" style="display: none">
                                    <label for="tanggal_jatuh_tempo" style="display: none">Tgl. kedaluarsa</label>
                                    <input type="date" class="form-control" id="tanggal_jatuh_tempo"
                                        name="tanggal_jatuh_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                </label>
                            </div>

                            <div style="overflow: auto">
                                <table class="table align-items-center table-flush">
                                    <!-- Your table headers -->
                                    <thead>
                                        <tr>
                                            @if(isset($penjualan))
                                                @if(isset($produk_penawaran))
                                                <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Produk Penawaran</th>
                                                @endif
                                                <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Produk</th>
                                            @else
                                                @if(isset($produk_penawaran))
                                                <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Produk Penawaran</th>
                                                @else
                                                <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Produk</th>
                                                @endif
                                            @endif
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Harga Satuan</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">% Diskon</th>
                                            <th scope="col" style="min-width: 100px !important; padding: 10px !important;">Nilai Diskon</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Pajak</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Jumlah</th>
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            @if(isset($penjualan))
                                                @if(isset($produk_penawaran))
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="produk_penawaran[]" id="produk_penawaran_1" onchange="get_data(this, 1)" required>
                                                        <option selected disabled hidden value="">Pilih Produk Penawaran</option>
                                                        @foreach ($produk_penawaran as $v)
                                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @endif
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="produk[]" id="produk_1" onchange="get_data(this, 1)" @if(!isset($produk_penawaran)) required @endif>
                                                        <option selected disabled hidden value="">Pilih produk</option>
                                                        @foreach ($produk as $v)
                                                            <option value="{{ $v->id }}"
                                                                data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @else
                                                @if(isset($produk_penawaran))
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="produk_penawaran[]" id="produk_penawaran_1" onchange="get_data(this, 1)" required>
                                                        <option selected disabled hidden value="">Pilih Produk Penawaran</option>
                                                        @foreach ($produk_penawaran as $v)
                                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @else
                                                <td style="padding: 10px !important;">
                                                    <select class="form-control form-control-sm" name="produk[]" id="produk_1" onchange="get_data(this, 1)" @if(!isset($produk_penawaran)) required @endif>
                                                        <option selected disabled hidden value="">Pilih produk</option>
                                                        @foreach ($produk as $v)
                                                            <option value="{{ $v->id }}"
                                                                data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @endif
                                            @endif
                                            
                                            <td style="padding: 10px !important;">
                                                <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_1" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_1"
                                                    name="kuantitas[]" value="1" onkeyup="change_harga(1)" onblur="check_null(this)" step="any" required></td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_1"
                                                    name="harga_satuan[]" value="0" onblur="change_harga(1)"></td>
                                            <td style="padding: 10px !important;">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="diskon_per_baris_1"
                                                        name="diskon_per_baris[]" placeholder="0"
                                                        onkeyup="change_diskon_per_baris(1)" onblur="check_null(this)" 
                                                        step="any">
                                                </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="nilai_diskon_per_baris_1"
                                                        name="nilai_diskon_per_baris[]" placeholder="0"
                                                        onkeyup="change_nilai_diskon_per_baris(1)" onblur="check_null(this)"
                                                        step="any">
                                                </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control form-control-sm" id="pajak_1" name="pajak[]"
                                                    onchange="get_pajak(this, 1)" required>
                                                    <option value="0" data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_1" name="jumlah[]" value="0" onblur="change_jumlah(1)"></td>
                                            <td style="padding: 10px !important;"><a href="javascript:;" onclick="create_row()"><i
                                                        class="fa fa-plus text-primary"></i></a></td>
                                            <!-- <td style="padding: 10px !important;">
                                                <a href="javascript:;" onclick="clear_row(1)"><i class="fa fa-trash text-primary"></i></a>
                                            </td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="form-row">
                                <label class="form-group col-md-3 pr-2">
                                    <label for="pesan">Pesan</label>
                                    <textarea class="form-control form-control-sm" name="pesan" id="pesan"></textarea>
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="memo">Memo</label>
                                    <textarea class="form-control form-control-sm" name="memo" id="memo"></textarea>
                                </label>
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
                                            <span>Diskon per baris</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="diskon_per_baris">Rp 0,00</span>
                                            <input type="text" id="input_diskon_per_baris"
                                                name="input_diskon_per_baris" hidden>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                    <div class="row mb-2">
                                        <div class="col">
                                            <span>Sisa Tagihan</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="sisa_tagihan">Rp 0,00</span>
                                            <input type="text" id="input_sisa_tagihan" name="input_sisa_tagihan" hidden>
                                        </div>
                                    </div>
                                    <div class="row my-4">
                                        <div class="col d-flex justify-content-end">
                                            @if(isset($penjualan))
                                            <a href="{{ url('penjualan').'/detail/'.$penjualan->id }}" class="btn btn-light">Batalkan</a>
                                            @else
                                            <a href="{{ url('penjualan') }}" class="btn btn-light">Batalkan</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary" >@if(isset($penjualan)) Simpan perubahan @else Buat @endif</button>
                                        </div>
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
        var x = 1;
        var i = 1;
        var ppn = {};
        var subtotal = {};
        var diskon_per_baris = {};
        var result_subtotal = 0;
        var result_ppn = 0;
        var result_diskon_per_baris = 0;
        var kuantitas_array = [];

        function load() {

            result_subtotal = 0;
            for (var key in subtotal) {
                result_subtotal += subtotal[key];
            }

            result_ppn = 0;
            for (var key in ppn) {
                result_ppn += ppn[key];
            }

            result_diskon_per_baris = 0;
            for (var key in diskon_per_baris) {
                result_diskon_per_baris += diskon_per_baris[key];
            }

            $('#total_faktur').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));

            $('#subtotal').text(rupiah(result_subtotal));
            $('#ppn').text(rupiah(result_ppn));
            $('#diskon_per_baris').text(rupiah(result_diskon_per_baris));
            $('#total').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));
            $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));

            $('#input_subtotal').val(result_subtotal);
            $('#input_ppn').val(result_ppn);
            $('#input_diskon_per_baris').val(result_diskon_per_baris);
            $('#input_total').val(result_subtotal + result_ppn - result_diskon_per_baris);
            $('#input_sisa_tagihan').val(result_subtotal + result_ppn - result_diskon_per_baris);
        }

        function load_select_2(id) {
            @if(isset($penjualan))
            $("#produk_penawaran_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih Produk Penawaran',
                width: '100px'
            });
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk',
                width: '100px'
            });
            @else
            @if(isset($produk_penawaran))
            $("#produk_penawaran_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih Produk Penawaran',
                width: '100px'
            });
            @else
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk',
                width: '100px'
            });
            @endif
            @endif
            // $('#produk_'+id).on('select2:select', function (e) {
            //     if(id >= x){
            //         x += 1;
            //         create_row();
            //     }
            // });
            
            new AutoNumeric("#harga_satuan_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });
            new AutoNumeric("#jumlah_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });
        }

        function get_data(thisElement, no) {
            @if(isset($produk_penawaran))
                var selected = 0;
            @else
                var selected = $(thisElement).find('option:selected').data('harga_jual');
            @endif
            // $('#harga_satuan_' + no).val(selected);
            // $('#jumlah_' + no).val(selected);
            AutoNumeric.set('#harga_satuan_' + no,selected);
            AutoNumeric.set('#jumlah_' + no,selected);

            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            subtotal[no] = kuantitas * parseFloat(selected);

            float_diskon_per_baris = parseFloat($('#diskon_per_baris_' + no).val()) || 0;

            diskon_per_baris[no] = subtotal[no] * float_diskon_per_baris / 100;
            $('#nilai_diskon_per_baris_'+no).val(diskon_per_baris[no] == 0 ? "" : diskon_per_baris[no] );
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

        function change_harga(no, val_harga_satuan = null) {
            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif
            
            if(val_harga_satuan){
                AutoNumeric.set('#harga_satuan_' + no,val_harga_satuan);
            }else{
                AutoNumeric.set('#harga_satuan_' + no,AutoNumeric.getNumber('#harga_satuan_' + no));
            }
            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = subtotal[no] * diskon / 100;
            AutoNumeric.set('#jumlah_' + no,subtotal[no] - diskon_per_baris[no]);
            get_pajak($('#pajak_' + no), no);
            load();
        }

        function change_jumlah(no) {
            AutoNumeric.set('#jumlah_' + no,AutoNumeric.getNumber('#jumlah_' + no));
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;

            AutoNumeric.set('#harga_satuan_' + no, (100/(100-diskon)) * AutoNumeric.getNumber('#jumlah_' + no) / kuantitas);

            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon_per_baris[no] = subtotal[no] * diskon / 100;
            get_pajak($('#pajak_' + no), no);
            load();
        }

        function change_diskon_per_baris(no) {
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            // harga_satuan = $('#harga_satuan_' + no).val() ? parseFloat($('#harga_satuan_' + no).val()) : 0;
            var subtotal = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = subtotal * diskon / 100;

            $('#nilai_diskon_per_baris_'+no).val(diskon_per_baris[no] == 0 ? "" : diskon_per_baris[no] );
            
            // $('#jumlah_' + no).val(subtotal - diskon_per_baris[no]);
            AutoNumeric.set('#jumlah_' + no,subtotal - diskon_per_baris[no]);

            get_pajak($('#pajak_' + no), no);

            load();
        }

        function change_nilai_diskon_per_baris(no) {
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            var subtotal = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            nilai_diskon = $('#nilai_diskon_per_baris_' + no).val() ? parseFloat($('#nilai_diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = nilai_diskon;
            $('#diskon_per_baris_'+no).val("");
            
            // $('#jumlah_' + no).val(subtotal - diskon_per_baris[no]);
            AutoNumeric.set('#jumlah_' + no,subtotal - diskon_per_baris[no]);

            get_pajak($('#pajak_' + no), no);

            load();
        }

        function check_null(element) {
            if (element.value.trim() === "") {
                element.value = "";
                load();
            }

        }

        function hapus(no) {
            $('#list_' + no).remove();
            subtotal[no] = 0;
            ppn[no] = 0;
            diskon_per_baris[no] = 0;
            load();
        }

        function clear_row(no) {
            @if(isset($penjualan))
            $('#produk_penawaran_'+no).val('').trigger('change');
            $('#produk_'+no).val('').trigger('change');
            @else
            @if(isset($produk_penawaran))
            $('#produk_penawaran_'+no).val('').trigger('change');
            @else
            $('#produk_'+no).val('').trigger('change');
            @endif
            @endif
            $('#deskripsi_'+no).val('');
            $('#kuantitas_'+no).val('');
            AutoNumeric.set('#harga_satuan_' + no,0);
            $('#diskon_per_baris_'+no).val("");
            $('#nilai_diskon_per_baris_'+no).val("");
            $('#pajak_'+no).val(0).trigger('change');
            AutoNumeric.set('#jumlah_' + no,0);
            subtotal[no] = 0;
            ppn[no] = 0;
            diskon_per_baris[no] = 0;
            load();
        }

        function create_row() {
            i++;
            @if(isset($penjualan))
            @if(isset($produk_penawaran))
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk_penawaran[]" id="produk_penawaran_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden value="">Pilih Produk Penawaran</option>
                            @foreach ($produk_penawaran as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})">
                            <option selected disabled hidden value="">Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any" required></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" 
                                name="diskon_per_baris[]"  placeholder="0"
                                onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" 
                                step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" 
                            name="nilai_diskon_per_baris[]"  placeholder="0"
                            onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" 
                            step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <!--<a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>-->
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @else
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden value="">Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any" required></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <!--<a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>-->
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @endif
            @else
            @if(isset($produk_penawaran))
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk_penawaran[]" id="produk_penawaran_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden value="">Pilih Produk Penawaran</option>
                            @foreach ($produk_penawaran as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <!--<a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>-->
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @else
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden value="">Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <!--<a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>-->
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @endif
            @endif
            load_select_2(i);
        };

        $( document ).ready(function() {
            // $("#pelanggan").select2({
            //     allowClear: true,
            //     placeholder: 'Pilih pelanggan',
            //     width: 'resolve'
            // });
            $('#pelanggan').selectpicker();
            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));
            @if(isset($penjualan))
                // $('#pelanggan').val('id_pelanggan').trigger('change')
                const pel = $('#pelanggan')
                pel.selectpicker('val','{{ $penjualan->id_pelanggan }}');
                alamat_pelanggan(pel[0]).then(function() {
                    $('#alamat').val('{{ $penjualan->alamat }}');
                })
                $('#email').val('{{ $penjualan->email }}')
                $('#no_rfq').val('{{ $penjualan->no_rfq }}')
                $('#pic').val('{{ $penjualan->pic }}')
                $('#detail_alamat').val('{{ $penjualan->detail_alamat }}')
                fp_transaksi.setDate(new Date('{{ $penjualan->tanggal_transaksi }}'));
                $('#tanggal_jatuh_tempo').val('{{ $penjualan->tanggal_jatuh_tempo }}')

                $('#pesan').val('{{ $penjualan->pesan }}')
                $('#memo').val('{{ $penjualan->memo }}')

                var x = 1;
                load_select_2(x);
                @foreach($detail_penjualan as $v)
                    @if(isset($produk_penawaran))
                    $('#produk_penawaran_'+x).val('{{ $v->id_produk_penawaran }}').trigger('change');
                    @endif
                    $('#produk_'+x).val('{{ $v->id_produk }}').trigger('change');
                    
                    $('#deskripsi_'+x).val('{{ $v->deskripsi }}');
                    $('#kuantitas_'+x).val('{{ $v->kuantitas }}').trigger('keyup');
                    change_harga(x, {{ $v->harga_satuan }});
                    $('#diskon_per_baris_'+x).val('{{ $v->diskon_per_baris }}').trigger('keyup');
                    @if($v->pajak != 0)
                        $('#pajak_'+x).val('11').trigger('change');
                    @else
                        $('#pajak_'+x).val('0').trigger('change');
                    @endif
                    create_row();
                    x++;
                @endforeach
                hapus(x);
            @else
                load_select_2(1);
            @endif
        });

        function alamat_pelanggan(thisElement) {
            var selected = $(thisElement).find('option:selected').val();
            $('#alamat').empty();
            return $.ajax({
                url: '{{ url("pelanggan/alamat") }}',
                type: 'GET',
                data: {
                    id: selected
                },
                success: function (response) {
                    $('#alamat').append('<option selected disabled hidden value="">Pilih alamat</option>');
                    for(var i = 0; i < response.length; i++){
                        $('#alamat').append('<option>'+response[i].alamat+'</option>');
                    }
                }
            });
        }
    </script>
@endsection
