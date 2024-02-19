@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">
                        <div class="row mb-3">
                            <div class="col">
                                <a href="{{ url('jurnal/insert') }}"class="btn btn-primary" >
                                    Buat Jurnal Umum
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        
                        <table class="table align-items-center table-flush">
                            <thead>
                                <tr>
                                    <th>Nomor Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Nama Kategori</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($akun as $v)
                                <tr>
                                    <td>{{ $v->nomor }}</td>
                                    <td><a href="{{ url('akun/detail').'/'.$v->id }}">{{ $v->nama }}</a></td>
                                    <td>{{ $v->nama_kategori }}</td>
                                    <td>@if($v->saldo < 0 )( {{ number_format(abs($v->saldo),2,',','.') }} ) @else {{ number_format($v->saldo,2,',','.')  }} @endif</td>
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
@endsection
