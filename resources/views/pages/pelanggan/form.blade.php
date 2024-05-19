@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-transparent border-0">
                        Buat Kontak Pelanggan
                    </div>
                    <div class="card-body ">
                        <form action="{{ url('pelanggan/insert') }}" method ="POST" id="form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="nama"><strong>Nama Pelanggan</strong></label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($pelanggan) ? $pelanggan->nama : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><strong>Email</strong></label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ isset($pelanggan) ? $pelanggan->email : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_perusahaan"><strong>Nama Perusahaan</strong></label>
                                        <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ isset($pelanggan) ? $pelanggan->nama_perusahaan : '' }}">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="nomor_handphone"><strong>Nomor handphone</strong></label>
                                            <input type="number" class="form-control" id="nomor_handphone" name="nomor_handphone" placeholder="Contoh: 0812 9374 546"  value="{{ isset($pelanggan) ? $pelanggan->nomor_handphone : '' }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="nomor_telepon"><strong>Nomor Telepon</strong></label>
                                            <input type="number" class="form-control" id="nomor_telepon" name="nomor_telepon" placeholder="Contoh: 021 83746579" value="{{ isset($pelanggan) ? $pelanggan->nomor_telepon : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="fax"><strong>Fax</strong></label>
                                            <input type="number" class="form-control" id="fax" name="fax" placeholder="Contoh: 012 9374867" value="{{ isset($pelanggan) ? $pelanggan->fax : '' }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="npwp"><strong>NPWP</strong></label>
                                            <input type="text" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 12.3765.748.0-132.546" value="{{ isset($pelanggan) ? $pelanggan->npwp : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="additional_alamat">
                                        
                                        <label for="alamat" class="mb-2"><strong>Alamat</strong><button type="button" id="tambah_alamat" class="btn btn-primary ml-3" onclick="additional_alamat()">Tambah</button></label>
                                        <textarea class="form-control mb-2" id="alamat" name="alamat" placeholder="Contoh: Jalan Indonesia Block C No. 22">{{ isset($pelanggan) ? $pelanggan->alamat : '' }}</textarea>
                                        
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('pelanggan') }}" class="btn btn-light">Batalkan</a>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col"></div>
                                <div class="col">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($pelanggan))
    <script>
    $( document ).ready(function() {
        $('#form').attr('action','{{ url("pelanggan/edit")."/".$pelanggan->id }}')
        @foreach($additional_alamat as $v)
        additional_alamat('{{ $v->alamat }}');
        @endforeach
    });
    </script>
    @endif
    <script>
        var value = '';
        function additional_alamat(value = null){
            $('#additional_alamat').append(
                `<textarea class="form-control mb-2" name="additional_alamat[]">${value}</textarea>`
            );
        }
    </script>
@endsection
