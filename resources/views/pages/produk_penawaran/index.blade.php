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
                        <div class="row">
                            <div class="col">
                                <b>Persediaan</b>
                            </div>
                            <div class="col d-flex justify-content-end">
                                @hasanyrole('Admin Marketing/sales')
                                @else
                                <a class="btn btn-primary" href="{{ url('produk_penawaran/insert') }}">Tambah produk penawaranx baru</a>
                                @endhasallroles
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="overflow: auto">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama produk</th>
                                        <th>barcode produk</th>
                                        <th>Kode produk</th>
                                        <th>Kategori produk</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk_penawaran as $v)
                                        <tr>
                                            <td><a href="{{ url('produk_penawaran/detail') . '/' . $v->id }}">{{ $v->nama }}</a></td>
                                            <td>
                                                {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($v->id, 'C39') }}" alt="" srcset=""> --}}
                                                <a class="btn btn-sm btn-primary" download="barcode_{{ $v->nama }}.png" href="data:image/png;base64,{{ DNS1D::getBarcodePNG($v->id, 'C39+',5,55) }}">Generate</a>
                                            </td>
                                            <td>{{ $v->kode }}</td>
                                            <td>{{ $v->kategori }}</td>
                                            <td>{{ $v->unit }}</td>
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable()
        });

        $.fn.dataTable.ext.type.order['formatted-num-pre'] = function(data) {
            var numeric = data.replace('.', '').replace(',', '.');
            return parseFloat(numeric);
        };
    </script>
@endsection
