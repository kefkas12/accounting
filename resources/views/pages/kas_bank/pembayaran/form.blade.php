@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form action="{{ url('/kas_bank/pembayaran/insert') }}" method ="POST" id="form">
                    @csrf
                        <div class="card">
                            <div class="card-body ">
                                <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Buat Pembayaran</h2>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row mb-2">
                                            <label for="nama" class="col-sm-3 col-form-label">Kas/Bank <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="kas_bank" id="kas_bank" required>
                                                    @foreach($kas_bank as $v)
                                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="tanggal" class="col-sm-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ isset($pembayaran) ? $pembayaran->tanggal : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="nama_perusahaan" class="col-sm-3 col-form-label">Nama Perusahaan</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ isset($pelanggan) ? $pelanggan->nama_perusahaan : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="nomor_handphone" class="col-sm-3 col-form-label">Nomor Handphone</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="nomor_handphone" name="nomor_handphone" placeholder="Contoh: 0812 9374 546"  value="{{ isset($pelanggan) ? $pelanggan->nomor_handphone : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="nomor_telepon" class="col-sm-3 col-form-label">Nomor Telepon</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="nomor_telepon" name="nomor_telepon" placeholder="Contoh: 021 83746579" value="{{ isset($pelanggan) ? $pelanggan->nomor_telepon : '' }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2">
                                            <label for="fax" class="col-sm-3 col-form-label">Fax</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="fax" name="fax" placeholder="Contoh: 012 9374867" value="{{ isset($pelanggan) ? $pelanggan->fax : '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 12.3765.748.0-132.546" value="{{ isset($pelanggan) ? $pelanggan->npwp : '' }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2">
                                            <label for="alamat" class="col-sm-3 col-form-label">Alamat<button type="button" id="tambah_alamat" class="btn btn-primary ml-3" onclick="additional_alamat()">Tambah</button></label>
                                            <div class="col-sm-9" id="additional_alamat">
                                                <textarea class="form-control mb-2" id="alamat" name="alamat" placeholder="Contoh: Jalan Indonesia Block C No. 22">{{ isset($pelanggan) ? $pelanggan->alamat : '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success btn-lg px-5">
                                                <i class="fa fa-save" style="font-size: 1.5em;"></i>
                                            </button>
                                            <a href="{{ url('kas_bank/pembayaran') }}" class="btn btn-light btn-lg px-5"><i class="fa fa-trash" style="font-size: 1.5em;"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
            if(value){
                $('#additional_alamat').append(
                    `<textarea class="form-control mb-2" name="additional_alamat[]" placeholder="Contoh: Jalan Indonesia Block C No. 22">${value}</textarea>`
                );
            }else{
                $('#additional_alamat').append(
                    `<textarea class="form-control mb-2" name="additional_alamat[]" placeholder="Contoh: Jalan Indonesia Block C No. 22"></textarea>`
                );
            }
            
        }
    </script>
@endsection
