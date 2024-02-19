@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
@include('layouts.headers.cards')
<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
    }

    .select2-container {
        width: 100% !important;
        padding: 0;
    }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0">
                    Invoice
                </div>
                <div class="card-body">
                    <form action="{{ url('invoice') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="customer" class="col-sm-1 col-form-label text-white">Customer</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="customer" name="customer">
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @foreach($customer as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <label for="mobil" class="col-sm-1 col-form-label text-white">Dari</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="dari">
                            </div>
                            <label for="mobil" class="col-sm-1 col-form-label text-white">Sampai</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="sampai">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary" name="cari">Cari</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table align-items-center table-dark table-flush">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name"></th>
                                    <th scope="col">Pelanggan</th>
                                    <th scope="col">No Invoice</th>
                                    <th scope="col">Tanggal Invoice</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Sisa</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal Lunas</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($invoice as $v)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $v->nama_customer }}</td>
                                    <td>
                                        <div id="div_{{ $v->id }}">{{ $v->no }} &nbsp;&nbsp;
                                            <button type="button" class="btn btn-primary" onclick="$('#form_{{ $v->id }}').show();$('#div_{{ $v->id }}').hide()">
                                                <i class="ni ni-bold-left"></i>
                                            </button>
                                        </div>
                                        <form action="{{ url('invoice').'/'.$v->id }}" method="post" id="form_{{ $v->id }}" style="display:none">
                                            @csrf
                                            <div class="form-row">
                                                <div class="form-group col-md-8"><input type="text" class="form-control" name="no"></div>
                                                <div class="form-group col-md-4"><button type="submit" class="btn btn-primary">Kirim</button></div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ date('d/m/Y',strtotime($v->tanggal)) }}</td>
                                    <td><a href="#" onclick="detail_invoice({{ $v->id }});" data-toggle="modal" data-target="#modal_detail_invoice">Rp {{ number_format($v->total,0,',','.') }}</a></td>
                                    <td><a href="#" onclick="pembayaran({{ $v->id }});" class="text-danger" data-toggle="modal" data-target="#modal_pembayaran">Rp {{ number_format($v->sisa,0,',','.') }}</td>
                                    <td class="text-capitalize">{{ $v->status }}</td>
                                    <td>{{ $v->tanggal_lunas }}</td>
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
<div class="modal fade" tabindex="-1" id="modal_pembayaran" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="form" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-lg-3"><b>Pembayaran</b></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pembayaran" id="transfer" value="transfer" checked="">
                            <label class="form-check-label" for="transfer">Transfer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pembayaran" id="tunai" value="tunai">
                            <label class="form-check-label" for="tunai">Tunai</label>
                        </div>
                    </div>
                    <div id="field_transfer">
                        <div class="form-group row">
                            <label class="col-sm-3"><b>Nama Rekening</b></label>
                            <input type="text" class="form-control col-sm-8" id="nama_rekening" name="nama_rekening">
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3"><b>Nomor Rekening</b></label>
                            <input type="number" class="form-control col-sm-8" id="nomor_rekening" name="nomor_rekening" min="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3"><b>Nilai</b></label>
                        <input type="text" class="form-control col-sm-8" id="nilai" name="nilai" placeholder="Rp" required="" value="">
                    </div>


                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th class="col-sm-1">No.</th>
                                <th class="col-sm-2">Tanggal</th>
                                <th class="col-sm-1">Pembayaran</th>
                                <th class="col-sm-3">Nama Rekening</th>
                                <th class="col-sm-2">Nomor Rekening</th>
                                <th class="col-sm-3">Nilai</th>
                            </tr>
                        </thead>
                        <tbody id="pembayaran">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modal_detail_invoice" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
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
                    <tbody id="detail_invoice">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_print_invoice">Print Invoice</button>
                <button type="button" class="btn btn-secondary" id="btn_print_kwitansi">Print Kwitansi</button>
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

        [elementNilai] = AutoNumeric.multiple(['#nilai'], {
            options
        });

    });

    function popupWindow(url, windowName, win, w, h) {
        const y = win.top.outerHeight / 2 + win.top.screenY - (h / 2);
        const x = win.top.outerWidth / 2 + win.top.screenX - (w / 2);
        return win.open(url, windowName, `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${y}, left=${x}`);
    }

    function print_invoice(id) {
        popupWindow("{{url('/invoice')}}/" + id, 'Print Invoice', window, 800, 600);
    }

    function print_kwitansi(id) {
        popupWindow("{{url('/kwitansi')}}/" + id, 'Print Kwitansi', window, 800, 600);
    }

    function pembayaran(id) {
        $('#form').attr('action', '{{ url("pembayaran") }}/' + id);
        axios.get('{{ url("/lihat_pembayaran") }}', {
                params: {
                    id_invoice: id
                }
            })
            .then(function(response) {
                $('#pembayaran').empty();
                const invoice = response.data.invoice;
                const pembayaran = response.data.pembayaran;
                let no = 0;
                total = 0;
                for (let i = 0; i < pembayaran.length; i++) {
                    no = i + 1;
                    var d = new Date(pembayaran[i].created_at);
                    var datestring = ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" + d.getFullYear();
                    if (pembayaran[i].nama_rekening) {
                        nama_rekening = pembayaran[i].nama_rekening;
                    } else {
                        nama_rekening = '-';
                    }
                    if (pembayaran[i].nomor_rekening) {
                        nomor_rekening = pembayaran[i].nomor_rekening;
                    } else {
                        nomor_rekening = '-';
                    }
                    $('#pembayaran').append(`
                    <tr>
                        <th scope="row">${no}</th>
                        <td>${datestring}</td>
                        <td>${pembayaran[i].pembayaran}</td>
                        <td>${nama_rekening}</td>
                        <td>${nomor_rekening}</td>
                        <td>Rp ${format_ribuan(pembayaran[i].nilai)}</td>
                    </tr>`);
                    total += pembayaran[i].nilai;
                }
                $('#pembayaran').append(`
                <tr>
                    <td colspan="5" class="text-right"><b>Total</b></td>
                    <td>Rp ${format_ribuan(total)}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right"><b>Sisa</b></td>
                    <td>Rp ${format_ribuan(invoice.sisa)}</td>
                </tr>`);
                if (invoice.sisa <= 0) {
                    $('#btn_simpan').hide();
                } else {
                    $('#btn_simpan').show();
                }
            });
    }

    function detail_invoice(id) {
        $('#btn_print_invoice').attr('onclick', 'print_invoice(' + id + ')')
        $('#btn_print_kwitansi').attr('onclick', 'print_kwitansi(' + id + ')')
        axios.get('{{ url("/lihat_detail_invoice") }}', {
                params: {
                    id_invoice: id
                }
            })
            .then(function(response) {
                $('#detail_invoice').empty();
                const detail_invoice = response.data.detail_invoice;
                let no = 0;
                total = 0;
                for (let i = 0; i < detail_invoice.length; i++) {
                    no = i + 1;
                    var d = new Date(detail_invoice[i].tanggal);
                    var datestring = ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" + d.getFullYear();
                    $('#detail_invoice').append(`
                    <tr>
                        <th scope="row">${no}</th>
                        <td>${datestring}</td>
                        <td>${detail_invoice[i].nopol}</td>
                        <td>${detail_invoice[i].jenis}</td>
                        <td>${detail_invoice[i].tujuan}</td>
                        <td>${detail_invoice[i].barang_muat}</td>
                        <td>${format_ribuan(detail_invoice[i].tonase)}</td>
                        <td>Rp ${format_ribuan(detail_invoice[i].harga)}</td>
                        <td>Rp ${format_ribuan(detail_invoice[i].total)}</td>
                    </tr>`);
                    total += parseInt(detail_invoice[i].total);
                }

                $('#detail_invoice').append(`<tr><td colspan="8" class="text-right"><b>Total Harga</b></td><td>Rp ${format_ribuan(total)}</td></tr>`);
            });
    }

    $('[name="pembayaran"]').change(function() {
        $('[name="pembayaran"]:checked').val() == 'transfer' ? $('#field_transfer').show() : $('#field_transfer').hide();
    });
</script>
@endsection