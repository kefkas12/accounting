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
                    <table class="table" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th>Nama Akun / Tanggal</th>
                                <th>Transaksi</th>
                                <th>No.</th>
                                <th>Deskripsi</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
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
                    <tr class="header font-weight-bold" style="cursor: pointer;">
                        <td colspan="7" class="bg-secondary">
                            (${value.nomor}) ${value.nama}</td>
                    </tr>
                `);
                $.each(value.detail, function(key2, value2) {
                    $('#buku_besar').append(`
                        <tr class="text-center">
                            <td style="padding: 0 !important;">${ value2.tanggal_transaksi }</td>
                            <td style="padding: 0 !important;">${ value2.kategori }</td>
                            <td style="padding: 0 !important;">${ value2.no }</td>
                            <td style="padding: 0 !important;">${ value2.no_str }</td>
                            <td style="padding: 0 !important;">${ rupiah(value2.debit) }</td>
                            <td style="padding: 0 !important;">${ rupiah(value2.kredit) }</td>
                            <td style="padding: 0 !important;">${ rupiah(value2.saldo) }</td>
                        </tr>
                    `);
                    debit += value2.debit
                    kredit += value2.kredit
                    saldo = value2.saldo
                });
                $('#buku_besar').append(`
                    <tr class="total text-right font-weight-bold">
                        <td colspan="4">(${value.nomor}) ${value.nama} | Saldo akhir</td>
                        <td>${ rupiah(debit) }</td>
                        <td>${ rupiah(kredit) }</td>
                        <td>${ rupiah(saldo) }</td>
                    </tr>
                `);
            });

            $('tbody>tr:not(.header)').hide();
            $('.total').show();

            $('tbody>tr.header').click(function() {
                $(this).find('span').text(function(_, value) {
                    return value == '-' ? '+' : '-'
                });

                $(this).nextUntil('tr.total').slideToggle(100, function() {});
            });
        });


    </script>
@endsection
