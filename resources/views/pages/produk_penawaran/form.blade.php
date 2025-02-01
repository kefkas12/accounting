@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form action="{{ url('produk_penawaran/insert') }}" method ="POST" id="form">
                    @csrf
                    <div class="card">
                        <div class="card-body ">
                            <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Buat Produk Penawaran Baru</h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="form-group has-float-label mb-2">
                                        <span>Nama Produk Penawaran <span class="text-red">*</span></span>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($produk_penawaran) ? $produk_penawaran->nama : '' }}" required>
                                    </label>
                                    <label class="form-group has-float-label mb-2">
                                        <span>Deskripsi</span>
                                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="4">{{ isset($produk_penawaran) ? $produk_penawaran->deskripsi : '' }}</textarea>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-group has-float-label mb-2">
                                        <span>Kode produk / SKU</span>
                                        <input type="number" class="form-control" id="kode" name="kode"  value="{{ isset($produk_penawaran) ? $produk_penawaran->kode : '' }}">
                                    </label>
                                    <label class="form-group has-float-label mb-2">
                                        <span>Kategori</span>
                                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ isset($produk_penawaran) ? $produk_penawaran->kategori : '' }}">
                                    </label>
                                    <label class="form-group has-float-label mb-2">
                                        <span>Unit</span>
                                        <select class="form-control" name="satuan" id="satuan" value="{{ isset($produk_penawaran) ? $produk_penawaran->unit : '' }}">
                                            <option value="buah" selected>Buah</option>
                                            @foreach($satuan as $v)
                                            <option>{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    
                                </div>
                                <div class="col-sm-12">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('produk_penawaran') }}" class="btn btn-light">Batalkan</a>
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
    
    <script>
        @if(isset($produk_penawaran))
            $('#form').attr('action','{{ url("produk_penawaran/edit")."/".$produk_penawaran->id }}')
        @endif
    </script>
@endsection
