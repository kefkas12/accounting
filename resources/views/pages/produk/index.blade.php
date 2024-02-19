@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">
                        <div class="row mb-3">
                            <div class="col">
                                <a href="{{ url('produk/insert') }}"class="btn btn-primary">
                                    Tambah Produk
                                </a>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col">
                                <div class="card border-success">
                                    <div class="card-header border-success">
                                        Stok Tersedia
                                    </div>
                                    <div class="card-body">
                                        <p>Total Produk</p>
                                        <b>0</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-warning">
                                    <div class="card-header border-warning">
                                        Stok segera habis
                                    </div>
                                    <div class="card-body">
                                        <p>Total Produk</p>
                                        <b>0</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card border-danger">
                                    <div class="card-header border-danger">
                                        Stok habis
                                    </div>
                                    <div class="card-body">
                                        <p>Total Produk</p>
                                        <b>0</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Kategori produk</th>
                                        <th>Total Stok</th>
                                        <th>Unit</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $v)
                                        <tr>
                                            <td><a href="{{ url('produk/detail') . '/' . $v->id }}">{{ $v->nama }}</a>
                                            </td>
                                            <td>{{ $v->kode }}</td>
                                            <td>{{ $v->kategori }}</td>
                                            <td>{{ $v->stok }}</td>
                                            <td>{{ $v->unit }}</td>
                                            <td>@if($v->harga_beli) {{ number_format($v->harga_beli, 2, ',', '.') }} @else 0,00 @endif</td>
                                            <td>@if($v->harga_jual) {{ number_format($v->harga_jual, 2, ',', '.') }} @else 0,00 @endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Supir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/supir_input') }}" id="form">
                        @csrf
                        <div class="form-group">
                            <label for="nama" class="col-form-label">Nama Supir</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_rekening" class="col-form-label">Nama Rekening Supir</label>
                            <input type="text" class="form-control" name="nama_rekening" id="nama_rekening" required>
                        </div>
                        <div class="form-group">
                            <label for="no_rekening" class="col-form-label">No Rekening Supir</label>
                            <input type="number" class="form-control" name="no_rekening" id="no_rekening" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="col-form-label">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // new DataTable('#example');
            $('#example').DataTable({
                columnDefs: [{
                    type: 'formatted-num',
                    targets: [5, 6]
                }],
                language: {
                    thousands: ".",
                    decimal: ","
                }
            })
        });

        $.fn.dataTable.ext.type.order['formatted-num-pre'] = function(data) {
            var numeric = data.replace('.', '').replace(',', '.');
            return parseFloat(numeric);
        };
    </script>
@endsection
