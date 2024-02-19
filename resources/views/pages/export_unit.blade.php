@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="container-fluid mt--6">

        <div class="row">
            <div class="col-sm-12">
                <div class="card bg-default shadow">
                    <div class="card-body p-4">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item mr-2" role="presentation">
                                <button class="btn btn-primary active" id="pills-unit-tab" data-toggle="pill"
                                    data-target="#pills-unit" type="button" role="tab" aria-controls="pills-unit"
                                    aria-selected="true">Unit</button>
                            </li>
                        </ul>
                        <hr class="bg-white">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-service" role="tabpanel"
                                aria-labelledby="pills-service-tab">
                                <form action="{{ url('export/unit') }}" method="POST" class="text-white">
                                    @csrf
                                    <h4 class="text-white">Export Unit</h4>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Periode</label>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control" name="dari" id="dari" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control" name="sampai" id="sampai" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" name="cabang" id="cabang" required>
                                                <option value="all">All</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tipe</label>
                                        <div class="col-sm-10">
                                            <div class="form-row mt-2">
                                                @foreach ($filter as $v)
                                                    <div class="col-sm-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="model_unit[]"
                                                                value="{{ $v->nama }}"
                                                                id="{{ str_replace(' ', '_', strtolower($v->nama)) }}">
                                                            <label class="form-check-label"
                                                                for="{{ str_replace('_', ' ', strtolower($v->nama)) }}">
                                                                {{ $v->nama }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary mb-2" name="cari">Cari</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="data">
                                    <?php if(isset($_POST['cari'])){ ?>
                                    <a class="btn btn-primary mb-2"
                                        href="{{ url('export/unit') . '/' . $model_unit . '/' . $_POST['dari'] . '/' . $_POST['sampai'] . '/' . $_POST['cabang'] }}">Export</a>
                                    <?php
                                    $tipe = explode(' ', rtrim($model_unit));
                                    ?>
                                    <script>
                                        @foreach($tipe as $v)
                                            $("#{{ $v }}").prop("checked", true);
                                        @endforeach
                                    </script>
                                    <script>
                                        $(document).ready(function() {
                                            $('#dari').val('{{ $_POST['dari'] }}');
                                            $('#sampai').val('{{ $_POST['sampai'] }}');
                                            $('#cabang').val('{{ $_POST['cabang'] }}');
                                        });
                                    </script>
                                    <div class="table-responsive text-white">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Tgl Serah Terima</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Alamat</th>
                                                    <th scope="col">Cabang</th>
                                                    <th scope="col">No Seri Unit</th>
                                                    <th scope="col">No Engine</th>
                                                    <th scope="col">Model Unit</th>
                                                    <th scope="col">Tipe Service</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $v)
                                                    <tr>
                                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                                        <td>{{ date('d-m-Y', strtotime($v->tgl_serah_terima)) }}</td>
                                                        <td>{{ $v->nama_pemilik_terakhir_serah_terima }}</td>
                                                        <td>{{ $v->alamat }}</td>
                                                        <td>{{ $v->cabang }}</td>
                                                        <td>{{ $v->no_seri_unit }}</td>
                                                        <td>{{ $v->no_engine }}</td>
                                                        <td>{{ $v->model_unit }}</td>
                                                        <td>{{ $v->tipe_service }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @foreach($cabang as $v)
        $('#cabang').append(`
        <option>{{ $v->nama }}</option>
        `)
        @endforeach


    </script>
@endsection
