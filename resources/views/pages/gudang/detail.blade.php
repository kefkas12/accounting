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
                            <div class="col-sm-6">Detail gudang</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <strong>
                                @if (isset($gudang))
                                    {{ $gudang->nama }}
                                @else
                                    -
                                @endif
                                </strong>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('gudang/edit').'/'.$gudang->id }}" class="btn btn-outline-primary">Ubah</a>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4"><i class="fa fa-store text-primary mr-4"></i><strong>Info gudang</strong></div>
                            <div class="col-sm-4">
                                
                            </div>  
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Kode gudang</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->kode != null)
                                    {{ $gudang->kode }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Alamat</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->alamat != null)
                                    {{ $gudang->alamat }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Keterangan</div>
                            <div class="col-sm-4">
                                @if (isset($gudang) && $gudang->keterangan != null)
                                    {{ $gudang->keterangan }}
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
