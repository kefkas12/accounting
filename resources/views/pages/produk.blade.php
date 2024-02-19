@extends('layouts.app')

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
                        Tambah Produk
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">No</th>
                                <th scope="col" class="sort" data-sort="budget">Nama Supir</th>
                                <th scope="col">Nama Rekening </th>
                                <th scope="col" class="sort">Nomor Rekening</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Supir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/supir_input') }}" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Supir</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_rekening" class="col-form-label">Nama Rekening Supir</label>
                        <input type="text" class="form-control" name="nama_rekening" id="nama_rekening" required>
                    </div>
                    <div class="form-group">
                        <label for="no_rekening" class="col-form-label">No Rekening Supir</label>
                        <input type="number" class="form-control" name="no_rekening" id="no_rekening" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="col-form-label">Status</label>
                        <select class="form-control" name="status" id="status">
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
    function edit(id) {
        $('#form').attr('action', '{{ url("/supir_edit") }}/' + id);
        var row = $('tr#' + id);
        $('#nama').val(row.find('.nama').html());
        $('#nama_rekening').val(row.find('.nama_rekening').html());
        $('#no_rekening').val(row.find('.no_rekening').html());
        $('#status').val(row.find('.status').html());
    }
</script>
@endsection