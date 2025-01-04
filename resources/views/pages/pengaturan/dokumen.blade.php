@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
<div class="card" style="border-radius: 0">
    <div class="card-header" style="background: #F1F5F9">
        <div class="row">
            <div class="col" style="padding-left:0 ">Pengaturan Dokumen</div>
            <div class="col d-flex justify-content-end" style="padding-right:0 ">
                <a href="{{ url('pengaturan/dokumen/insert') }}" class="btn btn-primary">Tambah dokumen</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="overflow: auto">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Dokumen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumen as $v)
                    <tr>
                        <td>{{ $v->nama }}</td>
                        <td>
                            @if(Auth::user()->id != $v->id)
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Aksi</button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{ url('pengaturan/dokumen/edit').'/'.$v->id }}">Ubah</a>
                              <a class="dropdown-item" href="{{ url('pengaturan/dokumen/hapus').'/'.$v->id }}">Hapus</a>
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