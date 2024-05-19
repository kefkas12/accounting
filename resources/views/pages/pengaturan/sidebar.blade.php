@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
    @include('layouts.headers.cards')
    <div class="mt--6">
        <div class="row">
            <div class="col-sm-2" style="padding: 0">
                <div class="card" style="border-radius: 0;">
                    <ul class="list-group list-group-flush text-sm" >
                        <li class="list-group-item text-primary"  style="background: #F1F5F9; font-size:13px;">Pengaturan</li>
                        <li class="list-group-item" style="background: #F1F5F9; font-size:13px;"><a href="{{ url('pengaturan/perusahaan') }}" style="color:#000000 !important;">Perusahaan</a></li>
                        <li class="list-group-item" style="background: #F1F5F9; font-size:13px;"><a href="{{ url('pengaturan/pengguna') }}" style="color:#000000 !important;">Pengguna</a></li>
                        <li class="list-group-item" style="background: #F1F5F9; font-size:13px;"><a href="{{ url('pengaturan/approval') }}" style="color:#000000 !important;">Aturan Approval</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-10"  style="padding: 0">
                @yield('content_pengaturan')
            </div>
        </div>
    </div>
@endsection
