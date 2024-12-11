@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
<div class="card" style="border-radius: 0">
    <div class="card-header" style="background: #F1F5F9">
        <div class="row">
            <div class="col" style="padding-left:0 ">Pengaturan Status Pengiriman</div>
            <div class="col d-flex justify-content-end" style="padding-right:0 ">
                <a href="{{ url('pengaturan/status_pengiriman/insert') }}" class="btn btn-primary">Tambah status pengiriman</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="overflow: auto">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Status Pengiriman</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($status_pengiriman as $v)
                    <tr>
                        <td>{{ $v->nama }}</td>
                        <td>
                            @if(Auth::user()->id != $v->id)
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{ url('pengaturan/status_pengiriman/edit').'/'.$v->id }}">Ubah</a>
                              <a class="dropdown-item" href="{{ url('pengaturan/status_pengiriman/hapus').'/'.$v->id }}">Hapus</a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection