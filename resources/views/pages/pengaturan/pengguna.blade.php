@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
<div class="card" style="border-radius: 0">
    <div class="card-header" style="background: #F1F5F9">
        <div class="row">
            <div class="col" style="padding-left:0 ">Pengaturan pengguna</div>
            <div class="col d-flex justify-content-end" style="padding-right:0 ">
                <a href="{{ url('pengaturan/pengguna/insert') }}" class="btn btn-primary">Undang Pengguna</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="overflow: auto">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama pengguna</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengguna as $v)
                    <tr>
                        <td>
                            <div class="d-flex">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-xs username"><a href="#">{{ $v->name }}</a></h6>
                                    <p class="text-xs mb-0 email">{{ $v->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>-</td>
                        <td>Aktif</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection