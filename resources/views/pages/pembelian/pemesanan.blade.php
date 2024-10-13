@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header border-0">
                        <div class="row">
                            <div class="col">
                                <a href="{{ url('pembelian') }}">Pembelian</a>
                            </div>
                        </div>
                        <div class="row text-sm">
                            <div class="col">
                                <h2>Buat Pemesanan Pembelian</h2>
                            </div>
                            <div class="col-sm-3 d-flex justify-content-end">
                                <select class="form-control" onchange="location = this.value;">
                                    <option selected disabled hidden>Pemesanan Pembelian</option>
                                    <option value="{{ url('pembelian/faktur') }}">Faktur Pembelian</option>
                                    <option value="{{ url('pembelian/pemesanan') }}">Pemesanan Pembelian</option>
                                    <option value="{{ url('pembelian/penawaran') }}">Penawaran Pembelian</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <form method="POST"
                        @if(isset($penawaran))
                            action="{{ url('pembelian/penawaran').'/pemesanan/'.$pembelian->id }}" 
                        @elseif(isset($pembelian))
                            action="{{ url('pembelian/pemesanan').'/'.$pembelian->id }}" 
                        @else
                            action="{{ url('pembelian/pemesanan') }}" 
                        @endif
                        id="insertForm"
                        >
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="supplier">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-control" id="supplier" name="supplier" required @if(isset($penawaran)) disabled @endif>
                                        <option selected disabled value="">Pilih kontak</option>
                                        @foreach ($supplier as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 pr-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                    
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="email">Pengiriman</label>
                                    <div class="form-check mb-4" >
                                        <input class="form-check-input" type="checkbox" id="info_pengiriman" name="info_pengiriman">
                                        <label class="form-check-label" for="info_pengiriman">
                                            Info Pengiriman
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-3 d-flex justify-content-end">
                                    Total Rp <span id="total_faktur">0,00</span>
                                </div>
                            </div>
                            <div class="form-row text-sm">
                                <div class="col-md-3 pr-4">
                                    <div class="form-group">
                                        <label for="alamat_penagihan">Alamat Penagihan</label><br>
                                        <textarea class="form-control" name="alamat_penagihan" id="alamat_penagihan" rows="1"></textarea>
                                    </div>
                                    <div class="form-group info_pengiriman" style="display:none">
                                        <label for="alamat_pengiriman">Alamat Pengiriman</label><br>
                                        <textarea class="form-control" name="alamat_pengiriman" id="alamat_pengiriman" rows="1" style="display:none"></textarea>
                                        <div class="form-check mb-4" >
                                            <input class="form-check-input" type="checkbox" id="sama_dengan_penagihan" name="sama_dengan_penagihan" checked>
                                            <label class="form-check-label" for="sama_dengan_penagihan">
                                                Sama dengan penagihan
                                            </label>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-2 pr-2">
                                    <div class="form-group">
                                        <label for="tanggal_transaksi">Tgl. transaksi</label>
                                        <input type="date" class="form-control" id="tanggal_transaksi"
                                            name="tanggal_transaksi" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_jatuh_tempo">Tgl. jatuh tempo</label>
                                        <input type="date" class="form-control" id="tanggal_jatuh_tempo"
                                            name="tanggal_jatuh_tempo" value="{{ date('Y-m-d', strtotime("+30 days")) }}">
                                    </div>
                                </div>
                                <div class="col-md-2 pr-2">
                                    <div class="form-group info_pengiriman" style="display:none">
                                        <label for="tanggal_pengiriman">Tgl. pengiriman</label>
                                        <input type="date" class="form-control" id="tanggal_pengiriman"
                                            name="tanggal_pengiriman" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="gudang">Gudang</label>
                                        <select class="form-control" id="gudang" name="gudang">
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
                                </div>
                                <div class="col-md-2 info_pengiriman" style="display:none">
                                    <div class="form-group">
                                        <label for="kirim_melalui">Kirim melalui</label>
                                        <input type="text" class="form-control" id="kirim_melalui"
                                            name="kirim_melalui">
                                    </div>
                                    <div class="form-group">
                                        <label for="no_pelacakan">No. pelacakan</label>
                                        <input type="text" class="form-control" id="no_pelacakan" name="no_pelacakan">
                                    </div>
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    @if(isset($penawaran))
                                    <label for="nomor_penawaran_pembelian">No Penawaran Pembelian</label> <br>
                                    <a href="{{ url('pembelian/detail').'/'.$pembelian->id }}">{{ $pembelian->no_str }}</a>
                                    @endif
                                </div>
                            </div>

                            <div style="overflow: auto">
                                <table class="table align-items-center table-flush">
                                    <!-- Your table headers -->
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 300px !important; padding: 10px !important;">Produk</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 100px !important; padding: 10px !important;">Kuantitas</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Harga Satuan</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Pajak</th>
                                            <th scope="col" style="min-width: 200px !important; padding: 10px !important;">Jumlah</th>
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" name="produk[]" id="produk_1" onchange="get_data(this, 1)"
                                                    required>
                                                    <option selected disabled hidden>Pilih produk</option>
                                                    @foreach ($produk as $v)
                                                        <option value="{{ $v->id }}"
                                                            data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <textarea class="form-control" name="deskripsi[]" id="deskripsi_1" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_1"
                                                    name="kuantitas[]" value="1" onkeyup="change_jumlah(1)"
                                                    onblur="check_null(this)" step="any"></td>
                                            <td style="padding: 10px !important;"><input type="number" class="form-control" id="harga_satuan_1"
                                                    name="harga_satuan[]" value="0" onkeyup="change_jumlah(1)"
                                                    onblur="check_null(this)" step="any"></td>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control" id="pajak_1" name="pajak[]"
                                                    onchange="get_pajak(this, 1)" required>
                                                    <option value="0" data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="number" class="form-control" id="jumlah_1" name="jumlah[]"
                                                    value="0" step="any"></td>
                                            <td style="padding: 10px !important;"><a href="javascript:;" onclick="create_row()"><i
                                                        class="fa fa-plus text-primary"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col"></div>
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
                                            <input type="text" id="input_sisa_tagihan" name="input_sisa_tagihan" hidden>
                                        </div>
                                    </div>
                                    <div class="row my-5">
                                        <div class="col d-flex justify-content-end">
                                            <a href="{{ url('pembelian') }}" class="btn btn-light">Batalkan</a>
                                            <button type="submit" class="btn btn-primary" onclick="buat();">Buat</button>
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

            $('#input_subtotal').val(result_subtotal);
            $('#input_ppn').val(result_ppn);
            $('#input_total').val(result_subtotal + result_ppn);
            $('#input_sisa_tagihan').val(result_subtotal + result_ppn);
        }

        function buat() {
            success = $('#supplier').val() != null ? true : false;
            if(success == true){
                $('#insertForm').submit();
            }
        }

        function get_data(thisElement, no) {
            var selected = $(thisElement).find('option:selected').data('harga_beli');
            $('#harga_satuan_' + no).val(selected);
            $('#jumlah_' + no).val(selected);
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0 ;
            subtotal[no] = kuantitas * parseFloat(selected);
            load();
        }

        function get_pajak(thisElement, no) {
            var selected = parseFloat($(thisElement).find('option:selected').data('persen'));

            if (selected != 0) {
                ppn[no] = selected * $('#jumlah_' + no).val() / 100;
            } else {
                ppn[no] = 0;
            }

            load();
        }

        function change_jumlah(no) {
            kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            harga_satuan = $('#harga_satuan_' + no).val() ? parseFloat($('#harga_satuan_' + no).val()) : 0;
            subtotal[no] = kuantitas * harga_satuan;
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
                        <select class="form-control" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required>
                            <option selected disabled hidden>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any"></td>
                    <td style="padding: 10px !important;">
                        <select class="form-control" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="number" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0" step="any"></td>
                    <td style="padding: 10px !important;"><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
        };

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
            }else{
                $('#alamat_pengiriman').show();
            }
        })

        @if(isset($pembelian))
        $( document ).ready(function() {
            $('#supplier').val('{{ $pembelian->id_supplier }}')
            $('#email').val('{{ $pembelian->email }}')
            $('#alamat').val('{{ $pembelian->alamat }}')
            $('#tanggal_transaksi').val('{{ $pembelian->tanggal_transaksi }}')
            $('#tanggal_jatuh_tempo').val('{{ $pembelian->tanggal_jatuh_tempo }}')

            var x = 1;
            @foreach($detail_pembelian as $v)
                $('#produk_'+x).val('{{ $v->id_produk }}');
                $('#deskripsi_'+x).val('{{ $v->deskripsi }}');
                $('#kuantitas_'+x).val('{{ $v->kuantitas }}').trigger('keyup');
                $('#harga_satuan_'+x).val('{{ $v->harga_satuan }}').trigger('keyup');
                @if($v->pajak != 0)
                    $('#pajak_'+x).val('11').trigger('change');
                @else
                    $('#pajak_'+x).val('0').trigger('change');
                @endif
                create_row();
                x++;
            @endforeach
            hapus(x);

            
        });
        @endif

        @if(isset($penawaran))
        document.getElementById('form').addEventListener('submit', function() {
            document.getElementById('supplier').removeAttribute('disabled');
        });
        @endif
    </script>
@endsection
