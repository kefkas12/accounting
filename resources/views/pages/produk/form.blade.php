@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <form action="{{ url('produk/insert') }}" method ="POST" id="form">
                    @csrf
                    <div class="card">
                        <div class="card-body ">
                            <h2 class="text-primary mb-3 pb-3" style="border-bottom: 1px solid rgb(199, 206, 215);">Buat Produk Baru</h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row mb-2">
                                        <label for="nama" class="col-sm-3 col-form-label">Nama Produk <span class="text-red">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($produk) ? $produk->nama : '' }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="kode" class="col-sm-3 col-form-label">Kode produk / SKU</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="kode" name="kode"  value="{{ isset($produk) ? $produk->kode : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="nomor_telepon" class="col-sm-3 col-form-label">Unit</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="satuan" id="satuan" value="{{ isset($produk) ? $produk->unit : '' }}">
                                                <option value="buah" selected>Buah</option>
                                                @foreach($satuan as $v)
                                                <option>{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="kategori" class="col-sm-3 col-form-label">Kategori</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="kategori" name="kategori" value="{{ isset($produk) ? $produk->kategori : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="deskripsi" id="deskripsi">{{ isset($produk) ? $produk->deskripsi : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="harga_beli" class="col-sm-3 col-form-label">Harga Beli Satuan</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ isset($produk) ? $produk->harga_beli : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="harga_jual" class="col-sm-3 col-form-label">Harga Jual Satuan</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ isset($produk) ? $produk->harga_jual : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="batas_minimum" class="col-sm-3 col-form-label">Monitor persediaan barang</label>
                                        <div class="col-sm-9">
                                            <input type="checkbox" class="mt-4" style="position: absolute;margin-top: .3rem;" name="batas_minimum" id="batas_minimum">
                                        </div>
                                      </div>
                                    <div class="form-group" id="monitor_persediaan_barang" style="display:none;">
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Batas stok minimum</th>
                                                    <th>Akun persediaan barang default</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" placeholder="0" name="batas_stok_minimum" id="batas_stok_minimum">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Buah</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td>
                                                        (1-10200) - Persediaan Barang
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    
    <script>
        @if(isset($produk))
            $('#form').attr('action','{{ url("produk/edit")."/".$produk->id }}')
            @if(isset($produk->batas_stok_minimum))
            $('#batas_minimum').prop("checked", true).trigger('onclick');
            $('#batas_stok_minimum').val({{$produk->batas_stok_minimum}})
            @endif
        @endif

        $( document ).ready(function() {
            $('#batas_minimum').prop('checked', true).trigger("change");
        });

        $('#batas_minimum').on("change", function() {
            monitor_persediaan_barang();
            $('#batas_stok_minimum').prop('required', true);
        });
        
        function monitor_persediaan_barang(){
            if($('#batas_minimum').is(":checked")){
                $('#monitor_persediaan_barang').show();
            }else{
                $('#monitor_persediaan_barang').hide();
            }
        }
    </script>
@endsection
