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
                        Tambah gudang baru
                    </div>
                    <div class="card-body ">
                        <form action="{{ url('gudang/insert') }}" method ="POST" id="form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-11">
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="nama">Nama gudang <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($gudang) ? $gudang->nama : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="kode">Kode gudang</label>
                                            <input type="text" class="form-control" id="kode" name="kode"  value="{{ isset($gudang) ? $gudang->kode : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="alamat">Alamat</label>
                                            <textarea class="form-control" id="alamat" name="alamat" value="{{ isset($gudang) ? $gudang->alamat : '' }}"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan" value="{{ isset($gudang) ? $gudang->keterangan : '' }}"></textarea>
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
    @if(isset($gudang))
    <script>
        $('#form').attr('action','{{ url("gudang/edit")."/".$gudang->id }}')
    </script>
    @endif
@endsection
