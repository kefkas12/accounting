<html>

<head>
    <meta charset='utf-8'>
    <meta content='IE=edge' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1.0,user-scalable=no' name='viewport'>
    <title>
        Surat Jalan
    </title>
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" media="all" href="https://d2lud967a5orp2.cloudfront.net/assets/prints-20337af3b4793164edb266685154eb22.css" data-turbolinks-track="true" />
</head>

<body>
    <!-- A4 Paper size: 210mm x 297mm = 793.7px x 1122.5px -->
    <div class='print-template'>
        <div class='delivery-slip'>
            <div class='a4'>
                <div class='transaction-customer-info clear'>
                    <div class='transaction-info'>
                        <div class='row' style='margin-bottom: 5px; width: 95%;'>
                        <div class="col-sm-12">
                            
                        </div>
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
    </div>
    <script>
        // window.print()
    </script>
</body>

</html>
