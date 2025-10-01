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
                        <div class="row">
                            <div class="form-group col-md-9 pr-2">
                                <a href="{{ url('pembelian') }}">Pembelian</a>
                                <h2>Buat Pengiriman Pembelian</h2>
                            </div>
                            <div class="form-group col-md-3 pr-2">
                                <select class="form-control" onchange="location = this.value;" @if(isset($pemesanan)) disabled @endif>
                                    <option selected disabled hidden>Pengiriman Pembelian</option>
                                    <option value="{{ url('pembelian/faktur') }}">Faktur Pembelian</option>
                                    <option value="{{ url('pembelian/pemesanan') }}">Pemesanan Pembelian</option>
                                </select>
                            </div>
                        </div>
                        <form method="POST" id="insertForm"
                            @if(isset($pemesanan_pengiriman))
                                action="{{ url('pembelian/pemesanan').'/pengiriman/'.$pembelian->id }}" 
                            @elseif(isset($pemesanan))
                                action="{{ url('pembelian/pengiriman').'/'.$pembelian->id }}" 
                            @else
                                action="{{ url('pembelian/pengiriman') }}" 
                            @endif
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @if(isset($pemesanan))
                            <div class="form-row border-bottom border-top border-left border-right text-center pt-3 mb-3">
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Pemesanan</label> <br>
                                        @if(isset($pemesanan_pengiriman))
                                        <a href="{{ url('pembelian/detail').'/'.$pembelian->id }}">{{ $pembelian->no_str }}</a>
                                        @else
                                        <a href="{{ url('pembelian/detail').'/'.$pembelian->id_pemesanan }}">{{ $pembelian->no_str_pemesanan }}</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>Gudang</label> <br>
                                        <span>{{ $pembelian->nama_gudang }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3 border-right">

                                </div>
                                
                            </div>
                            @endif
                            <div class="form-row border-bottom mb-3">
                                <div class="form-group col-md-3 pr-2">
                                    <label for="supplier">Supplier <span class="text-danger">*</span></label>
                                    <select class="selectpicker form-control form-control-sm" data-live-search="true" title="Pilih Supplier" id="supplier" name="supplier" onchange="alamat_supplier(this)" required @if(isset($penawaran)) disabled @endif hidden>
                                        <option selected disabled>Pilih kontak</option>
                                        @foreach ($supplier as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email">
                                </div>
                                <div class="form-group col-md-3 pr-2">
                                    <label for="tanggal_transaksi">Tgl. Transaksi</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_transaksi" 
                                        name="tanggal_transaksi" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}">
                                </div>
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
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <div class="form-check" >
                                        <input class="form-check-input" type="checkbox" id="info_pengiriman" name="info_pengiriman">
                                        <label class="form-check-label" for="info_pengiriman">
                                            Info Pengiriman
                                        </label>
                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-3 d-flex justify-content-end">
                                    Total Rp <span id="total_faktur">0,00</span>
                                </div> -->
                            </div>
                            <div class="form-row">
                                <div class="form-group info_pengiriman" style="display:none">
                                    <label class="alamat_pengiriman" style="display:none">Alamat Pengiriman</label>
                                    <textarea class="form-control form-control-sm" name="alamat_pengiriman" id="alamat_pengiriman" rows="1" style="display:none"></textarea>
                                </div>
                                <div class="form-check mb-4 text-sm" style="display:none" >
                                    <input class="form-check-input" type="checkbox" id="sama_dengan_penagihan" name="sama_dengan_penagihan" checked>
                                    <label class="form-check-label" for="sama_dengan_penagihan">
                                        Alamat Pengiriman sama dengan pemesanan
                                    </label>
                                </div>
                                <div class="form-group col-md-2 pr-2" style="display:none">
                                    <label for="tanggal_jatuh_tempo">Tgl. jatuh tempo</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_jatuh_tempo"
                                        name="tanggal_jatuh_tempo" style="background-color: #ffffff !important;" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                </div>
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <label for="gudang">Gudang</label>
                                    <select class="form-control form-control-sm" id="gudang" name="gudang" @if(isset($pemesanan)) disabled @endif>
                                        <option selected disabled hidden>Pilih Gudang</option>
                                        @if(isset($gudang))
                                        @foreach($gudang as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                        @endforeach
                                        @else
                                        <option disabled>No result found</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3 pr-2" style="display:none">
                                    <div class="form-group info_pengiriman" style="display:none">
                                        <label for="tanggal_pengiriman">Tgl. pengiriman</label>
                                        <input type="date" class="form-control form-control-sm" id="tanggal_pengiriman"
                                            name="tanggal_pengiriman" style="background-color: #ffffff !important;">
                                    </div>
                                </div>
                                <div class="form-group col-md-3 info_pengiriman" style="display:none">
                                    <label for="kirim_melalui">Kirim Melalui</label>
                                    <input type="text" class="form-control form-control-sm" id="kirim_melalui"
                                        name="kirim_melalui">
                                </div>
                                <div class="form-group col-md-3 pr-2 info_pengiriman" style="display:none">
                                    <label for="no_pelacakan">No. Pelacakan</label>
                                    <input type="text" class="form-control form-control-sm" id="no_pelacakan" name="no_pelacakan">
                                </div>
                            </div>
                            @if(isset($detail_pemesanan))
                                Referensi Pemesanan
                                <table class="table table-striped table-dark">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Deskripsi</th>
                                            <th>Kuantitas</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($detail_pemesanan as $v)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $v->produk->nama }}</td>
                                            <td>{{ $v->deskripsi }}</td>
                                            <td>{{ $v->kuantitas }}</td>
                                            <td>{{ $v->produk->unit }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <div style="overflow: auto">
                                <table class="table align-items-center table-flush">
                                    <!-- Your table headers -->
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Produk</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                            <th scope="col" style="min-width: 100px !important; padding: 10px !important;">Unit</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;" hidden>Harga Satuan</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;" hidden>Pajak</th>
                                            <th scope="col" style="min-width: 150px !important; padding: 10px !important;" hidden>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control form-control-sm" name="produk[]" id="produk_1" onchange="get_data(this, 1)" required @if(isset($pemesanan)) disabled @endif>
                                                    <option selected disabled hidden>Pilih produk</option>
                                                    @foreach ($produk as $v)
                                                        <option value="{{ $v->id }}"
                                                            data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_1" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <input type="number" class="form-control form-control-sm" id="kuantitas_1"
                                                    name="kuantitas[]" value="1" onkeyup="change_jumlah(1)"
                                                    onblur="check_null(this)" step="any"></td>
                                            <td style="padding: 10px !important;">
                                                <input type="text" class="form-control form-control-sm" id="unit_1"
                                                    name="unit[]" readonly></td>
                                            <td style="padding: 10px !important;" hidden>
                                                <input type="text" class="form-control form-control-sm" id="harga_satuan_1"
                                                    name="harga_satuan[]" value="0" onkeyup="change_jumlah(1)"
                                                    onblur="check_null(this)" step="any"></td>
                                            <td style="padding: 10px !important;" hidden>
                                                <select class="form-control form-control-sm" id="pajak_1" name="pajak[]"
                                                    onchange="get_pajak(this, 1)" required>
                                                    <option value="0" data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;display:none;"><input type="text" class="form-control form-control-sm" id="jumlah_1" name="jumlah[]" value="0" onblur="change_jumlah(1)" @if(isset($pemesanan)) disabled @endif></td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-2">
                                    <label for="pesan">Pesan</label>
                                    <textarea class="form-control form-control-sm" name="pesan" id="pesan"></textarea>
                                </div>
                                <div class="form-group col-sm-3 pr-2">
                                    <label for="memo">Memo</label>
                                    <textarea class="form-control form-control-sm" name="memo" id="memo"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Ongkos kirim</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm"  id="input_ongkos_kirim" name="input_ongkos_kirim">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3"  hidden>
                                        <div class="col">
                                            <span>Subtotal</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="subtotal">Rp 0,00</span>
                                            <input type="text" id="input_subtotal" name="input_subtotal" hidden>
                                        </div>
                                    </div>
                                    <div class="row" hidden>
                                        <div class="col">
                                            <span>PPN</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="ppn">Rp 0,00</span>
                                            <input type="text" id="input_ppn" name="input_ppn" hidden>
                                        </div>
                                    </div>
                                    <hr class="bg-white" hidden>
                                    <div class="row mb-3" hidden>
                                        <div class="col">
                                            <span>Total</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="total">Rp 0,00</span>
                                            <input type="text" id="input_total" name="input_total" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-5" hidden>
                                        <div class="col">
                                            <span>Sisa Tagihan</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="sisa_tagihan">Rp 0,00</span>
                                            <input type="text" id="input_sisa_tagihan" name="input_sisa_tagihan" hidden>
                                        </div>
                                    </div>
                                    <div class="row my-5">
                                        <div class="col d-flex justify-content-end">
                                            @if(isset($pembelian))
                                            <a href="{{ url('pembelian').'/detail/'.$pembelian->id }}" class="btn btn-light">Batalkan</a>
                                            @else
                                            <a href="{{ url('pembelian') }}" class="btn btn-light">Batalkan</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Buat</button>
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
        var i = 1;
        var ppn = {};
        var subtotal = {};
        var result_subtotal = 0;
        var result_ppn = 0;

        function load() {

            result_subtotal = 0;
            for (var key in subtotal) {
                result_subtotal += subtotal[key];
            }

            result_ppn = 0;
            for (var key in ppn) {
                result_ppn += ppn[key];
            }

            $('#subtotal').text(rupiah(result_subtotal));
            $('#ppn').text(rupiah(result_ppn));
            $('#total').text(rupiah(result_subtotal + result_ppn));
            $('#total_faktur').text(rupiah(result_subtotal + result_ppn));
            $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn));
            $('#input_sisa_tagihan').val(result_subtotal + result_ppn);

            $('#input_subtotal').val(result_subtotal);
            $('#input_ppn').val(result_ppn);
            $('#input_total').val(result_subtotal + result_ppn);
        }

        function load_select_2(id) {        
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk',
                width: '100px'
            });

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
            var selected = $(thisElement).find('option:selected').data('harga_beli');
            // $('#harga_satuan_' + no).val(selected);
            // $('#jumlah_' + no).val(selected);
            AutoNumeric.set('#harga_satuan_' + no,selected);
            AutoNumeric.set('#jumlah_' + no,selected);

            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0 ;
            subtotal[no] = kuantitas * parseFloat(selected);
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
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            
            if(val_harga_satuan){
                AutoNumeric.set('#harga_satuan_' + no,val_harga_satuan);
            }else{
                AutoNumeric.set('#harga_satuan_' + no,AutoNumeric.getNumber('#harga_satuan_' + no));
            }
            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            AutoNumeric.set('#jumlah_' + no,subtotal[no]);
            get_pajak($('#pajak_' + no), no);
            load();
        }

        function change_jumlah(no) {
            AutoNumeric.set('#jumlah_' + no,AutoNumeric.getNumber('#jumlah_' + no));
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            harga_satuan = $('#harga_satuan_' + no).val() ? parseFloat($('#harga_satuan_' + no).val()) : 0;
            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            $('#jumlah_' + no).val(subtotal[no]);

            get_pajak($('#pajak_'+no), no);

            load();
        }

        function check_null(element) {
            if (element.value.trim() === "") {
                element.value = 0;
                load();
            }

        }

        function hapus(no) {
            $('#list_' + no).remove();
            subtotal[no] = 0;
            ppn[no] = 0;
            load();
        }

        function create_row() {
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required @if(isset($pemesanan)) disabled @endif>
                            <option selected disabled hidden>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" 
                                    data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" 
                        name="kuantitas[]" value="1" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="unit_${i}" name="unit[]" readonly></td>
                    <td style="padding: 10px !important;" id="harga_satuan_td_${i}"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" 
                        name="harga_satuan[]" value="0" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;" id="diskon_per_baris_td_${i}">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" 
                        onchange="get_pajak(this, ${i})" required>
                        <option value="0" data-persen="0" >Pilih pajak</option>
                        <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;display:none;" id="jumlah_td_${i}"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" step="any" @if(isset($pemesanan)) disabled @endif></td>
                </tr>
            `);
            load_select_2(i);
        };

        // $('#input_ongkos_kirim').on("keyup", function() {
        //     $('#total_faktur').text(rupiah(result_subtotal + result_ppn +(int)$(this).val()));
        // })

        $("#info_pengiriman").change(function() {
            if(this.checked) {
                $('.info_pengiriman').show();
            }else{
                $('.info_pengiriman').hide();
            }
        });

        $('#sama_dengan_penagihan').change(function() {
            if(this.checked) {
                $('#alamat_pengiriman').hide();
                $('.alamat_pengiriman').hide();
            }else{
                $('#alamat_pengiriman').show();
                $('.alamat_pengiriman').show();
            }
        })

        $( document ).ready(function() {
            $('#info_pengiriman').prop('checked', true).trigger("change");

            $('#supplier').selectpicker();

            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));

            const fp_pengiriman = flatpickr("#tanggal_pengiriman", {
                dateFormat: "d/m/Y"
            });
            fp_pengiriman.setDate(new Date('{{ date("Y-m-d") }}'));

            const fp_jatuh_tempo = flatpickr("#tanggal_jatuh_tempo", {
                dateFormat: "d/m/Y"
            });
            fp_jatuh_tempo.setDate(new Date('{{ date("Y-m-d") }}'));
            @if(isset($pembelian))
                const sup = $('#supplier')
                sup.selectpicker('val','{{ $pembelian->id_supplier }}')
                alamat_supplier(sup[0]).then(function() {
                    $('#alamat').val('{{ $pembelian->alamat }}');
                })
                $('#email').val('{{ $pembelian->email }}')
                $('#detail_alamat').val('{{ $pembelian->detail_alamat }}')

                fp_transaksi.setDate(new Date('{{ $pembelian->tanggal_transaksi }}'));
                fp_pengiriman.setDate(new Date('{{ $pembelian->tanggal_pengiriman }}'));
                fp_jatuh_tempo.setDate(new Date('{{ $pembelian->tanggal_jatuh_tempo }}'));
                
                $('#gudang').val('{{ $pembelian->id_gudang }}')

                $('#kirim_melalui').val('{{ $pembelian->kirim_melalui }}')
                $('#no_pelacakan').val('{{ $pembelian->no_pelacakan }}')

                $('#pesan').val('{{ $pembelian->pesan }}')
                $('#memo').val('{{ $pembelian->memo }}')

                $('#input_ongkos_kirim').val('{{ $pembelian->ongkos_kirim }}')

                var x = 1;
                load_select_2(x);
                @foreach($detail_pembelian as $v)
                    $('#produk_'+x).val('{{ $v->id_produk }}').trigger('change');
                    $('#deskripsi_'+x).val('{{ $v->deskripsi }}');
                    $('#kuantitas_'+x).val('{{ $v->kuantitas }}').trigger('keyup');
                    $('#unit_'+x).val('{{ $v->unit }}');
                    $('#harga_satuan_'+x).val('{{ $v->harga_satuan }}').trigger('keyup');
                    @if($v->pajak != 0)
                        $('#pajak_'+x).val('11').trigger('change');
                    @else
                        $('#pajak_'+x).val('0').trigger('change');
                    @endif
                    // $('#harga_satuan_td_'+x).hide();
                    // $('#diskon_per_baris_td_'+x).hide();
                    create_row();
                    x++;
                @endforeach
                hapus(x);   
            @else
                load_select_2(1);
            @endif         
        });
       

        @if(isset($penawaran) || isset($pemesanan))
        $('#insertForm').submit(function() {
            $('.form-control').removeAttr('disabled');
        });
        @endif

        function alamat_supplier(thisElement) {
            var selected = $(thisElement).find('option:selected').val();
            $('#alamat').empty();
            return $.ajax({
                url: '{{ url("supplier/alamat") }}',
                type: 'GET',
                data: {
                    id: selected
                },
                success: function (response) {
                    $('#alamat').append('<option selected disabled hidden value="">Pilih Alamat</option>');
                    for(var i = 0; i < response.length; i++){
                        console.log(response[i]);
                        $('#alamat').append('<option>'+response[i].alamat+'</option>');
                    }
                }
            });
        }
    </script>
@endsection
