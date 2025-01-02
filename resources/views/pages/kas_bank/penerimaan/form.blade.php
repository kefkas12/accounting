@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form action="{{ url('/kas_bank/penerimaan/insert') }}" method ="POST" id="form">
                    @csrf
                        <div class="card">
                            <div class="card-body ">
                                <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Buat Penerimaan</h2>
                                <div class="form-row">
                                    <div class="form-group col-md-3 pr-4">
                                        <label for="nama">Kas/Bank <span class="text-danger">*</span></label>
                                        <select class="form-control" name="kas_bank" id="kas_bank" required>
                                            @foreach($kas_bank as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 pr-4">
                                        <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ isset($penerimaan) ? $penerimaan->tanggal : '' }}" required>
                                    </div>
                                </div>
                                <div style="overflow-x: auto">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="min-width: 300px !important;padding: 10px !important;">Akun</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Atas Penerimaan</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Deskripsi</th>
                                                <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Nilai</th>
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
                                                    @if (isset($penerimaan)) 
                                                        <input type="number" name="id_detail_penerimaan[]" id="id_detail_penerimaan_1" hidden>
                                                    @endif
                                                </th>
                                                <td style="padding: 10px !important;">
                                                    <textarea class="form-control" id="atas_penerimaan_1" name="atas_penerimaan[]"></textarea>
                                                </td>
                                                <td style="padding: 10px !important;">
                                                    <textarea class="form-control" id="deskripsi_1" name="deskripsi[]"></textarea>
                                                </td>
                                                <td style="padding: 10px !important;">
                                                    <input type="text" class="form-control" id="nilai_1" name="nilai[]"
                                                        value="0" onblur="change_nilai(1)" required>
                                                    </td>
                                                <td style="padding: 10px !important;" hidden>
                                                    <a href="javascript:;" onclick="clear_row(1)"><i class="fa fa-trash text-primary"></i></a>
                                                </td>
                                                <td style="padding: 10px !important;">
                                                    <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-10"></div>
                                    <div class="col">
                                        <span>Nilai</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-10"></div>
                                    <div class="col">
                                        <span id="nilai">Rp 0,00</span>
                                        <input type="text" id="input_nilai" name="total_nilai" hidden>
                                    </div>
                                </div>
                                <div class="row my-5">
                                    <div class="col d-flex justify-content-end">
                                        @if (isset($penerimaan))
                                        <a href="{{ url('kas_bank/penerimaan') }}" class="btn btn-danger">Batal</a>
                                        <button type="submit" class="btn btn-success" onclick="check_balance();">Ubah
                                            Penerimaan</button>
                                        @else
                                        <a href="{{ url('kas_bank/penerimaan') }}" class="btn btn-light">Batalkan</a>
                                        <button type="submit" class="btn btn-primary" onclick="check_balance();">Buat
                                            Penerimaan</button>
                                        @endif
                                    </div>
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
        var i = 1;
        var result_nilai = 0;

        $(document).ready(function() {
            @if (isset($penerimaan))
                $('#kas_bank').val('{{ $penerimaan->id_kas_bank }}')
                $('#tanggal').val('{{ $penerimaan->tanggal }}')
                x = 1;
                @foreach ($penerimaan->detail_penerimaan as $v)
                    load_select_2(x);
                    $('#id_detail_penerimaan_' + x).val('{{ $v->id }}').trigger('change');
                    $('#akun_' + x).val('{{ $v->id_akun }}').trigger('change');
                    $('#atas_penerimaan_' + x).val(`{{ $v->atas_penerimaan }}`);
                    $('#deskripsi_' + x).val(`{{ $v->deskripsi }}`);
                    change_nilai(x, {{ $v->nilai }});
                    create_row();
                @endforeach
                hapus(x+1)
            @else
                load_select_2(1);
            @endif
        });

        function load_select_2(id) {
            $("#akun_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih akun'
            });
            // $('#akun_'+id).on('select2:select', function (e) {
            //         create_row();
            // });
            
            new AutoNumeric("#nilai_" + id, {
                commaDecimalCharDotSeparator: false,
                watchExternalChanges: true,
                modifyValueOnWheel : false,
                showOnlyNumbersOnFocus : true,
                unformatOnSubmit: true,
                noSeparatorOnFocus: true,
                allowDecimalPadding: false,
                unformatOnSubmit: true
            });

            document.getElementById("nilai_" + id).addEventListener("paste", function (e) {
                e.preventDefault();
                let pastedData = e.clipboardData.getData('Text');
                AutoNumeric.set(this, pastedData);
            });
        }


        
        var nilai = {};

        function load() {
            result_nilai = 0;
            for (var key in nilai) {
                result_nilai += nilai[key];
            }

            $('#nilai').text(rupiah(result_nilai));

            $('#input_nilai').val(result_nilai);
        }

        function change_nilai(no, val_nilai = null) {
            if(val_nilai){
                AutoNumeric.set('#nilai_' + no,val_nilai);
            }else{
                AutoNumeric.set('#nilai_' + no,AutoNumeric.getNumber('#nilai_' + no));
            }
            
            nilai[no] = parseFloat(AutoNumeric.getNumber('#nilai_' + no));
            load();
        }

        function hapus(no) {
            $('#list_' + no).remove();
            nilai[no] = 0;
            load();
        }

        function clear_row(no) {
            $('#akun_'+no).val('').trigger('change');
            $('#id_detail_penerimaan_'+no).val('');
            $('#atas_penerimaan_'+no).val('');
            $('#deskripsi_'+no).val('');
            AutoNumeric.set('#nilai_' + no,0);
            nilai[no] = 0;
            load();
        }

        function create_row() {
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control" name="akun[]" id="akun_${i}" required>
                            <option value="" hidden selected disabled>Pilih akun</option>
                            @foreach ($akun as $v)
                                <option value="{{ $v->id }}" >({{ $v->nomor }}) {{ $v->nama }} ({{ $v->nama_kategori }})</option>
                            @endforeach
                        </select>
                        @if (isset($penerimaan)) 
                            <input type="number" name="id_detail_penerimaan[]" id="id_detail_penerimaan_${i}" hidden>
                        @endif
                    </th>
                    <td style="padding: 10px !important;"><textarea class="form-control" id="atas_penerimaan_${i}" name="atas_penerimaan[]"></textarea></td>
                    <td style="padding: 10px !important;"><textarea class="form-control" id="deskripsi_${i}" name="deskripsi[]"></textarea></td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control" id="nilai_${i}" name="nilai[]" value="0" onblur="change_nilai(${i})" required></td>
                    <td style="padding: 10px !important;">
                        <a href="javascript:;" onclick="create_row()"><i class="fa fa-plus text-primary"></i></a><br>
                        <a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
            load_select_2(i);
        };

        
    </script>
@endsection
