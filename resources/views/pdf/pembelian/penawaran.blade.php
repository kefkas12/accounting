<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Penawaran Pembelian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <div class="container mt-5">
    <h2 class="mb-4">{{ $company->nama_perusahaan }}</h2>
    <div class="row mb-5">
      <div class="col">
        Telp : {{ $company->nomor_telepon }} <br>
        Email : {{ $company->email }} <br>
      </div>
      <div class="col">
        <b>Penawaran #</b> : {{ $pembelian->no }} <br>
        <b>Tanggal</b> : {{ $pembelian->tanggal_transaksi }} <br>
      </div>
    </div>
    <hr>
    <div class="d-flex justify-content-center mb-5">
      <h3>Penawaran pembelian</h3>
    </div>
    Pelanggan
    <div class="row mb-4">
      <div class="col">
        Nama : {{ $pembelian->nama }}
      </div>
      <div class="col">
        Berlaku Hingga {{ $pembelian->tanggal_jatuh_tempo }}
      </div>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">NO.</th>
          <th scope="col">KETERANGAN</th>
          <th scope="col">QTY</th>
          <th scope="col">HARGA SATUAN (Rp.)</th>
          <th scope="col">Jumlah (Rp.)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($detail_pembelian as $v)
        <tr>
          <td>{{ $loop->index+1 }}</td>
          <td>{{ $v->nama }}</td>
          <td>{{ $v->kuantitas }} {{ $v->unit }}</td>
          <td>{{ $v->harga_satuan }}</td>
          <td>{{ $v->jumlah }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="row mb-5">
      <div class="col-8"></div>
      <div class="col-4">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">Subtotal</th>
              <th scope="col">{{ $pembelian->subtotal }}</th>
            </tr>
            <tr>
              <th scope="col">Total</th>
              <th scope="col">{{ $pembelian->total }}</th>
            </tr>
          </thead>
        </table>
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