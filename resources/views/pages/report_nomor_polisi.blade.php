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
                    Report Nomor Polisi {{ $status }}
                </div>

                <div class="card-body">
                    <form action="{{ url('report/nomor_polisi').'/'.$status }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="mobil" class="col-sm-1 col-form-label text-white">Nomor Polisi</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="mobil" name="mobil" >
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @foreach($mobil as $v)
                                    <option value="{{ $v->id }}">{{ $v->nopol }} @if( $v->nama_pemilik )- {{ $v->nama_pemilik }} @endif</option>
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
                                            <th scope="col">Nopol</th>
                                            
                                            <th scope="col">Barang Muat</th>
                                            <th scope="col">Tonase</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Total Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach($report as $v)
                                        <tr>
                                            <td>{{ date('d-m-Y',strtotime($v->tanggal_barang)) }}</td>
                                            <td>{{ $v->nopol }}</td>
                                            
                                            <td>{{ $v->barang_muat }}</td>
                                            <td>{{ $v->tonase }}</td>
                                            <td>{{ $v->tujuan }}</td>
                                            <td>Rp {{ $v->tonase * $v->harga ? number_format($v->tonase * $v->harga,0,',','.') : 0 }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="text-right">Keuntungan : </td>
                                            <td>Rp {{ number_format($keuntungan,0,',','.') }}</td>
                                        </tr>
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
<script>
    $(document).ready(function() {
        $('#mobil').select2();
        $('#supir').select2();
    });
</script>
@endsection