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
                    Report Customer
                </div>

                <div class="card-body">
                    <form action="{{ url('report/customer') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="customer" class="col-sm-1 col-form-label text-white">Customer</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="customer" name="customer">
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @foreach($customer as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
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
                    <form action="{{ url('tagih') }}" method="POST">
                        @csrf
                        <input type="number" style="display:none;" value="{{ $id_customer }}" name="id_customer" >
                        <button type="submit" class="btn btn-primary" id="tagih" style="display:none;">Tagih</button>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-dark table-flush">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Nama Customer</th>
                                                <th scope="col">Nopol</th>
                                                <th scope="col">Barang</th>
                                                <th scope="col">Tonase</th>
                                                <th scope="col">Tujuan</th>
                                                <th scope="col">Total</th>
                                                @if($check == true)
                                                <th scope="col" onclick="checkall()"><input type="checkbox" id="checkall" ></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php $total = 0; ?>
                                            @foreach($report as $v)
                                            <tr>
                                                <td>{{ date('d/m/Y',strtotime($v->tanggal)) }}</td>
                                                <td>{{ $v->nama_customer }}</td>

                                                <td>{{ $v->nopol }}</td>
                                                <td>{{ $v->barang_muat }}</td>
                                                <td>{{ $v->tonase }}</td>
                                                <td>{{ $v->tujuan }}</td>
                                                <td>Rp {{ $v->tonase * $v->harga ? number_format($v->tonase * $v->harga,0,',','.') : 0 }}</td>
                                                @if($check == true)
                                                <td>@if($v->status_tagihan == null)<input type="checkbox" id="checkbox_{{ $v->id }}" class="checkbox" name="tagih[]" value="{{ $v->id }}" onclick="check()">@else {{ $v->status_tagihan }}@endif</td>
                                                @endif
                                            </tr>
                                            <?php $total += $v->tonase * $v->harga; ?>
                                            @endforeach
                                            <tr>
                                                <td colspan="6" class="text-right">Total : </td>
                                                <td>Rp {{ number_format($total,0,',','.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    $(document).ready(function() {
        $('#mobil').select2();
        $('#supir').select2();
    });

    function checkall() {
        $('.checkbox').prop('checked', $('#checkall').is(':checked') ? true : false);
        check();
    }

    function check() {
        
        if ($("input:checkbox:checked").length > 0) {
            $('#tagih').show();
        } else {
            $('#tagih').hide();
        }
    }
</script>
@endsection