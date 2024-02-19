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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Input Barang
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">No</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Merk Barang</th>
                                <th scope="col" class="sort" data-sort="completion">Stock</th>
                                <th scope="col" class="sort">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($barang as $v)
                            <tr id="{{ $v->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="nama">{{ $v->nama }}</td>
                                <td class="merk">{{ $v->merk }}</td>
                                <td class="stock">{{ $v->stock }}</td>
                                <td class="keterangan">{{ $v->keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalUse" onclick="use({{ $v->id }})" hidden>Pakai</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" onclick="edit({{ $v->id }})">Edit</button>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Ban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/barang_input') }}" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="merk" class="col-form-label">Merk Barang</label>
                        <input type="text" class="form-control" name="merk" id="merk" required>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-form-label">Stock Barang</label>
                        <input type="number" class="form-control" name="stock" id="stock" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" id="keterangan">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pakai ban</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/pbarang_use')}}">
                    @csrf
                    <div class="form-group">
                        <label for="tanggal" class="col-form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mobil" class="col-form-label">Mobil</label>
                        <select class="form-control" id="mobil" name="mobil">
                            <option value="" selected disabled hidden>Choose here</option>
                            @foreach($mobil as $v)
                            <option value="{{ $v->id }}">{{ $v->nopol }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ritase" class="col-form-label">Ritase</label>
                        <input type="number" class="form-control" name="ritase">
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="col-form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan">
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
    function edit(id) {
        $('#form').attr('action', '{{ url("barang_edit") }}/' + id);
        var row = $('tr#' + id);
        $('#nama').val(row.find('.nama').html());
        $('#merk').val(row.find('.merk').html());
        $('#stock').val(row.find('.stock').html());
        $('#keterangan').val(row.find('.keterangan').html());
    }

    function use(id) {
        $('#form').attr('action', '{{ url("barang_use") }}/' + id);
        var row = $('tr#' + id);
        $('#nama_barang').val(row.find('.nama_barang').html());
        $('#stock').val(row.find('.stock').html());
        $('#keterangan').val(row.find('.keterangan').html());
    }
</script>
@endsection