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
                    @can('create_role')
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Role
                    </button>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">No</th>
                                <th scope="col" class="sort" data-sort="budget">Nama Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($role as $v)
                            <tr id="{{ $v->id }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="name">{{ $v->name }}</td>
                                <td>
                                    @can('permission_role')
                                    <a class="btn btn-primary btn-sm" href="{{ url('permission').'/'.$v->id }}"><i class="fa fa-gear"></i> Permission</a>
                                    @endcan
                                    @can('update_role')
                                    <a href="#" data-toggle="modal" data-target="#exampleModal" onclick="edit({{ $v->id }});">Edit</a>
                                    @endcan
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('/role') }}" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label">Nama Role</label>
                        <input type="text" class="form-control" name="name" id="name" required autofocus>
                    </div>
                    <div class="form-group" hidden>
                        <label for="alamat" class="col-form-label">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function edit(id) {
        $('#exampleModalLabel').text('Edit Role Details');
        $('#form').attr('action', '{{ url("/role") }}/' + id);
        var row = $('tr#' + id);
        $('#name').val(row.find('.name').html());
    }
</script>
@endsection