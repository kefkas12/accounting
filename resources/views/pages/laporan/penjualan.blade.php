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
                                <strong><span style="font-size: 1.5rem;">Daftar penjualan</span></strong> (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <table class="table" id="laba_rugi">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        
        $( document ).ready(function() {
            const jsonData = {!! $laba_rugi !!}
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
                $('#laba_rugi').append(`
                    <tr class="bg-secondary" id="${key.replace(/ /g,"_")}">
                        <th colspan="3">${key_string}</th>
                    </tr>
                `)

                $.each(value, function(key_1, value_1) {
                    $('#'+key.replace(/ /g,"_")).parent().append(`
                        <tr id="${key_1.replace(/ /g,"_")}">
                            <th colspan="3">&nbsp;&nbsp;&nbsp;&nbsp; ${key_1}</th>
                        </tr>
                    `)
                    $.each(value_1, function(key_2, value_2) {
                        if(value_2.saldo != 0){
                            if(value_2.id_akun != ''){
                                var akun_nomor = "<a href='{{ url('akun/detail') }}/"+value_2.id_akun+"'>&nbsp;&nbsp;"+value_2.nomor+"</a>";
                                var akun_nama = "<a href='{{ url('akun/detail') }}/"+value_2.id_akun+"'>&nbsp;&nbsp;"+value_2.nama+"</a>";
                            }else{
                                var akun_nomor = "&nbsp;&nbsp;&nbsp;&nbsp;"+value_2.nomor;
                                var akun_nama = "&nbsp;&nbsp;&nbsp;&nbsp;"+value_2.nama;
                            }
                            $('#'+key_1.replace(/ /g,"_")).parent().append(`
                                <tr >
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${ akun_nomor }</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${ akun_nama }</td>
                                    <td class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${rupiah(value_2.saldo)}</td>
                                </tr>
                            `)
                            total += value_2.saldo;
                        }
                    });
                    $('#'+key_1.replace(/ /g,"_")).parent().append(`
                        <tr >
                            <th colspan="2">&nbsp;&nbsp;&nbsp;Total ${key_1}</th>
                            <th class="text-right">&nbsp;&nbsp;&nbsp;&nbsp; ${rupiah(total)}</th>
                        </tr>
                    `)
                    
                    subtotal += total;
                    total = 0;
                });
                if(key == 'Other income'){
                    key_string = 'Other income (expense)';
                }else{
                    key_string = key;
                }
                $('#'+key.replace(/ /g,"_")).parent().append(`
                    <tr >
                        <th colspan="2">&nbsp;&nbsp;&nbsp;Total dari ${key_string}</th>
                        <th class="text-right"> ${rupiah(subtotal)}</th>
                    </tr>
                `)
                grandtotal += subtotal

                if(key == 'Cost of sales'){
                    gross_profit = grandtotal;
                    $('#laba_rugi').append(`
                        <tr>
                            <th colspan="2">Gross profit</th>
                            <th class="text-right"> ${rupiah([gross_profit])}</th>
                        </tr>
                    `)
                    grandtotal = 0;
                }
                if(key == 'Operational expense'){
                    operating_profit = gross_profit - grandtotal;
                    $('#laba_rugi').append(`
                        <tr>
                            <th colspan="2">Operating profit</th>
                            <th class="text-right"> ${rupiah([operating_profit])}</th>
                        </tr>
                    `)
                    grandtotal = 0;
                }
                if(key == 'Other income'){
                    other_income = grandtotal;
                    grandtotal = 0;
                }
                subtotal = 0;
            });
            $('#laba_rugi').append(`
                <tr>
                    <th colspan="2">Profit (loss)</th>
                    <th class="text-right"> ${rupiah([operating_profit+other_income])}</th>
                </tr>
            `)
        });
    </script>
@endsection
