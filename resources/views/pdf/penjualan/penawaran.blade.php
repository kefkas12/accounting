<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Penawaran Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<?php
function penyebut($nilai) {
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";
  if ($nilai < 12) {
    $temp = " ". $huruf[$nilai];
  } else if ($nilai <20) {
    $temp = penyebut($nilai - 10). " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
  }     
  return $temp;
}

function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}     		
	return $hasil;
}
?>
  <div class="container mt-5">
    <h2 class="mb-4">{{ $company->nama_perusahaan }}</h2>
    <div class="row mb-5">
      <div class="col">
        Telp : {{ $company->nomor_telepon }} <br>
        Email : {{ $company->email }} <br>
      </div>
      <div class="col">
        <b>Penawaran #</b> : {{ $penjualan->no }} <br>
        <b>Tanggal</b> : {{ date('d/m/Y',strtotime($penjualan->tanggal_transaksi)) }} <br>
      </div>
    </div>
    <hr>
    <div class="d-flex justify-content-center mb-5">
      <h3>Penawaran penjualan</h3>
    </div>
    Pelanggan
    <div class="row mb-4">
      <div class="col border">
        Nama : {{ $penjualan->nama }}
      </div>
      <div class="col border">
        Berlaku Hingga {{ date('d/m/Y',strtotime($penjualan->tanggal_jatuh_tempo)) }}
      </div>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">NO.</th>
          <th scope="col">KETERANGAN</th>
          <th scope="col">QTY</th>
          <th scope="col">HARGA SATUAN (Rp.)</th>
          <th scope="col">DISKON</th>
          <th scope="col">Jumlah (Rp.)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($detail_penjualan as $v)
        <tr>
          <td>{{ $loop->index+1 }}</td>
          <td>{{ $v->nama }}</td>
          <td>{{ $v->kuantitas }} {{ $v->unit }}</td>
          <td>{{ number_format($v->harga_satuan,0,',','.') }}</td>
          <td>{{ number_format($v->diskon_per_baris,1,'.',',') }} %</td>
          <td>{{ number_format($v->jumlah,0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="row">
      <div class="col-8"></div>
      <div class="col-4">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">Subtotal</th>
              <th scope="col">{{ number_format($penjualan->subtotal,0,',','.') }}</th>
            </tr>
            <tr>
              <th scope="col">PPN (11%)</th>
              <th scope="col">{{ number_format($penjualan->ppn,0,',','.') }}</th>
            </tr>
            <tr>
              <th scope="col">Total</th>
              <th scope="col">{{ number_format($penjualan->total,0,',','.') }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-7 border">
        <span id="terbilang" class="text-uppercase">
          Terbilang <br>
          {{ terbilang($penjualan->total) }} Rupiah
        </span>
      </div>
      <div class="col-4">
        
      </div>
    </div>
    <br>
    <br>
    <div class="row mb-5">
      <div class="col-sm-8"></div>
      <div class="col-sm-4"><hr></div>
    </div>
    <br>
    <br>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>