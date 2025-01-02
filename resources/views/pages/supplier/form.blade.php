@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form action="{{ url('supplier/insert') }}" method ="POST" id="form">
                    @csrf
                    <div class="card">
                        <div class="card-body ">
                            <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Buat Kontak Pelanggan</h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row mb-2">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama Supplier / Pemasok <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($supplier) ? $supplier->nama : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ isset($supplier) ? $supplier->email : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="nama_perusahaan" class="col-sm-3 col-form-label">Nama Perusahaan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ isset($supplier) ? $supplier->nama_perusahaan : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="nomor_handphone" class="col-sm-3 col-form-label">Handphone</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="nomor_handphone" name="nomor_handphone" placeholder="Contoh: 0812 9374 546"  value="{{ isset($supplier) ? $supplier->nomor_handphone : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="nomor_telepon" class="col-sm-3 col-form-label">No. Telp. Bisnis</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="nomor_telepon" name="nomor_telepon" placeholder="Contoh: 021 83746579" value="{{ isset($supplier) ? $supplier->nomor_telepon : '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2">
                                        <label for="fax" class="col-sm-3 col-form-label">Fax</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="fax" name="fax" placeholder="Contoh: 012 9374867" value="{{ isset($supplier) ? $supplier->fax : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="npwp" name="npwp" placeholder="Contoh: 12.3765.748.0-132.546" value="{{ isset($supplier) ? $supplier->npwp : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="alamat" name="alamat" placeholder="Contoh: Jalan Indonesia Block C No. 22"> {{ isset($supplier) ? $supplier->alamat : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                                <i class="fa fa-save" style="font-size: 1.5em;"></i>
                                            </button>
                                        <a href="{{ url('supplier') }}" class="btn btn-light btn-lg px-5"><i class="fa fa-trash" style="font-size: 1.5em;"></i></a>
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
    @if(isset($supplier))
    <script>
        $('#form').attr('action','{{ url("supplier/edit")."/".$supplier->id }}')
    </script>
    @endif
@endsection
