<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV Gemilang Jaya Transport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        @media print {
            @page {
                margin-top: 0;
                margin-bottom: 0;
                size: landscape;
            }

            body {
                padding-top: 72px;
                padding-bottom: 72px;
            }
        }
    </style>
</head>

<body>
    <p class="text-center">
        <img src="{{ asset('/image/kop.png') }}">
    </p>
    <h1 class="text-center">Kwitansi</h1>

    <table class="table table-borderless mb-5">
        <tbody>
            <div class="row">
                <div class="col-sm-3">Telah diterima dari</div>
                <div class="col-sm-5">: &nbsp; {{ $invoice->nama_customer }}</div>
            </div>

            <div class="row">
                <div class="col-sm-3">Uang sejumlah</div>
                <div class="col-sm-5">: &nbsp; {{ $uang_sejumlah }}</div>
            </div>

            <div class="row">
                <div class="col-sm-3">Perihal</div>
                <div class="col-sm-5">: &nbsp; Tagihan</div>
            </div>

            <div class="row">
                <div class="col-sm-3">Jenis Barang</div>
                <div class="col-sm-5">: &nbsp; @foreach($detail_invoice as $v) @if($loop->index+1 != 1) &nbsp; &nbsp; @endif{{ $loop->index+1 }}. {{ $v->barang_muat }} <br> @endforeach</div>
            </div>
            <div class="row">
                <div class="col-sm-3">Tanggal Muat</div>
                <div class="col-sm-5">: &nbsp; @foreach($detail_invoice as $v) @if($loop->index+1 == 1) {{ date('d-m-Y',strtotime($v->tanggal)) }} @endif @endforeach</div>
            </div>
            <div class="row">
                <div class="col-sm-3">Tanggal Bongkar</div>
                <div class="col-sm-5">: &nbsp; @if( $count == 1) @foreach($detail_invoice as $v) @if($loop->index+1 == 1) {{ date('d-m-Y',strtotime($v->tanggal_bongkar)) }} @endif @endforeach @else - @endif</div>
            </div>
        </tbody>
    </table>
    <div class="row mt-5">
        <div class="col-sm-9">Terbilang Rp. <u>{{ $total->total }}</u></div>
        <div class="col-sm-3">{{ date('d F Y') }}</div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script>
        window.print();
    </script>
</body>

</html>