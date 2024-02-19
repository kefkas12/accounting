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
                    Report Partner
                </div>

                <div class="card-body">
                    <form action="{{ url('report/partner') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="partner" class="col-sm-1 col-form-label text-white">Partner</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="partner" name="partner">
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @foreach($partner as $v)
                                    <option>{{ $v->nama }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <label for="mobil" class="col-sm-1 col-form-label text-white">Dari</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="dari">
                            </div>
                            <label for="mobil" class="col-sm-1 col-form-label text-white">Sampai</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="sampai">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary" name="cari">Cari</button>
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Nama Partner</th>
                                            <th scope="col">Nopol</th>
                                            <th scope="col">Barang</th>
                                            <th scope="col">Tonase</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $total = 0; ?>
                                        @foreach($report as $v)
                                        <tr>
                                            <td>{{ date('d/m/Y',strtotime($v->tanggal)) }}</td>
                                            <td>{{ $v->nama_pemilik }}</td>

                                            <td>{{ $v->nopol }}</td>
                                            <td>{{ $v->barang_muat }}</td>
                                            <td>{{ $v->tonase }}</td>
                                            <td>Rp {{ number_format($v->total,'0',',','.') }}</td>
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
    </div>
</div>
@endsection