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
                        Tambah satuan baru
                    </div>
                    <div class="card-body ">
                        <form action="{{ url('satuan/insert') }}" method ="POST" id="form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-11">
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="nama">Nama satuan <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($satuan) ? $satuan->nama : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ url('produk') }}" class="btn btn-light">Batalkan</a>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($satuan))
    <script>
        $('#form').attr('action','{{ url("satuan/edit")."/".$satuan->id }}')
    </script>
    @endif
@endsection
