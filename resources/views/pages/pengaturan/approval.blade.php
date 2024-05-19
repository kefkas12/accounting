@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
<div class="card" style="border-radius: 0">
    <div class="card-header" style="background: #F1F5F9">
        <div class="row">
            <div class="col" style="padding-left:0 ">Pengaturan approval</div>
            <div class="col d-flex justify-content-end" style="padding-right:0 ">
                <a href="{{ url('pengaturan/approval/insert') }}" class="btn btn-primary">Buat Aturan Approval</a>
            </div>
        </div>
    </div>
    <div class="card-body" style="font-size: 12px">
        <div class="row mb-3">
            <div class="col-md-12"><h3>Aturan Approval</h3></div>
        </div>
            <div style="overflow: auto">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Aturan</th>
                            <th>Tipe Transaksi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approval as $v)
                        <tr>
                            <td>{{ $v->nama }}</td>
                            <td>{{ $v->tipe_transaksi }}</td>
                            <td><a href="">Hapus</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</div>
@endsection