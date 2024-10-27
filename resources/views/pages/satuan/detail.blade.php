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
                            <div class="col-sm-6">Detail satuan</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <strong>
                                @if (isset($satuan))
                                    {{ $satuan->nama }}
                                @else
                                    -
                                @endif
                                </strong>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('satuan/edit').'/'.$satuan->id }}" class="btn btn-outline-primary">Ubah</a>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4"><i class="fa fa-store text-primary mr-4"></i><strong>Info satuan</strong></div>
                            <div class="col-sm-4">
                                
                            </div>  
                        </div>
                        <div class="row my-3">
                            <div class="col-sm-4">Nama satuan</div>
                            <div class="col-sm-4">
                                @if (isset($satuan) && $satuan->nama != null)
                                    {{ $satuan->nama }}
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
