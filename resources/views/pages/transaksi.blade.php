@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
@include('layouts.headers.cards')
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0 text-white">
                    Transaksi {{ $status }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-danger" role="alert">
                        Mobil Masih Dalam Perjalanan
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{ url('transaksi_muat').'/'.$status }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="tanggal_muat" class="col-sm-2 col-form-label text-white">Tanggal Muat</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="tanggal_muat" name="tanggal_muat" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobil" class="col-sm-2 col-form-label text-white">Nomor Polisi</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="mobil" name="mobil" required>
                                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                                            @foreach($mobil as $v)
                                            <option value="{{ $v->id }}">{{ $v->nopol }} @if( $v->nama_pemilik )- {{ $v->nama_pemilik }} @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="supir" class="col-sm-2 col-form-label text-white">Nama Supir</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="supir" name="supir">
                                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                                            @foreach($supir as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <div class="row justify-content-end mr-1">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Tgl Muat</th>
                                            <th scope="col">Nopol (supir)</th>
                                            <th scope="col">Detail Barang</th>
                                            <th scope="col">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($transaksi as $v)
                                        <tr id="{{ $v->id }}">
                                            <td>
                                                {{ $loop->index+1 }}
                                            </td>
                                            <td>{{ date('d-m-Y',strtotime($v->tanggal_pergi)) }}</td>
                                            <td>{{ $v->nopol }} ({{ $v->nama_supir }})</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lihatModal" onclick="lihat({{ $v->id }})">Lihat Barang</button>
                                                @if($v->status == 'pergi')
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahModal" onclick="tambah({{ $v->id }})">+</button>
                                                @endif
                                            </td>
                                            <form action='{{ url("transaksi_bongkar") }}/{{ $v->id }}' method="POST">
                                                @csrf
                                                <td>
                                                    {{ $v->status }} &nbsp;&nbsp;
                                                    @if($v->status == 'pergi')
                                                    <button type="submit" class="btn btn-primary">Pulang</button>
                                                    @endif
                                                </td>
                                            </form>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="lihatModal" aria-labelledby="lihatModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lihatModalLabel">Lihat Muatan {{ $status }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table align-items-center ">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal Muat</th>
                                <th scope="col">Tanggal Bongkar</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">Tujuan</th>
                                <th scope="col">Barang Muat</th>
                                <th scope="col">Tonase</th>
                                <th scope="col">Klaim</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="lihat">
                        </tbody>
                    </table>
                </div>
                <div class="text-center" id="loading">
                    Loading...
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahModal" aria-labelledby="tambahModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Muatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form">
                    @csrf
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-4 col-form-label">Tanggal Muat</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_bongkar" class="col-sm-4 col-form-label">Tanggal Bongkar</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggal_bongkar" name="tanggal_bongkar" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="customer" class="col-sm-4 col-form-label">Pelanggan</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="customer" name="customer" required>
                                <option value="" selected disabled hidden>Silahkan Pilih</option>
                                @foreach($customer as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tujuan" class="col-sm-4 col-form-label">Tujuan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tujuan" name="tujuan" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barang_muat" class="col-sm-4 col-form-label">Barang Muat</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="barang_muat" name="barang_muat" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tonase" class="col-sm-4 col-form-label">Tonase</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tonase" name="tonase" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga" class="col-sm-4 col-form-label">Harga</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="harga" name="harga" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="total" class="col-sm-4 col-form-label">Total</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="total" name="total" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status">
                                <option value="pergi">Pergi</option>
                                <option value="pulang">Pulang</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-4">
                        <div class="row justify-content-end mr-1">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_detail_transaksiModal" aria-labelledby="edit_detail_transaksiModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_detail_transaksiModalLabel">Tambah Muatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_edit">
                    @csrf
                    <div class="form-group row">
                        <label for="tanggal_edit" class="col-sm-4 col-form-label">Tanggal</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggal_edit" name="tanggal" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_bongkar_edit" class="col-sm-4 col-form-label">Tanggal Bongkar</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggal_bongkar_edit" name="tanggal_bongkar" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="customer" class="col-sm-4 col-form-label">Pelanggan</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="customer_edit" name="customer">
                                <option value="" selected disabled hidden>Silahkan Pilih</option>
                                @foreach($customer as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tujuan" class="col-sm-4 col-form-label">Tujuan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tujuan_edit" name="tujuan" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="barang_muat" class="col-sm-4 col-form-label">Barang Muat</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="barang_muat_edit" name="barang_muat" value="-" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tonase" class="col-sm-4 col-form-label">Tonase</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tonase_edit" name="tonase" value="0" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="status_edit" name="status">
                                <option value="pergi">Pergi</option>
                                <option value="pulang">Pulang</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="klaim" class="col-sm-4 col-form-label">Klaim</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="klaim_edit" name="klaim" value="0">
                        </div>
                    </div>
                    <div class="col-sm-12 mb-4">
                        <div class="row justify-content-end mr-1">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#mobil').select2();
        $('#supir').select2();
        var options = {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            minimumValue: 0,
            unformatOnSubmit: true
        };

        [elementTonase, elementTonase_edit, elementHarga, elementTotal, elementKlaim] = AutoNumeric.multiple(['#tonase', '#tonase_edit', '#harga', '#total', '#klaim_edit'], {
            options
        });

    });

    $('form').submit(function() {
        var form = $(this);
        $('input').each(function(i) {
            var self = $(this);
            try {
                var v = self.AutoNumeric('get');
                self.autoNumeric('destroy');
                self.val(v);
            } catch (err) {
                console.log("Not an autonumeric field: " + self.attr("name"));
            }
        });
        return true;
    });

    function tambah(id) {
        $('#form').attr('action', '{{ url("transaksi") }}/{{ $status }}/' + id);
        var row = $('tr#' + id);
        $('.tanggal').hide();
        $('#kode').val(row.find('.kode').html());

        elementTonase.set(0);
        elementHarga.set(0);
        elementTotal.set(0);
    }

    function lihat(id) {
        $('#lihat').empty();
        $('#loading').text('Loading...');
        axios.get('{{ url("/detail_transaksi") }}/' + id)
            .then(function(response) {
                $('#loading').empty();
                const detail_transaksi = response.data.detail_transaksi;
                console.log(detail_transaksi);
                for (let i = 0; i < detail_transaksi.length; i++) {
                    var d = new Date(detail_transaksi[i].tanggal);
                    var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + d.getFullYear();

                    if (detail_transaksi[i].tanggal_bongkar) {
                        var d = new Date(detail_transaksi[i].tanggal_bongkar);
                        var datestring_bongkar = ("0" + d.getDate()).slice(-2) + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + d.getFullYear();
                    }else{
                        var datestring_bongkar = '-';
                    }

                    $('#lihat').append(`
                        <tr id="detail_${detail_transaksi[i].id}">
                            <td>${i+1}</td>
                            <td >${datestring}</td>
                            <td >${datestring_bongkar}</td>
                            <td class="tanggal" hidden>${detail_transaksi[i].tanggal}</td>
                            <td class="tanggal_bongkar" hidden>${detail_transaksi[i].tanggal_bongkar}</td>
                            <td class="id_customer" hidden>${detail_transaksi[i].id_customer}</td>
                            <td>${detail_transaksi[i].nama_customer}</td>
                            <td class="tujuan">${detail_transaksi[i].tujuan}</td>
                            <td class="barang_muat">${detail_transaksi[i].barang_muat}</td>
                            <td>${format_ribuan(detail_transaksi[i].tonase)}</td>
                            <td>Rp ${format_ribuan(detail_transaksi[i].klaim)}</td>
                            <td class="tonase" hidden>${detail_transaksi[i].tonase}</td>
                            <td class="klaim" hidden>${detail_transaksi[i].klaim}</td>
                            <td class="status">${detail_transaksi[i].status}</td>
                            <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_detail_transaksiModal" onclick="edit_detail_transaksi(${detail_transaksi[i].id})">Ubah</button></td>
                        </tr>`);
                }

            });
    }
    $('#tonase').on('keyup keydown', function() {
        var tonase = elementTonase.getNumber();
        var harga = elementHarga.getNumber();
        elementTotal.set(tonase * harga);
    });
    $('#harga').on('keyup keydown', function() {
        var harga = elementHarga.getNumber();
        var tonase = elementTonase.getNumber();
        elementTotal.set(tonase * harga);
    });

    function edit_detail_transaksi(id) {
        var row = $('tr#detail_' + id);
        console.log(row.find('.tonase').text());
        $('#form_edit').attr('action', '{{ url("/detail_transaksi") }}/' + id);
        $('#tanggal_edit').val(row.find('.tanggal').text());
        $('#tanggal_bongkar_edit').val(row.find('.tanggal_bongkar').text());
        $('#customer_edit').val(row.find('.id_customer').text());
        $('#tujuan_edit').val(row.find('.tujuan').text());
        $('#barang_muat_edit').val(row.find('.barang_muat').text());
        elementTonase_edit.set(row.find('.tonase').text());
        elementKlaim.set(row.find('.klaim').text());
        $('#status_edit').val(row.find('.status').text());

    }
</script>
@endsection