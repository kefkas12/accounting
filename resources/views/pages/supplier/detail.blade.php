@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row">
                            <div class="col-sm-6">Informasi Kontak</div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('supplier/edit').'/'.$supplier->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('supplier/hapus').'/'.$supplier->id }}" class="btn btn-outline-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        Informasi Umum
                        <div class="row my-4">
                            <div class="col-sm-2">Nama</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->nama }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Alamat</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->alamat }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Nama Perusahaan</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->nama_perusahaan }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">NPWP</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->npwp }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Email</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->email }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Fax</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->fax }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Nomor Handphone</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->nomor_handphone }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Nomor Telepon</div>
                            <div class="col-sm-4">
                                @if (isset($supplier))
                                    {{ $supplier->nomor_telepon }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
