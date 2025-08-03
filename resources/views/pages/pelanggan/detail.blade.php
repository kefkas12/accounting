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
                                <a href="{{ url('pelanggan/edit').'/'.$pelanggan->id }}" class="btn btn-outline-primary">Ubah</a>
                                <a href="{{ url('pelanggan/hapus').'/'.$pelanggan->id }}" class="btn btn-outline-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="font-size: 12px;">
                        Informasi Umum
                        <div class="row my-4">
                            <div class="col-sm-2">Nama</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->nama }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Alamat</div>
                            <div class="col-sm-4">
                                @foreach($additional_alamat as $v)
                                    {{ $loop->index+1 }}. {{ $v->alamat }} <br>
                                @endforeach
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Nama Perusahaan</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->nama_perusahaan }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">NPWP</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->npwp }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Email</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->email }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Fax</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->fax }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-sm-2">Nomor Handphone</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->nomor_handphone }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="col-sm-2">Nomor Telepon</div>
                            <div class="col-sm-4">
                                @if (isset($pelanggan))
                                    {{ $pelanggan->nomor_telepon }}
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
