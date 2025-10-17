@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form method="POST" @if (isset($jurnal)) action="{{ url('jurnal/edit').'/'.$jurnal->id }}" @else action="{{ url('jurnal/insert') }}" @endif id="insertForm">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-body ">
                            <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Jurnal Umum</h2>
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="tanggal_transaksi">Tgl. transaksi <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_transaksi"
                                        name="tanggal_transaksi" required>
                                </div>
                                <div class="form-group col-md-3 pr-4">
                                    <label for="gudang">Gudang</label>
                                    <select class="form-control" name="gudang" id="gudang">
                                        @if(Auth::user()->id_gudang)
                                            @foreach($gudang as $v)
                                            <option value="{{ $v->id }}" selected>{{ $v->nama }}</option>
                                            @endforeach
                                        @else
                                            <option selected disabled hidden>Pilih Gudang</option>
                                            @if(isset($gudang))
                                            @foreach($gudang as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                            @endforeach
                                            @else
                                            <option disabled>No result found</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div style="overflow-x: auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 300px !important;padding: 10px !important;">Akun</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Debit</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Kredit</th>
                                            <th scope="col" style="min-width: 25px !important;padding: 10px !important;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <th style="padding: 10px !important;">
                                                <select class="form-control" name="akun[]" id="akun_1" required>
                                                    <option value="" hidden selected disabled>Pilih akun</option>
                                                    @foreach ($akun as $v)
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
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="debit_1" name="debit[]"
                                                    value="0" onblur="change_debit(1)"></td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="kredit_1" name="kredit[]"
                                                    value="0" onblur="change_kredit(1)"></td>
                                            <td style="padding: 10px !important;">
                                                <a href="javascript:;" onclick="clear_row(1)"><i class="fa fa-trash text-primary"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 10px !important;">
                                                <select class="form-control" name="akun[]" id="akun_2" required>
                                                    <option value="" hidden selected disabled>Pilih akun</option>
                                                    @foreach ($akun as $v)
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
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="debit_2" name="debit[]"
                                                    value="0" onblur="change_debit(2)"></td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="kredit_2" name="kredit[]"
                                                    value="0" onblur="change_kredit(2)"></td>
                                            <td style="padding: 10px !important;">
                                                <a href="javascript:;" onclick="clear_row(2)"><i class="fa fa-trash text-primary"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-7"></div>
                                <div class="col">
                                    <span>Total Debit</span>
                                </div>
                                <div class="col">
                                    <span>Total Kredit</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-7"></div>
                                <div class="col">
                                    <span id="debit">Rp 0,00</span>
                                    <input type="text" id="input_debit" name="total_debit" hidden>
                                </div>
                                <div class="col">
                                    <span id="kredit">Rp 0,00</span>
                                    <input type="text" id="input_kredit" name="total_kredit" hidden>
                                </div>
                            </div>
                            <div class="row my-5">
                                <div class="col d-flex justify-content-end">
                                    @if (isset($jurnal))
                                    <a href="{{ url('laporan/jurnal') }}" class="btn btn-danger">Batal</a>
                                    <button type="submit" class="btn btn-success" onclick="check_balance();">Ubah
                                        Jurnal Umum</button>
                                    @else
                                    <a href="{{ url('akun') }}" class="btn btn-light">Batalkan</a>
                                    <button type="submit" class="btn btn-primary" onclick="check_balance();">Buat
                                        Jurnal Umum</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var x = 0;
        var i = 2;
        var result_debit = 0;
        var result_kredit = 0;

        $(document).ready(function() {
            @if (isset($jurnal))
                $('#tanggal_transaksi').val('{{ $jurnal->tanggal_transaksi }}')
                $('#gudang').val('{{ $jurnal->id_gudang }}')
                x = 1;
                @foreach ($jurnal->detail_jurnal as $v)
                    if(x < 3){
                        load_select_2(x);
                    }
                    $('#id_detail_jurnal_' + x).val('{{ $v->id }}').trigger('change');
                    $('#akun_' + x).val('{{ $v->id_akun }}').trigger('change');
                    $('#deskripsi_' + x).val(`{{ $v->deskripsi }}`);
                    change_debit(x, {{ $v->debit }});
                    change_kredit(x, {{ $v->kredit }});
                    x++;
                    if(x>2){
                        create_row();
                    }
                @endforeach
                hapus(x+1)
            @else
                load_select_2(1);
                load_select_2(2);
            @endif
        });

        function check_balance() {
            event.preventDefault();
            if (result_debit != result_kredit) {
                Swal.fire({
                    title: 'Transaksi Tidak Balance.',
                    text: 'Debit harus sama dengan Kredit.',
                    icon: 'error'
                })
            } else {
                $('#insertForm').submit();
            }
            //
        }
        function load_select_2(id) {
            $("#akun_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih akun'
            });
            if(id > 1){
                $('#akun_'+id).on('select2:select', function (e) {
                    if(id >= x){
                        x += 1;
                        create_row();
                    }
                });
            }
            
            new AutoNumeric("#debit_" + id, {
                commaDecimalCharDotSeparator: false,
                watchExternalChanges: true,
                modifyValueOnWheel : false,
                showOnlyNumbersOnFocus : true,
                unformatOnSubmit: true,
                noSeparatorOnFocus: true,
                allowDecimalPadding: false,
                unformatOnSubmit: true
            });
            new AutoNumeric("#kredit_" + id, {
                commaDecimalCharDotSeparator: false,
                watchExternalChanges: true,
                modifyValueOnWheel : false,
                showOnlyNumbersOnFocus : true,
                unformatOnSubmit: true,
                noSeparatorOnFocus: true,
                allowDecimalPadding: false,
                unformatOnSubmit: true
            });

            document.getElementById("debit_" + id).addEventListener("paste", function (e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('Text');
                AutoNumeric.set(this, pastedData);
            });

            document.getElementById("kredit_" + id).addEventListener("paste", function (e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('Text');
                AutoNumeric.set(this, pastedData);
            });
        }
        
        var debit = {};
        var kredit = {};

        function load() {
            result_debit = 0;
            for (var key in debit) {
                result_debit += debit[key];
            }

            result_kredit = 0;
            for (var key in kredit) {
                result_kredit += kredit[key];
            }
            $('#debit').text(rupiah(result_debit));
            $('#kredit').text(rupiah(result_kredit));

            $('#input_debit').val(result_debit);
            $('#input_kredit').val(result_kredit);
        }

        function change_debit(no, val_debit = null) {
            if(val_debit){
                AutoNumeric.set('#debit_' + no,val_debit);
            }else{
                AutoNumeric.set('#debit_' + no,AutoNumeric.getNumber('#debit_' + no));
            }
            
            debit[no] = parseFloat(AutoNumeric.getNumber('#debit_' + no));
            load();
        }

        function change_kredit(no, val_kredit = null) {
            if(val_kredit){
                AutoNumeric.set('#kredit_' + no,val_kredit);
            }else{
                AutoNumeric.set('#kredit_' + no,AutoNumeric.getNumber('#kredit_' + no));
            }
            kredit[no] = parseFloat(AutoNumeric.getNumber('#kredit_' + no));
            load();
        }

        function hapus(no) {
            $('#list_' + no).remove();
            debit[no] = 0;
            kredit[no] = 0;
            load();
        }

        function clear_row(no) {
            $('#akun_'+no).val('').trigger('change');
            $('#id_detail_jurnal_'+no).val('');
            $('#deskripsi_'+no).val('');
            AutoNumeric.set('#debit_' + no,0);
            AutoNumeric.set('#kredit_' + no,0);
            debit[no] = 0;
            kredit[no] = 0;
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
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="debit_${i}" name="debit[]" value="0" onblur="change_debit(${i})"></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="kredit_${i}" name="kredit[]" value="0" onblur="change_kredit(${i})"></td>
                    <td style="padding: 10px !important;"><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
            load_select_2(i);
        };

        
    </script>
@endsection
