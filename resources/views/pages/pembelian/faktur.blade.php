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
                        Buat Faktur Pembelian
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('pembelian/faktur') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="supplier">Supplier</label>
                                    <select class="form-control" id="supplier" name="supplier" required>
                                        <option selected disabled>Pilih kontak</option>
                                        @foreach ($supplier as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group col-md-3">
                                </div>
                                <div class="form-group col-md-3 d-flex justify-content-end">
                                    Total Rp <span id="total_faktur">0</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="alamat">Alamat Penagihan</label><br>
                                    <textarea class="form-control" name="alamat" id="alamat"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pr-4">
                                        <label for="tanggal_transaksi">Tgl. transaksi</label>
                                        <input type="date" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi">
                                    </div>
                                    <div class="form-group">

                                    </div>
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    <label for="tanggal_jatuh_tempo">Tgl. jatuh tempo</label>
                                    <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo"> 
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kuantitas</th>
                                            <th>Harga Satuan</th>
                                            <th>Pajak</th>
                                            <th>Jumlah</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <th>
                                                <select class="form-control" name="produk[]" onchange="get_data(this, 1)"
                                                    required>
                                                    <option selected disabled>Pilih produk</option>
                                                    @foreach ($produk as $v)
                                                        <option value="{{ $v->id }}" data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <td><input type="number" class="form-control" id="kuantitas_1" name="kuantitas[]" value="1" onkeyup="change_jumlah(1)"></td>
                                            <td><input type="number" class="form-control" id="harga_satuan_1" name="harga_satuan[]" value="0" onkeyup="change_jumlah(1)"></td>
                                            <td>
                                                <select class="form-control" id="pajak" name="pajak[]" onchange="get_pajak(this, 1)" required>
                                                    <option value="0"  data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" id="jumlah_1" name="jumlah[]" value="0"></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6"><button type="button" class="btn btn-primary btn-block"
                                                    onclick="create_row()">Tambah</button></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <span>Subtotal</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="subtotal">Rp 0,00</span>
                                                <input type="text" id="input_subtotal" name="subtotal" hidden>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <span>PPN</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="ppn">Rp 0,00</span>
                                                <input type="text" id="input_ppn" name="ppn" hidden>
                                            </div>
                                        </div>
                                        <hr class="bg-white">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <span>Total</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="total">Rp 0,00</span>
                                                <input type="text" id="input_total" name="total" hidden>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col">
                                                <span>Sisa Tagihan</span>
                                            </div>
                                            <div class="col d-flex justify-content-end">
                                                <span id="sisa_tagihan">Rp 0,00</span>
                                                <input type="text" id="input_sisa_tagihan" name="sisa_tagihan" hidden>
                                            </div>
                                        </div>
                                        <div class="row my-5">
                                            <div class="col d-flex justify-content-end">
                                                <a href="{{ url('pembelian') }}" class="btn btn-light">Batalkan</a>
                                                <button type="submit" class="btn btn-primary">Buat Faktur</button>
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
    </div>
    <script>
        var i = 1;
        var ppn = {};
        var subtotal =  {};
        var result_subtotal = 0;
        var result_ppn = 0;

        const rupiah = (number)=>{
            return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
            }).format(number);
        }

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
            $('#total').text(rupiah(result_subtotal+result_ppn));
            $('#total_faktur').text(rupiah(result_subtotal+result_ppn));
            $('#sisa_tagihan').text(rupiah(result_subtotal+result_ppn));

            $('#input_subtotal').val(result_subtotal);
            $('#input_ppn').val(result_ppn);
            $('#input_total').val(result_subtotal+result_ppn);
            $('#input_sisa_tagihan').val(result_subtotal+result_ppn);
        }

        function get_data(thisElement, no) {
            var selected = $(thisElement).find('option:selected').data('harga_beli');
            $('#harga_satuan_'+no).val(selected);
            $('#jumlah_'+no).val(selected);
            subtotal[no] = parseInt($('#kuantitas_'+no).val()) * parseInt(selected);
            load();
        }

        function get_pajak(thisElement, no){
            var selected = parseInt($(thisElement).find('option:selected').data('persen'));
            if(selected != 0){
                ppn[no] = selected * $('#jumlah_'+no).val() /100;
            }else{
                ppn[no] = 0;
            }
            load();
        }

        function change_jumlah(no){
            subtotal[no] = parseInt($('#kuantitas_'+no).val()) * parseInt($('#harga_satuan_'+no).val());
            $('#jumlah_'+no).val(subtotal[no]);
            load();
        }

        function hapus(no){
            $('#list_'+no).remove();
            subtotal[no] = 0;
            ppn[no] = 0;
            load();
        }

        function create_row() {
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th>
                        <select class="form-control" name="produk[]" onchange="get_data(this, ${i})" required>
                            <option selected disabled>Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_beli="{{ $v->harga_beli }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td><input type="number" class="form-control" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_jumlah(${i})"></td>
                    <td><input type="number" class="form-control" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onkeyup="change_jumlah(${i})"></td>
                    <td>
                        <select class="form-control" name="pajak[]" onchange="get_pajak(this, ${i})"  required>
                            <option value="0" data-persen="0">Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control" id="jumlah_${i}" name="jumlah[]" value="0"></td>
                    <td><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
        };
    </script>
@endsection
