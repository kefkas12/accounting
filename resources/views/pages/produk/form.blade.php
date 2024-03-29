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
                        Buat Produk Baru
                    </div>
                    <div class="card-body ">
                        <form action="{{ url('produk/insert') }}" method ="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Produk</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($produk) ? $produk->nama : '' }}">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="kode">Kode produk / SKU</label>
                                            <input type="number" class="form-control" id="kode" name="kode"  value="{{ isset($produk) ? $produk->kode : '' }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="nomor_telepon">Unit</label>
                                            <select class="form-control" name="unit" id="unit" value="{{ isset($produk) ? $produk->unit : '' }}">
                                                <option value="buah" selected>Buah</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ isset($produk) ? $produk->kategori : '' }}">
                                    </div>
                                   
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="harga_beli">Harga Beli Satuan</label>
                                            <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ isset($produk) ? $produk->harga_beli : '' }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="harga_jual">Harga Jual Satuan</label>
                                            <input type="text" class="form-control" id="harga_jual" name="harga_jual" value="{{ isset($produk) ? $produk->harga_jual : '' }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ url('produk') }}" class="btn btn-light">Batalkan</a>
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
@endsection
