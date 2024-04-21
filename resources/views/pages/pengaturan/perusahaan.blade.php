@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
<div class="card" style="border-radius: 0">
    <div class="card-header" style="background: #F1F5F9">
        <div class="row">
            <div class="col" style="padding-left:0 ">Pengaturan perusahaan</div>
            <div class="col d-flex justify-content-end" style="padding-right:0 ">
                <a href="{{ url('pengaturan/perusahaan/insert') }}" class="btn btn-primary">Edit perusahaan</a>
            </div>
        </div>
    </div>
    <div class="card-body" style="font-size: 12px">
        <div class="row mb-3">
            <div class="col-md-12"><h3>Info Perusahaan</h3></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">Nama perusahaa</div>
            <div class="col-md-4">{{ $perusahaan->nama_perusahaan }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">Alamat perusahaan</div>
            <div class="col-md-4">{{ $perusahaan->alamat_perusahaan ? $perusahaan->alamat_perusahaan : '-' }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">Nomor telepon</div>
            <div class="col-md-4">{{ $perusahaan->nomor_telepon ? $perusahaan->nomor_telepon : '-' }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">Logo Perusahaan</div>
            <div class="col-md-12"><img src="{{ $perusahaan->logo_perusahaan ? asset('argon/img/brand').'/'.$perusahaan->logo_perusahaan : 'https://cdn-icons-png.flaticon.com/512/4812/4812244.png' }}" alt="" width="100px"></div>
        </div>
    </div>
</div>
@endsection