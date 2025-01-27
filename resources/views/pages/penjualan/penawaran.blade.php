@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important; /* Sesuaikan dengan tinggi input Bootstrap */
            display: flex; /* Gunakan flexbox untuk menata konten */
            align-items: center; /* Pusatkan konten secara vertikal */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 10px; /* Memberikan jarak teks dari tepi kiri */
            line-height: normal !important; /* Hilangkan line-height default */
            color: #6c757d; /* Placeholder warna abu-abu */
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            margin: 0; /* Hilangkan margin bawaan */
            line-height: normal; /* Pastikan line-height tidak memengaruhi posisi */
            color: #6c757d; /* Warna abu-abu */
        }
    </style>
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0">
                        <div class="row">
                            <div class="col">
                                <a href="{{ url('penjualan') }}">Penjualan</a>
                            </div>
                        </div>
                        <div class="row text-sm">
                            <div class="col">
                                <h2>Buat Penawaran Penjualan</h2>
                            </div>
                            <div class="col-sm-3 d-flex justify-content-end">
                                <select class="form-control" onchange="location = this.value;">
                                    <option selected disabled hidden>Penawaran Penjualan</option>
                                    <option value="{{ url('penjualan/penagihan') }}">Penagihan Penjualan</option>
                                    <option value="{{ url('penjualan/penawaran') }}">Penawaran Penjualan</option>
                                    <option value="{{ url('penjualan/pemesanan') }}">Pemesanan Penjualan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <form method="POST" id="insertForm"
                        @if(isset($penjualan)) 
                            action="{{ url('penjualan/penawaran').'/'.$penjualan->id }}" 
                        @else
                            action="{{ url('penjualan/penawaran') }}"
                        @endif
                    >
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="pelanggan">Pelanggan <span class="text-danger">*</span></label>
                                    <select class="form-control col-md-3" id="pelanggan" name="pelanggan" required>
                                        <option selected disabled value="">Pilih kontak</option>
                                        @foreach ($pelanggan as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-3 pr-4">
                                    <label for="no_rfq">No RFQ</label>
                                    <input type="text" class="form-control" id="no_rfq" name="no_rfq">
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group col-md-3 d-flex justify-content-end">
                                    Total &nbsp; <span id="total_faktur"> Rp 0</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="pic">PIC</label>
                                    <input type="text" class="form-control" id="pic"
                                        name="pic">
                                </div>
                                
                                <div class="form-group col-md-3 pr-4">
                                    <label for="tanggal_transaksi">Tgl. transaksi</label>
                                    <input type="date" class="form-control" id="tanggal_transaksi"
                                        name="tanggal_transaksi" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    <label for="alamat">Alamat</label><br>
                                    <textarea class="form-control" name="alamat" id="alamat"></textarea>
                                </div>
                                <div class="form-group col-md-3 pr-4" style="display: none">
                                    <label for="tanggal_jatuh_tempo" style="display: none">Tgl. kedaluarsa</label>
                                    <input type="date" class="form-control" id="tanggal_jatuh_tempo"
                                        name="tanggal_jatuh_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}" style="display: none">
                                </div>
                            </div>

                            <div style="overflow: auto">
                                <table class="table align-items-center table-flush">
                                    <!-- Your table headers -->
                                    <thead>
                                        <tr>
                                            @if(isset($penjualan))
                                            @if(isset($produk_penawaran))
                                            <th scope="col" style="min-width: 300px !important; padding: 10px !important;">Produk Penawaran</th>
                                            @endif
                                            <th scope="col" style="min-width: 300px !important; padding: 10px !important;">Produk</th>
                                            @else
                                            @if(isset($produk_penawaran))
                                            <th scope="col" style="min-width: 300px !important; padding: 10px !important;">Produk Penawaran</th>
                                            @else
                                            <th scope="col" style="min-width: 300px !important; padding: 10px !important;">Produk</th>
                                            @endif
                                            @endif
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 100px !important; padding: 10px !important;">Kuantitas</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Harga Satuan</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">% Diskon</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Nilai Diskon</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Pajak</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Jumlah</th>
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            @if(isset($penjualan))
                                            @if(isset($produk_penawaran))
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" name="produk_penawaran[]" id="produk_penawaran_1" onchange="get_data(this, 1)" required>
                                                    <option selected disabled hidden>Pilih produk penawaran</option>
                                                    @foreach ($produk_penawaran as $v)
                                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @endif
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" name="produk[]" id="produk_1" onchange="get_data(this, 1)" @if(!isset($produk_penawaran)) required @endif>
                                                    <option selected disabled hidden>Pilih produk</option>
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
                                                <select class="form-control" name="produk_penawaran[]" id="produk_penawaran_1" onchange="get_data(this, 1)" required>
                                                    <option selected disabled hidden>Pilih produk penawaran</option>
                                                    @foreach ($produk_penawaran as $v)
                                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @else
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" name="produk[]" id="produk_1" onchange="get_data(this, 1)" @if(!isset($produk_penawaran)) required @endif>
                                                    <option selected disabled hidden>Pilih produk</option>
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
                                                <textarea class="form-control" name="deskripsi[]" id="deskripsi_1" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_1"
                                                    name="kuantitas[]" value="1" onkeyup="change_harga(1)" onblur="check_null(this)" step="any"></td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control" id="harga_satuan_1"
                                                    name="harga_satuan[]" value="0" onblur="change_harga(1)"></td>
                                            <td style="padding: 10px !important;">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="diskon_per_baris_1"
                                                        name="diskon_per_baris[]"
                                                        onkeyup="change_diskon_per_baris(1)" onblur="check_null(this)" step="any">
                                                </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="nilai_diskon_per_baris_1"
                                                        name="nilai_diskon_per_baris[]"
                                                        onkeyup="change_nilai_diskon_per_baris(1)" onblur="check_null(this)"
                                                        step="any">
                                                </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" id="pajak_1" name="pajak[]"
                                                    onchange="get_pajak(this, 1)" required>
                                                    <option value="0" data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control" id="jumlah_1" name="jumlah[]" value="0" onblur="change_jumlah(1)"></td>
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
                            <div class="row">
                                <div class="col">
                                    <div class="form-group col-md-6">
                                        <label for="pesan">Pesan</label><br>
                                        <textarea class="form-control" name="pesan" id="pesan"></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="memo">Memo</label><br>
                                        <textarea class="form-control" name="memo" id="memo"></textarea>
                                    </div>
                                </div>
                                <div class="col ">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <span>Subtotal</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="subtotal">Rp 0,00</span>
                                            <input type="text" id="input_subtotal" name="input_subtotal" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
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
                                    <hr class="bg-white">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <span>Total</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="total">Rp 0,00</span>
                                            <input type="text" id="input_total" name="input_total" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <span>Sisa Tagihan</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="sisa_tagihan">Rp 0,00</span>
                                            <input type="text" id="input_sisa_tagihan" name="input_sisa_tagihan"
                                                hidden>
                                        </div>
                                    </div>
                                    <div class="row my-5">
                                        <div class="col d-flex justify-content-end">
                                            <a href="{{ url('penjualan') }}" class="btn btn-light">Batalkan</a>
                                            <button type="submit" class="btn btn-primary" onclick="buat();">@if(isset($penjualan)) Simpan perubahan @else Buat @endif</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                placeholder: 'Pilih produk penawaran'
            });
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk'
            });
            @else
            @if(isset($produk_penawaran))
            $("#produk_penawaran_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk penawaran'
            });
            @else
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk'
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
            var selected = $(thisElement).find('option:selected').data('harga_jual');
            // $('#harga_satuan_' + no).val(selected);
            // $('#jumlah_' + no).val(selected);
            AutoNumeric.set('#harga_satuan_' + no,selected);
            AutoNumeric.set('#jumlah_' + no,selected);

            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            subtotal[no] = kuantitas * parseFloat(selected);

            float_diskon_per_baris = parseFloat($('#diskon_per_baris_' + no).val()) || 0;

            diskon_per_baris[no] = subtotal[no] * float_diskon_per_baris / 100;
            $('#nilai_diskon_per_baris_'+no).val(diskon_per_baris[no] == 0 ? "" : diskon_per_baris[no] );
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
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
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
                        <select class="form-control" name="produk_penawaran[]" id="produk_penawaran_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden>Pilih produk penawaran</option>
                            @foreach ($produk_penawaran as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th style="padding: 10px !important;">
                        <select class="form-control" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})">
                            <option selected disabled hidden>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @else
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>
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
                        <select class="form-control" name="produk_penawaran[]" id="produk_penawaran_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden>Pilih produk penawaran</option>
                            @foreach ($produk_penawaran as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @else
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onblur="change_harga(${i})"></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" name="diskon_per_baris[]" onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" name="nilai_diskon_per_baris[]" onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" step="any">
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0" onblur="change_jumlah(${i})"></td>
                    <td style="padding: 10px !important;">
                        <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a>
                    </td>
                </tr>
            `);
            @endif
            @endif
            load_select_2(i);
        };

        $( document ).ready(function() {
            $("#pelanggan").select2({
                allowClear: true,
                placeholder: 'Pilih pelanggan',
                width: 'resolve'
            });
            @if(isset($penjualan))
                $('#pelanggan').val('{{ $penjualan->id_pelanggan }}').trigger('change')
                $('#email').val('{{ $penjualan->email }}')
                $('#no_rfq').val('{{ $penjualan->no_rfq }}')
                $('#pic').val('{{ $penjualan->pic }}')
                $('#alamat').val('{{ $penjualan->alamat }}')
                $('#tanggal_transaksi').val('{{ $penjualan->tanggal_transaksi }}')
                $('#tanggal_jatuh_tempo').val('{{ $penjualan->tanggal_jatuh_tempo }}')

                $('#pesan').val('{{ $penjualan->pesan }}')
                $('#memo').val('{{ $penjualan->memo }}')

                var x = 1;
                load_select_2(x);
                @foreach($detail_penjualan as $v)
                    @if(isset($produk_penawaran))
                    $('#produk_penawaran_'+x).val('{{ $v->id_produk }}').trigger('change');
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
    </script>
@endsection
