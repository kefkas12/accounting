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
                        Tambah Customer
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">No</th>
                                <th scope="col" class="sort" data-sort="budget">Nama Customer</th>
                                <th scope="col">Alamat Customer </th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($customer as $v)
                            <tr id="{{ $v->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="nama">{{ $v->nama }}</td>
                                <td class="alamat">@if($v->alamat != '') {{ $v->alamat }} @else - @endif</td>
                                <td class="status">{{ $v->status }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" onclick="edit({{$v->id}})">Edit</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/customer_input') }}" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Customer</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="col-form-label">Alamat Customer</label>
                        <input type="text" class="form-control" name="alamat" id="alamat">
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
        $('#form').attr('action', '{{ url("/customer_edit") }}/' + id);
        var row = $('tr#' + id);
        $('#nama').val(row.find('.nama').html());
        $('#alamat').val(row.find('.alamat').html());
        $('#status').val(row.find('.status').html());
    }
</script>
@endsection