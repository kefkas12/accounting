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
                        Buat Akun Baru
                    </div>
                    <div class="card-body ">
                        <form method="POST" @if (isset($jurnal)) action="{{ url('akun/edit').'/'.$akun->id }}" @else action="{{ url('akun/insert') }}" @endif id="insertForm">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <b>Informasi Akun</b>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="nama">Nama</label>
                                            <input type="text" class="form-control" id="nama"
                                                name="nama">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="nomor">Nomor</label>
                                            <input type="text" class="form-control" id="nomor"
                                                name="nomor">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="nomor">Kategori</label>
                                            <select class="form-control" name="kategori" id="kategori">
                                                @foreach($kategori as $v)
                                                <option value="{{ $v->id }}" data-nomor="{{ $v->next_nomor }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('akun') }}" class="btn btn-light">Batalkan</a>
                                        <button type="submit" class="btn btn-primary">Buat Akun</button>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
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
        var result_debit = 0;
        var result_kredit = 0;

        $(document).ready(function() {
            $('#kategori').trigger('change')
        })

        $('#kategori').change(function() {
            const selectedOption = $(this).find('option:selected');
            const nomor = selectedOption.data('nomor').split("-");

            no = parseInt(nomor[1])+1;
            $('#nomor').val(nomor[0]+"-"+no);
        });
    </script>
@endsection
