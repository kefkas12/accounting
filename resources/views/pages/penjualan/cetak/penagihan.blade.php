<html>

<head>
    <meta charset='utf-8'>
    <meta content='IE=edge' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1.0,user-scalable=no' name='viewport'>
    <title>
        JURNAL
    </title>
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <link rel="stylesheet" media="all"
        href="https://d2lud967a5orp2.cloudfront.net/assets/prints-20337af3b4793164edb266685154eb22.css"
        data-turbolinks-track="true" />
</head>

<body>
    <!-- A4 Paper size: 210mm x 297mm = 793.7px x 1122.5px -->
    <div class='print-template'>
        <div class='invoice-nine'>
            <div class='a4'>
                <div class='transaction-customer-info clear'>
                    <div class='transaction-info'>
                        <div class='row' style='border-bottom: 1px solid; margin-bottom: 5px; width: 95%;'>
                            <div class='company-name' style='padding-top: 10px;'>
                                {{ strtoupper($company->nama_perusahaan) }}
                            </div>
                            <div class='invoice-name'>
                                FAKTUR
                            </div>
                        </div>
                        <div class='address-invoice-info'>
                            <div class='address-info'>
                                <div class='row'>

                                </div>
                                <div class='row'>
                                    Telp: 
                                </div>
                                <div class='row'>
                                    Fax: 
                                </div>
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
                                        PO No
                                    </div>
                                    <div class='data-content'>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='data-label'>
                                        Tgl.Faktur
                                    </div>
                                    <div class='data-content'>
                                        {{ date('d/m/Y',strtotime($penjualan->tanggal_transaksi)) }}
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='data-label'>
                                        Jatuh Tempo
                                    </div>
                                    <div class='data-content'>
                                        {{ date('d/m/Y',strtotime($penjualan->tanggal_jatuh_tempo)) }}
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
                            ko Aci
                        </div>
                        <div class='row'>

                        </div>
                        <div class='row'>
                            Telp.
                        </div>
                        <div class='row'>
                            NPWP
                        </div>
                    </div>
                </div>
                <div class='data'>
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    Nama Barang
                                </th>
                                <th>
                                    Kemasan
                                </th>
                                <th class='text-right'>
                                    Qty
                                </th>
                                <th class='text-right'>
                                    Harga Satuan
                                </th>
                                <th>
                                    Disk%
                                </th>
                                <th class='text-right'>
                                    Nilai Disc
                                </th>
                                <th class='text-right'>
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $jumlah = 0;
                            $diskon = 0;
                            $pajak = 0;
                            @endphp
                            @foreach ($penjualan->detail_penjualan as $v)
                                <tr>
                                    <td><p>{{ $v->produk->nama }}</p></td>
                                    <td>Buah</td>
                                    <td class='text-right'>{{ $v->kuantitas }}</td>
                                    <td class='text-right'>{{ number_format($v->harga_satuan, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($v->diskon_per_baris)
                                            {{ $v->diskon_per_baris }}%
                                        @else
                                            0.0%
                                        @endif
                                    </td>
                                    <td class='text-right'>{{ number_format($v->harga_satuan * $v->diskon_per_baris / 100 , 2, ',', '.') }}
                                    <td class='text-right'>{{ number_format($v->jumlah, 2, ',', '.') }}</td>
                                    @php
                                    $jumlah += $v->jumlah;
                                    $diskon += $v->harga_satuan * $v->diskon_per_baris / 100;
                                    $pajak += $v->pajak;
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class='amount-totals row'>
                    <div class='amount-in-words'>
                        <div class='row' style='margin-bottom: 3px;'>
                            TERBILANG
                        </div>
                        @php
                        function terbilang($angka) {
                            $satuan = array('Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
                            $belasan = array('Sepuluh', 'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas', 'Enam Belas', 'Tujuh Belas', 'Delapan Belas', 'Sembilan Belas');
                            $puluhan = array('Dua Puluh', 'Tiga Puluh', 'Empat Puluh', 'Lima Puluh', 'Enam Puluh', 'Tujuh Puluh', 'Delapan Puluh', 'Sembilan Puluh');
                            $ratusan = array('Seratus', 'Dua Ratus', 'Tiga Ratus', 'Empat Ratus', 'Lima Ratus', 'Enam Ratus', 'Tujuh Ratus', 'Delapan Ratus', 'Sembilan Ratus');
                            $ribuan = array('Seribu', 'Dua Ribu', 'Tiga Ribu', 'Empat Ribu', 'Lima Ribu', 'Enam Ribu', 'Tujuh Ribu', 'Delapan Ribu', 'Sembilan Ribu');
                            $miliaran = array('Satu Miliar', 'Dua Miliar', 'Tiga Miliar', 'Empat Miliar', 'Lima Miliar', 'Enam Miliar', 'Tujuh Miliar', 'Delapan Miliar', 'Sembilan Miliar');
                            $triliun = array('Satu Triliun', 'Dua Triliun', 'Tiga Triliun', 'Empat Triliun', 'Lima Triliun', 'Enam Triliun', 'Tujuh Triliun', 'Delapan Triliun', 'Sembilan Triliun');

                            $str = '';

                            if ($angka < 0) {
                                $str = 'Minus ';
                                $angka = abs($angka);
                            }

                            if ($angka < 10) {
                                $str .= $satuan[$angka];
                            } elseif ($angka < 20) {
                                $str .= $belasan[$angka - 10];
                            } elseif ($angka < 100) {
                                $str .= $puluhan[floor($angka / 10) - 2];
                                if ($angka % 10 > 0) {
                                $str .= ' ' . $satuan[$angka % 10];
                                }
                            } elseif ($angka < 1000) {
                                $str .= $ratusan[floor($angka / 100) - 1];
                                if ($angka % 100 > 0) {
                                $str .= ' ' . terbilang($angka % 100);
                                }
                            } elseif ($angka < 1000000) {
                                $str .= $ribuan[floor($angka / 1000) - 1];
                                if ($angka % 1000 > 0) {
                                $str .= ' ' . terbilang($angka % 1000);
                                }
                            } elseif ($angka < 1000000000) {
                                $str .= $miliaran[floor($angka / 1000000) - 1];
                                if ($angka % 1000000 > 0) {
                                $str .= ' ' . terbilang($angka % 1000000);
                                }
                            } else {
                                $str .= $triliun[floor($angka / 1000000000) - 1];
                                if ($angka % 1000000000 > 0) {
                                $str .= ' ' . terbilang($angka % 1000000000);
                                }
                            }

                            return $str;
                        }
                        @endphp
                        <div class='row'>
                            @if($penjualan->jumlah_terbayar)
                            {{ strtoupper(terbilang($penjualan->sisa_tagihan)) }} 
                            @else
                            {{ strtoupper(terbilang($jumlah + $pajak)) }}
                            @endif
                            RUPIAH
                            

                        </div>
                    </div>
                    <div class='totals'>
                        <div class='data-label'>
                            Total
                        </div>
                        <div class='data-content'>
                            {{ number_format($jumlah, 2, ',', '.') }}
                        </div>
                        <div class='data-label'>
                            Diskon
                        </div>
                        <div class='data-content'>
                            0,00
                        </div>
                        <div class='data-label'>
                            PPN 11.0%
                        </div>
                        <div class='data-content'>
                            {{ number_format($pajak, 2, ',', '.') }}
                        </div>
                        <div class='data-label'>
                            Netto
                        </div>
                        <div class='data-content'>
                            {{ number_format($jumlah + $pajak, 2, ',', '.') }}
                        </div>
                        @if($penjualan->jumlah_terbayar)
                        <div class='data-label'>
                            Bayaran Diterima
                        </div>
                        <div class='data-content'>
                            {{ number_format($penjualan->jumlah_terbayar, 2, ',', '.') }}
                        </div>
                        <div class='data-label'>
                            Sisa Tagihan
                        </div>
                        <div class='data-content'>
                            {{ number_format($penjualan->sisa_tagihan, 2, ',', '.') }}
                        </div>
                        @endif
                    </div>
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
                    <div class='message'>
                        <div class='row' style='margin-bottom: 3px;'>
                            PERHATIAN
                        </div>
                        <div class='row'>
                        </div>
                    </div>
                    <div class='signature'>
                        <div class='row'>
                            Hormat kami,
                        </div>
                        <div class='row' style='height: 95px'>

                        </div>
                        <div class='row'>
                            (.......................)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.print()
    </script>
</body>

</html>
