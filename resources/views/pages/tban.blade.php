@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card bg-default shadow">
                    <div class="table-responsive">
                        <table class="table align-items-center table-dark table-flush">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">No</th>
                                    <th scope="col" class="sort" data-sort="budget">Tanggal</th>
                                    <th scope="col" class="sort" data-sort="status">Kode Ban</th>
                                    <th scope="col">Jumlah </th>
                                    <th scope="col">Harga </th>
                                    <th scope="col">Total</th>
                                    <th scope="col" class="sort" data-sort="completion">Keterangan</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($tban as $v)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $v->tanggal }}</td>
                                        <td>{{ $v->kode_ban }}</td>
                                        <td> +{{ $v->jumlah }}</td>
                                        <td>{{ $v->harga }}</td>
                                        <td>{{ $v->total }}</td>
                                        <td>{{ $v->keterangan }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="/tban_delete/{{ $v->id }}">
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>
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
                    <form method="POST" action="{{ url('/ban_input') }}">
                        @csrf
                        <div class="form-group">
                            <label for="kode" class="col-form-label">Kode Ban</label>
                            <input type="text" class="form-control" name="kode" id="kode" required>
                        </div>
                        <div class="form-group">
                            <label for="harga" class="col-form-label">Harga Ban</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis" class="col-form-label">Jenis Ban</label>
                            <input type="text" class="form-control" name="jenis" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah" class="col-form-label">Jumlah Ban</label>
                            <input type="number" class="form-control" name="jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="ritase" class="col-form-label">Ritase</label>
                            <input type="number" class="form-control" name="ritase" required>
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
    @endsection
