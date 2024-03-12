@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6 mb-5">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">
                        <div class="row mb-3">
                            <div class="col">
                                <strong><span style="font-size: 1.5rem;">Neraca</span></strong> (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <table class="table" id="neraca">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        
        $( document ).ready(function() {
            const jsonData = {!! $neraca !!}
            var total = 0;
            var subtotal = 0;

            $.each(jsonData, function(key, value) {
                $('#neraca').append(`
                    <tr class="bg-secondary" id="${key.replace(/ /g,"_")}">
                        <th colspan="3">${key}</th>
                    </tr>
                `)
                $.each(value, function(key_1, value_1) {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr id="${key_1.replace(/ /g,"_")}">
                            <th colspan="3">&nbsp; ${key_1}</th>
                        </tr>
                    `)
                    $.each(value_1, function(key_2, value_2) {
                        if(value_2.saldo != 0){
                            $('#'+key_1.replace(/ /g,"_")).parent().append(`
                                <tr >
                                    <td><a href="{{ url('akun/detail') }}/${value_2.id_akun}">&nbsp;&nbsp; ${value_2.nomor}</a></td>
                                    <td><a href="{{ url('akun/detail') }}/${value_2.id_akun}">&nbsp;&nbsp; ${value_2.nama}</a></td>
                                    <td class="text-right">&nbsp;&nbsp; ${rupiah(value_2.saldo)}</td>
                                </tr>
                            `)
                            total += value_2.saldo;
                        }
                        
                    });
                    $('#'+key_1.replace(/ /g,"_")).parent().append(`
                        <tr >
                            <th colspan="2">&nbsp;Total ${key_1}</th>
                            <th class="text-right">&nbsp;&nbsp; ${rupiah(total)}</th>
                        </tr>
                    `)
                    subtotal += total;
                    total = 0;
                });
                $('#'+key.replace(/ /g,"_")).parent().append(`
                    <tr >
                        <th colspan="2">Total ${key}</th>
                        <th class="text-right">&nbsp;&nbsp; ${rupiah(subtotal)}</th>
                    </tr>
                `)
                subtotal = 0;
            });
        });
    </script>
@endsection
