@extends('layouts.app', ['sidebar' => $sidebar])

@section('content')
    @include('layouts.headers.cards')
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-3">
                    <div class="card-header border-0" style="padding: 1rem 0.5rem">
                        <div class="row mb-3">
                            <div class="col">
                                <b>{{ Auth::user()->name }}</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href="{{ url('profil') }}" class="btn btn-outline-primary btn-sm">Profil Akun</a>
                                <a href="{{ url('profil/password') }}" class="btn btn-outline-primary btn-sm">Ubah Password</a>
                                <a hidden href="{{ url('profil/company') }}" class="btn btn-outline-primary btn-sm">Daftar Perusahaan</a>
                            </div>
                        </div>
                    </div>         
                </div>
                @yield('content_profil')
            </div>
        </div>
    </div>
@endsection