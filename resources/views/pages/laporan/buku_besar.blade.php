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
                                <strong><span style="font-size: 1.5rem;">Buku Besar</span></strong> (dalam IDR)
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <th>Nama Akun / Tanggal</th>
                            <th>Transaksi</th>
                            <th>No.</th>
                            <th>Deskripsi</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Saldo</th>
                        </thead>
                        <tbody id="buku_besar">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const jsonData = {!! $buku_besar !!};

            $.each(jsonData, function(key, value) {
                debit = 0;
                kredit = 0;
                $('#buku_besar').append(`
                    <tr class="header" style="cursor: pointer;">
                        <td colspan="7" class="bg-secondary">
                            (${value.nomor}) ${value.nama}</td>
                    </tr>
                `);
                $.each(value.detail, function(key2, value2) {
                    $('#buku_besar').append(`
                        <tr>
                            <td>${ value2.tanggal_transaksi }</td>
                            <td>${ value2.kategori }</td>
                            <td>${ value2.no }</td>
                            <td>${ value2.no_str }</td>
                            <td>${ rupiah(value2.debit) }</td>
                            <td>${ rupiah(value2.kredit) }</td>
                            <td>${ rupiah(value2.saldo) }</td>
                        </tr>
                    `);
                    debit += value2.debit
                    kredit += value2.kredit
                    saldo = value2.saldo
                });
                $('#buku_besar').append(`
                    <tr class="total">
                        <td colspan="4">(${value.nomor}) ${value.nama} | Saldo akhir</td>
                        <td>${ rupiah(debit) }</td>
                        <td>${ rupiah(kredit) }</td>
                        <td>${ rupiah(saldo) }</td>
                    </tr>
                `);
            });

            $('tr:not(.header)').hide();
            $('.total').show();

            $('tr.header').click(function() {
                console.log(1);
                $(this).find('span').text(function(_, value) {
                    return value == '-' ? '+' : '-'
                });

                $(this).nextUntil('tr.header').slideToggle(100, function() {});
            });
        });


    </script>
@endsection
