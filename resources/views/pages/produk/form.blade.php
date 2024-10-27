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
                        <form action="{{ url('produk/insert') }}" method ="POST" id="form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <label for="nama">Nama Produk <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ isset($produk) ? $produk->nama : '' }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode">Kode produk / SKU</label>
                                        <input type="number" class="form-control" id="kode" name="kode"  value="{{ isset($produk) ? $produk->kode : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nomor_telepon">Unit</label>
                                        <select class="form-control" name="satuan" id="satuan" value="{{ isset($produk) ? $produk->unit : '' }}">
                                            <option value="buah" selected>Buah</option>
                                            @foreach($satuan as $v)
                                            <option>{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ isset($produk) ? $produk->kategori : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" id="deskripsi">{{ isset($produk) ? $produk->deskripsi : '' }}</textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-8">
                                            <label for="harga_beli">Harga Beli Satuan</label>
                                            <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ isset($produk) ? $produk->harga_beli : '' }}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="harga_jual">Harga Jual Satuan</label>
                                            <input type="text" class="form-control" id="harga_jual" name="harga_jual" value="{{ isset($produk) ? $produk->harga_jual : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="batas_minimum" id="batas_minimum" onclick="monitor_persediaan_barang()">
                                        <label class="form-check-label" for="batas_minimum">
                                            Monitor persediaan barang
                                        </label>
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        @if(isset($produk))
        $('#form').attr('action','{{ url("produk/edit")."/".$produk->id }}')
        @if($produk->batas_stok_minimum)
        $('#batas_minimum').prop("checked", true).trigger('onclick');
        $('#batas_stok_minimum').val({{$produk->batas_stok_minimum}})
        @endif
        @endif

        $( document ).ready(function() {
            $('#batas_minimum').prop('checked', true).on("change", function() {
                $('#monitor_persediaan_barang').show();
                $('#batas_stok_minimum').prop('required', true);
            }).trigger("change");
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
