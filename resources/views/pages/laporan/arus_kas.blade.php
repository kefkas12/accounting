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
                                <strong><span style="font-size: 1.5rem;">Laporan Arus Kas</span></strong> (dalam IDR)
                            </div>
                        </div>
                        <form method="POST" action="{{ url('laporan/neraca') }}">
                            @csrf
                            <div class="row">
                                <div class="col-2">
                                    <label for="periode_dari">Periode dari</label>
                                </div>
                                <div class="col-2">
                                    <label for="periode_sampai">Periode sampai</label>
                                </div>
                                <div class="col-2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-2">
                                    <input type="date" class="form-control" name="periode_dari" @if(isset($_POST['periode_dari'])) value="{{ $_POST['periode_dari'] }}" @endif>
                                </div>
                                <div class="form-group col-2">
                                    <input type="date" class="form-control" name="periode_sampai" @if(isset($_POST['periode_sampai'])) value="{{ $_POST['periode_sampai'] }}" @endif>
                                </div>
                                <div class="form-group col-2">
                                    <input type="submit" class="btn btn-primary" value="Filter">
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table" id="arus_kas">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        
        $( document ).ready(function() {
            const jsonData = {!! $arus_kas !!};
            var total = 0;
            var subtotal = 0;
            var grandtotal = 0;
            var key_string = '';

            $.each(jsonData, function(key, value) {
                if(key == 'Other income'){
                    key_string = 'Other income (expense)';
                }else{
                    key_string = key;
                }
                $('#arus_kas').append(`
                    <tr class="bg-secondary" id="${key.replace(/ /g,"_")}">
                        <th colspan="2">${key_string}</th>
                    </tr>
                `)

                $.each(value, function(key_1, value_1) {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr id="${key_1.replace(/ /g,"_")}">
                            <th colspan="2">&nbsp;&nbsp;&nbsp;&nbsp; ${key_1}</th>
                            <td class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${rupiah(value_1)}</td>
                        </tr>
                    `)
                    subtotal += parseFloat(value_1) || 0;
                });
                if(key_string == 'Arus kas dari aktivitas operasional') {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr >
                            <th colspan="2">&nbsp;&nbsp;&nbsp;Kas bersih yang diperoleh dari aktivitas operasional</th>
                            <th class="text-right"> ${rupiah(subtotal)}</th>
                        </tr>
                    `);
                }else if(key_string == 'Arus kas dari aktivitas investasi') {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr >
                            <th colspan="2">&nbsp;&nbsp;&nbsp;Kas bersih yang diperoleh dari aktivitas investasi</th>
                            <th class="text-right"> ${rupiah(subtotal)}</th>
                        </tr>
                    `);
                }else if(key_string == 'Arus kas dari aktivitas pendanaan') {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr >
                            <th colspan="2">&nbsp;&nbsp;&nbsp;Kas bersih yang diperoleh dari aktivitas pendanaan</th>
                            <th class="text-right"> ${rupiah(subtotal)}</th>
                        </tr>
                    `);
                }
                grandtotal += subtotal;
                
                if(key == 'Revenue') {
                    revenue = subtotal;
                }

                if(key == 'Cost of sales'){
                    cost_of_sales = subtotal;
                    $('#arus_kas').append(`
                        <tr>
                            <th colspan="2">Gross profit</th>
                            <th class="text-right"> ${rupiah([revenue-cost_of_sales])}</th>
                        </tr>
                    `)
                }
                if(key == 'Operational expense'){
                    $('#arus_kas').append(`
                        <tr>
                            <th colspan="2">Operating profit</th>
                            <th class="text-right"> ${rupiah([0])}</th>
                        </tr>
                    `)
                }
                subtotal = 0;
            });
            $('#arus_kas').append(`
                <tr>
                    <th colspan="2"></th>
                    <th class="text-right"></th>
                </tr>
                <tr>
                    <th colspan="2">Kenaikan (penurunan) kas</th>
                    <th class="text-right"> ${rupiah([grandtotal])}</th>
                </tr>
                <tr>
                    <th colspan="2">Total revaluasi bank</th>
                    <th class="text-right"> ${rupiah([0])}</th>
                </tr>
                <tr>
                    <th colspan="2">Saldo kas awal</th>
                    <th class="text-right"> ${rupiah([0])}</th>
                </tr>
                <tr>
                    <th colspan="2">Saldo kas akhir</th>
                    <th class="text-right"> ${rupiah([grandtotal])}</th>
                </tr>
            `)
        });
    </script>
@endsection
