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
                                <button class="btn btn-primary active" id="pills-service-tab" data-toggle="pill"
                                    data-target="#pills-service" type="button" role="tab" aria-controls="pills-service"
                                    aria-selected="true">Service</button>
                            </li>
                        </ul>
                        <hr class="bg-white">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-service" role="tabpanel"
                                aria-labelledby="pills-service-tab">
                                <form action="{{ url('export/service') }}" method="POST" class="text-white">
                                    @csrf
                                    <h4 class="text-white">Export Service</h4>
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
                                                            <input class="form-check-input" type="checkbox" name="tipe[]"
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
                                        href="{{ url('export/service') . '/' . $tipe . '/' . $_POST['dari'] . '/' . $_POST['sampai'] . '/' . $_POST['cabang'] }}">Export</a>
                                    <?php
                                    $tipe = explode(' ', rtrim($tipe));
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
                                                    <th scope="col">No Service Bill Manual</th>
                                                    <th scope="col">No Invoice</th>
                                                    <th scope="col">Service</th>
                                                    <th scope="col">Hourmeter</th>
                                                    <th scope="col">Jasa Service</th>
                                                    <th scope="col">Sparepart</th>
                                                    <th scope="col">Transport</th>
                                                    <th scope="col">Garansi</th>
                                                    <th scope="col">Tgl Request</th>
                                                    <th scope="col">Tgl Berangkat</th>
                                                    <th scope="col">Tgl Service</th>
                                                    <th scope="col">Tgl Serah Terima</th>
                                                    <th scope="col">Jam Mulai</th>
                                                    <th scope="col">Jam Selesai</th>
                                                    <th scope="col">Cabang</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Model Unit</th>
                                                    <th scope="col">No Seri Unit</th>
                                                    <th scope="col">No Warranty</th>

                                                    <th scope="col">No Job Request</th>

                                                    <th scope="col">Mekanik 1</th>
                                                    <th scope="col">Mekanik 2</th>
                                                    <th scope="col">Driver</th>
                                                    <th scope="col">No Tracking</th>
                                                    <th scope="col">Permasalahan</th>
                                                    <th scope="col">Penyebab</th>
                                                    <th scope="col">Tindakan Perbaikan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $v)
                                                    <tr>
                                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                                        <td>{{ $v->no_service_bill_manual }}</td>
                                                        <td>{{ $v->no_invoice }}</td>
                                                        <td>{{ $v->tipe }}</td>
                                                        <td>{{ $v->hourmeter }}</td>
                                                        <td>
                                                            @if ($v->jasa_service)
                                                                {{ number_format($v->jasa_service, 0, ',', '.') }}
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                        <td>{{ $v->sparepart }}</td>
                                                        <td>{{ $v->transport }}</td>
                                                        <td>{{ $v->garansi }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($v->tanggal_request)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($v->tanggal_berangkat)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($v->tanggal)) }}</td>
                                                        
                                                        <td>@if($v->tgl_serah_terima){{ date('d-m-Y', strtotime($v->tgl_serah_terima)) }}@else - @endif</td>
                                                        <td>{{ $v->jam_mulai }}</td>
                                                        <td>{{ $v->jam_selesai }}</td>
                                                        <td>{{ $v->cabang }}</td>
                                                        <td>{{ $v->nama_konsumen }}</td>
                                                        <td>{{ $v->model_unit }}</td>
                                                        <td>{{ $v->no_seri_unit }}</td>
                                                        <td>{{ $v->no_buku_warranty }}</td>

                                                        <td>{{ $v->no_job_request }}</td>

                                                        <td>{{ $v->nama_teknisi_1 }}</td>
                                                        <td>{{ $v->nama_teknisi_2 }}</td>
                                                        <td>{{ $v->nama_driver }}</td>
                                                        <td>{{ $v->tracking_warranty }}</td>
                                                        <td>{{ $v->permasalahan }}</td>
                                                        <td>{{ $v->penyebab }}</td>
                                                        <td>{{ $v->tindakan_perbaikan }}</td>
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
