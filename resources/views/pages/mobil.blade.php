@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
@include('layouts.headers.cards')
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="tambah()">
                        Tambah Mobil
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">No</th>
                                <th scope="col" class="sort" data-sort="budget">Nomor Polisi</th>
                                <th scope="col">Jenis</th>
                                <th scope="col">Nama Pemilik</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                                <th scope="col">Oli</th>
                                <th scope="col">Ban</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($mobil as $v)
                            <tr id="{{ $v->id }}" @if($v->ritase > 3) class="bg-danger" @endif>
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="nopol">{{ $v->nopol }}</td>
                                <td class="jenis">{{ $v->jenis }}</td>
                                <td class="nama_pemilik">{{ $v->nama_pemilik }}</td>
                                <td class="status">{{ $v->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" onclick="edit({{$v->id}})">Edit</button>
                                </td>
                                <form action='{{ url("ganti_oli") }}/{{ $v->id }}' method="POST">
                                    @csrf
                                    <td>
                                        {{ $v->ritase ? $v->ritase : 0 }} Rit &nbsp;&nbsp;
                                        <button type="submit" class="btn btn-primary">Ganti Oli</button>
                                    </td>
                                </form>
                                <td>
                                    @if( $v->jenis == 'engkel')
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gantiBanEngkelModal" onclick="ganti_ban({{$v->id}},'{{ $v->nopol }}')">Ganti Ban</button>
                                    @else
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gantiBanTrontonModal" onclick="ganti_ban({{$v->id}},'{{ $v->nopol }}')">Ganti Ban</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="gantiBanEngkelModal" aria-labelledby="gantiBanEngkelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gantiBanEngkelModalLabel">Ganti Ban <span id="nama_mobil"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="text-center">Kiri</div>
                        <img src="{{ asset('image/engkel_kiri.png') }}" width="100%">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-6"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('depan_kiri')">Ganti</button></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kiri')">Ganti</button></div>
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-center">Kanan</div>
                        <img src="{{ asset('image/engkel_kanan.png') }}" width="100%">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-6"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kanan')">Ganti</button></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('depan_kanan')">Ganti</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="gantiBanTrontonModal" aria-labelledby="gantiBanTrontonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gantiBanTrontonModalLabel">Ganti Ban <span id="nama_mobil"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="text-center">Kiri</div>
                        <img src="{{ asset('image/tronton_kiri.png') }}" width="100%">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-5"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('depan_kiri')">Ganti</button></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kiri_depan')">Ganti</button></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kiri_belakang')">Ganti</button></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-center">Kanan</div>
                        <img src="{{ asset('image/tronton_kanan.png') }}" width="100%">
                        <div class="row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kanan_belakang')">Ganti</button></div>
                            <div class="col-sm-5"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('belakang_kanan_depan')">Ganti</button></div>
                            <div class="col-sm-2"><button type="button" data-toggle="modal" data-target="#formGantiBanModal" onclick="posisi_ban('depan_kanan')">Ganti</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="formGantiBanModal" aria-labelledby="formGantiBanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal_posisi">
        <div class="modal-content">
            <form method="POST" id="form_ganti_ban">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="formGantiBanModalLabel"><span id="posisi_ban"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" hidden>
                        <label for="id_mobil" class="col-form-label">id</label>
                        <input type="text" class="form-control" name="id_mobil" id="id_mobil" required>
                    </div>
                    <div class="form-group" hidden>
                        <label for="posisi" class="col-form-label">Posisi</label>
                        <input type="text" class="form-control" name="posisi" id="posisi" readonly>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="tanggal" class="col-form-label">Tanggal Ganti</label>
                                <input type="date" class="form-control" name="tanggal[]" id="tanggal" required>
                            </div>
                            <div class="form-group">
                                <label for="kode" class="col-form-label">Kode Ban</label>
                                <input type="text" class="form-control" name="kode[]" id="kode" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis" class="col-form-label">Jenis Ban</label>
                                <select class="form-control" name="jenis[]" id="jenis">
                                    <option value="" selected disabled hidden>Silahkan Pilih</option>
                                    @foreach($ban as $v)
                                    <option value="{{ $v->id }}">{{ $v->merk }} : {{ $v->stock }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan[]" id="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col" id="double">
                            <div class="form-group">
                                <label for="tanggal" class="col-form-label">Tanggal Ganti</label>
                                <input type="date" class="form-control" name="tanggal[]" id="tanggal" required>
                            </div>
                            <div class="form-group">
                                <label for="kode" class="col-form-label">Kode Ban</label>
                                <input type="text" class="form-control" name="kode[]" id="kode" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis" class="col-form-label">Jenis Ban</label>
                                <select class="form-control" name="jenis[]" id="jenis">
                                    <option value="" selected disabled hidden>Silahkan Pilih</option>
                                    @foreach($ban as $v)
                                    <option value="{{ $v->id }}">{{ $v->merk }} : {{ $v->stock }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan[]" id="keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Mobil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/mobil_input') }}" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="nopol" class="col-form-label">Nomor Polisi</label>
                        <input type="text" class="form-control" name="nopol" id="nopol" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis" class="col-form-label">Jenis Mobil</label>
                        <select class="form-control" name="jenis" id="jenis">
                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                            <option value="engkel">Engkel</option>
                            <option value="tronton">Tronton</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nopol" class="col-form-label">Pemilik</label>
                        <select class="form-control" id="pemilik">
                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                            <option value="gemilang">Gemilang</option>
                            <option value="luar">Luar</option>
                        </select>
                    </div>
                    <div class="form-group" id="form_pemilik" style="display:none;">
                        <label for="nama_pemilik" class="col-form-label">Nama Pemilik</label>
                        <select class="form-control" name="nama_pemilik" id="nama_pemilik">
                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                            @foreach($partner as $v)
                            <option>{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="col-form-label">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="" selected disabled hidden>Silahkan Pilih</option>
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function tambah() {
        $('#form').attr('action', '{{ url("/mobil_input") }}');
        $('#nopol').val('');
        $('#jenis').val('');
        $('#pemilik').val('');
        $('#status').val('');
    }

    function ganti_ban(id, nopol) {
        $('#form_ganti_ban').attr('action', '{{ url("/ganti_ban") }}/' + id);
        $('#nama_mobil').text(nopol);
        $('#id_mobil').val(id);
    }

    function posisi_ban(posisi) {
        $('#posisi_ban').text(posisi);
        $('#posisi').val(posisi);
        if (posisi == 'depan_kiri' || posisi == 'depan_kanan') {
            $('#double').hide();
            $('#modal_posisi').removeClass('modal-xl');
        } else {
            $('#double').show();
            $('#modal_posisi').addClass('modal-xl');
        }
    }

    function edit(id) {
        $('#form').attr('action', '{{ url("/mobil_edit") }}/' + id);
        var data = $('#' + id);
        $('#nopol').val(data.find('.nopol').text());
        $('#jenis').val(data.find('.jenis').text());
        $('#nama_pemilik').val(data.find('.nama_pemilik').text());
        if (data.find('.nama_pemilik').text() != '') {
            $('#pemilik').val('luar');
            $('#form_pemilik').show();
            $('#nama_pemilik').val(data.find('.nama_pemilik').text());
        } else {
            $('#pemilik').val('gemilang');
            $('#form_pemilik').hide();
            $('#nama_pemilik').val('');
        }
        $('#status').val(data.find('.status').text());
    }
    $('#pemilik').change(function() {
        if ($(this).val() == 'luar') {
            $('#form_pemilik').show();
        } else {
            $('#form_pemilik').hide();
            $('#nama_pemilik').val('');
        }
    })
</script>
@endsection