<html>

<head>
    <meta charset='utf-8'>
    <meta content='IE=edge' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1.0,user-scalable=no' name='viewport'>
    <title>
        Surat Jalan
    </title>
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ asset('assets/img/brand/metalco.jpg') }}" alt="" style="width: 300px;">
            </div>
            <div class="col-sm-3">
                <b style="font-size: 30px;">
                    Surat Jalan
                </b>
            </div>
            <div class="col-sm-4">
                <div class="d-flex flex-row-reverse">
                    <b>{{ date('d-M-y') }}</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Grand Sungkono Lagoon Tower Caspian 3515
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
                <div class="d-flex flex-row-reverse">
                    Kepada Yth :
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                Jl. KH Abdul Wahab Siamin Kav 9-10, Surabaya
            </div>
            <div class="col-sm-5">
            </div>
            <div class="col-sm-3">
                <div class="d-flex flex-row-reverse">
                    <b>{{ $penjualan->nama_pelanggan }}</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                031-33300038 / 0821 1791 2229
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
                <div class="d-flex flex-row-reverse text-right">
                    {{ $penjualan->alamat }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                
            </div>
            <div class="col-sm-5">
            </div>
            <div class="col-sm-3">
                <div class="d-flex flex-row-reverse text-right">
                    No SJ : {{ $penjualan->no_str }}
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12">
            <table class="table  table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Material Name</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan->detail_penjualan as $v)
                    <tr>
                        <td>
                            {{ $loop->index+1 }}
                        </td>
                        <td>
                            {{ $v->produk->nama }}
                        </td>
                        <td>
                            {{ $v->kuantitas }} Buah
                        </td>
                        <td>
                            {{ $v->deskripsi }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>PO No. : {{ $penjualan->no_str_pemesanan }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            PENERIMA :
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            PENGIRIM :
        </div>
    </div>
    </div>
    



    <!-- A4 Paper size: 210mm x 297mm = 793.7px x 1122.5px -->
    <!-- <div class='print-template'>
        <div class='delivery-slip'>
            <div class='a4'>
                <div class='transaction-customer-info clear'>
                    <div class='transaction-info'>
                        <div class='row' style='margin-bottom: 5px; width: 95%;'>
                            <div class='invoice-name'>
                                SURAT JALAN
                            </div>
                            <div class='company-name' style='border-bottom: 1px solid; padding-top: 3px;'>
                                {{ strtoupper($company->nama_perusahaan) }}
                            </div>
                        </div>
                        <div class='address-invoice-info'>
                            <div class='address-info'>
                                <div class='row'></div>
                            </div>
                            <div class='invoice-info'>
                                <div class='row'>
                                    <div class='data-label' style='font-weight: bold'>
                                        NO.
                                    </div>
                                    <div class='data-content' style='font-weight: bold'>
                                        {{ $penjualan->no }}
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='data-label'>
                                        Tanggal
                                    </div>
                                    <div class='data-content'>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='customer-info' style='padding-top: 12px;'>
                        <div class='row'>
                            KEPADA YTH.
                        </div>
                        <div class='row'>

                        </div>
                        <div class='row'>
                            {{ $penjualan->nama_pelanggan }}
                        </div>
                        <div class='row'>

                        </div>
                        <div class='row' style='margin-top: 5px'>
                            Telp.
                        </div>
                    </div>
                </div>
                <div class='row' style='margin: 20px 0 5px 0'>
                    Kami kirimkan barang-barang tersebut dibawah ini dengan kendaraan
                    ........................................................ No.
                    .................................................
                </div>
                <div class='data'>
                    <table>
                        <thead>
                            <tr>
                                <th class='text-right'>
                                    Qty
                                </th>
                                <th>
                                    Kemasan
                                </th>
                                <th style='width: 65%'>
                                    Nama Barang
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan->detail_penjualan as $v)
                                <tr>
                                    <td class='text-right'>
                                        {{ $v->kuantitas }}
                                    </td>
                                    <td>
                                        Buah
                                    </td>
                                    <td style='width: 65%'>
                                        {{ $v->produk->nama }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class='signature-message row'>
                    <div class='signature'>
                        <div class='row'>
                            Penerima,
                        </div>
                        <div class='row'>
                            Tanda Tangan / Cap
                        </div>
                        <div class='row' style='height: 75px'>

                        </div>
                        <div class='row'>
                            (...............................................)
                        </div>
                    </div>
                    <div class='message' style='border: 0px'></div>
                    <div class='signature'>
                        <div class='row'>
                            Hormat kami,
                        </div>
                        <div class='row' style='height: 95px'>

                        </div>
                        <div class='row'>
                            (...............................................)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <script>
        // window.print()
    </script>
</body>

</html>