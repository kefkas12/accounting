@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <style>
        .chosen-container.chosen-with-drop .chosen-drop {
            position: relative;
        }
    </style>
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        Jurnal Umum
                    </div>
                    <div class="card-body ">
                        <form method="POST" @if (isset($jurnal)) action="{{ url('jurnal/edit').'/'.$jurnal->id }}" @else action="{{ url('jurnal/insert') }}" @endif id="insertForm">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-4">
                                    <label for="tanggal_transaksi">Tgl. transaksi</label>
                                    <input type="date" class="form-control" id="tanggal_transaksi"
                                        name="tanggal_transaksi">
                                </div>
                            </div>

                            <div style="overflow-x: auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 300px !important;">Akun</th>
                                            <th scope="col" style="min-width: 250px !important;">Deskripsi</th>
                                            <th scope="col" style="min-width: 250px !important;">Debit</th>
                                            <th scope="col" style="min-width: 250px !important;">Kredit</th>
                                            <th scope="col" style="min-width: 25px !important;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <th>
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
                                            <td>
                                                <textarea class="form-control" id="deskripsi_1" name="deskripsi[]"></textarea>
                                            </td>
                                            <td><input type="number" class="form-control" id="debit_1" name="debit[]"
                                                    value="0" onkeyup="change_debit(1)"></td>
                                            <td><input type="number" class="form-control" id="kredit_1" name="kredit[]"
                                                    value="0" onkeyup="change_kredit(1)"></td>
                                            <td></td>
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
                                    <a href="{{ url('jurnal') }}" class="btn btn-danger">Batal</a>
                                    <button type="button" class="btn btn-success" onclick="check_balance();">Ubah
                                        Jurnal Umum</button>
                                    @else
                                    <a href="{{ url('akun') }}" class="btn btn-light">Batalkan</a>
                                    <button type="button" class="btn btn-primary" onclick="check_balance();">Buat
                                        Jurnal Umum</button>
                                    @endif
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
        var result_debit = 0;
        var result_kredit = 0;

        $(document).ready(function() {
            @if (isset($jurnal))
                $('#tanggal_transaksi').val('{{ $jurnal->tanggal_transaksi }}')

                var x = 1;
                load_select_2(x);
                @foreach ($jurnal->detail_jurnal as $v)
                    $('#id_detail_jurnal_' + x).val('{{ $v->id }}').trigger('change');
                    $('#akun_' + x).val('{{ $v->id_akun }}').trigger('change');
                    $('#deskripsi_' + x).val(`{{ $v->deskripsi }}`);
                    $('#debit_' + x).val('{{ $v->debit }}').trigger('keyup');
                    $('#kredit_' + x).val('{{ $v->kredit }}').trigger('keyup');
                    x++;
                    create_row();
                @endforeach
                hapus(x)
            @else
                load_select_2(1);
                create_row();
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
            $('#akun_'+id).attr('onchange','create_row()');
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

        function change_debit(no) {
            debit[no] = $('#debit_' + no).val() == '' ? 0 : parseFloat($('#debit_' + no).val());
            load();
        }

        function change_kredit(no) {
            kredit[no] = $('#kredit_' + no).val() == '' ? 0 : parseFloat($('#kredit_' + no).val());
            load();
        }

        function hapus(no) {
            $('#list_' + no).remove();
            debit[no] = 0;
            kredit[no] = 0;
            load();
        }

        function create_row() {
            $('#akun_'+i).removeAttr('onchange');
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th>
                        <select class="form-control" name="akun[]" id="akun_${i}" required>
                            <option value="" hidden selected disabled>Pilih akun</option>
                            @foreach ($akun as $v)
                                <option value="{{ $v->id }}" >({{ $v->nomor }}) {{ $v->nama }} ({{ $v->nama_kategori }})</option>
                            @endforeach
                        </select>
                        @if (isset($jurnal)) 
                            <input type="number" name="id_detail_jurnal[]" id="id_detail_jurnal_${i}" hidden>
                        @endif
                    </th>
                    <td><textarea class="form-control" id="deskripsi_${i}" name="deskripsi[]"></textarea></td>
                    <td><input type="number" class="form-control" id="debit_${i}" name="debit[]" value="0" onkeyup="change_debit(${i})"></td>
                    <td><input type="number" class="form-control" id="kredit_${i}" name="kredit[]" value="0" onkeyup="change_kredit(${i})"></td>
                    <td><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>
                </tr>
            `);
            load_select_2(i);
        };
    </script>
@endsection
