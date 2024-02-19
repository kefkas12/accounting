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
            }

            body {
                padding-top: 72px;
                padding-bottom: 72px;
            }
        }
    </style>
</head>

<body>
    <p>
        CV Gemilang Jaya Transport <br>
        Palembang - Sumatera Selatan
    </p>
    <hr>
    <h1 class="text-center">Invoice</h1>
    <hr>
    <div class="d-flex mb-3">
        <div class="me-auto p-2">
            <p>
                Kepada Yth, <br>
                {{ $invoice->nama_customer }}
            </p>
        </div>
        <div class="p-2">
            <p>
                No Invoice : {{ $invoice->no }}<br>
                Tanggal Invoice : {{ $invoice->tanggal }}
            </p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Nopol</th>
                <th scope="col">Jenis</th>
                <th scope="col">Tujuan</th>
                <th scope="col">Barang Muat</th>
                <th scope="col">Tonase</th>
                <th scope="col">Harga</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            ?>
            @foreach($detail_invoice as $v)
            <tr>
                <th scope="row">{{ $loop->index+1 }}</th>
                <td>{{ date('d/m/Y', strtotime($v->tanggal)) }}</td>
                <td>{{ $v->nopol }}</td>
                <td>{{ $v->jenis }}</td>
                <td>{{ $v->tujuan }}</td>
                <td>{{ $v->barang_muat }}</td>
                <td>{{ number_format($v->tonase,'0',',','.') }}</td>
                <td>Rp {{ number_format($v->harga,'0',',','.') }}</td>
                <td>Rp {{ number_format($v->total,'0',',','.') }}</td>
            </tr>

            <?php
            $total += $v->total;
            ?>
            @endforeach
            <tr>
                <td colspan="8" class="text-right"><b>Total Invoice</b></td>
                <td>Rp {{ number_format($total,'0',',','.') }}</td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><b>Sisa</b></td>
                <td>Rp {{ number_format($invoice->sisa,'0',',','.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex mb-3">
        <div class="me-auto p-2">
            <p>
                Cara Pembayaran, <br>
                Transfer ke Rekening Bank BCA <br>
                No Rekening : 849-0470-256 <br>
                a.n CV Gemilang Jaya Transport <br>
            </p>
        </div>
        <div class="p-2">
            <p>
                Hormat Kami,<br>
                <br>
                <br>
                CV Gemilang Jaya Transport<br>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script>
        window.print();
    </script>
</body>

</html>