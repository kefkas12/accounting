@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
@include('layouts.headers.cards')
<style>
    .form-control:disabled,
    .form-control[readonly] {
        opacity: 1;
        background-color: #e9ecefc4;
    }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0 text-white">
                    Beli Barang
                </div>
                <form action="{{ url('transaksi_barang') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="ban" class="col-sm-4 col-form-label text-white">Barang</label>
                                    <div class="col-sm-8">
                                        <select name="barang" class="form-control" id="barang">
                                            @foreach($barang as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal" class="col-sm-4 col-form-label text-white">Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keterangan" class="col-sm-4 col-form-label text-white">Keterangan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="harga" class="col-sm-4 col-form-label text-white">Harga</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="harga" name="harga">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="jumlah" class="col-sm-4 col-form-label text-white">Jumlah</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="jumlah" name="jumlah">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-white">Total</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" id="total" name="total" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-4">
                                <div class="row justify-content-end mr-1">
                                    <button type="submit" class="btn btn-primary" id="input">Input</button>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-dark table-flush">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="sort" data-sort="name">No</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Harga</th>
                                                <th scope="col">Jumlah</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($transaksi_barang as $v)
                                            <tr id="{{ $v->id }}">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $v->nama_barang }}</td>
                                                <td>{{ $v->tanggal }}</td>
                                                <td>{{ $v->harga }}</td>
                                                <td>{{ $v->jumlah }}</td>
                                                <td>{{ $v->total }}</td>
                                                <td>{{ $v->keterangan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#jumlah').on('keyup keydown', function() {
        var jumlah = $(this).val();
        var harga = $('#harga').val();
        $('#total').val(jumlah * harga);
    });
    $('#harga').on('keyup keydown', function() {
        var harga = $(this).val();
        var jumlah = $('#jumlah').val();
        $('#total').val(jumlah * harga);
    });
    // $('#jumlah').on('keyup keydown', function() {
    //     var jumlah = $(this).val();
    //     var harga = $('#harga').val();
    //     $('#total_barang_pergi').val(jumlah * harga);
    //     if (jumlah < 1) {
    //         $('#input').hide();
    //         $('#kode_ban').empty();
    //     } else {
    //         $('#input').show();
    //         $('#kode_ban').empty();
    //         $('#kode_ban').append(`
    //         <div class="form-group row">
    //             <label for="kode_ban" class="col-sm-4 col-form-label text-white">Kode Ban </label>
    //             <div class="col-sm-8">
    //                 <input type="text" class="form-control" name="kode_ban[]">
    //             </div>
    //         </div>`)
    //         if (jumlah > 1) {
    //             for (var i = 1; i < jumlah; i++) {
    //                 $('#kode_ban').append(`
    //                 <div class="form-group row">
    //                     <label for="kode_ban" class="col-sm-4 col-form-label text-white"></label>
    //                     <div class="col-sm-8">
    //                         <input type="text" class="form-control" name="kode_ban[]">
    //                     </div>
    //                 </div>`)
    //             }
    //         }

    //     }
    // });
</script>
@endsection