@extends('pages.profil.sidebar', ['sidebar' => $sidebar])

@section('content_profil')
<div class="row">
    <div class="col-8">
        <div class="card mb-5">
            <div class="card-header border-0" style="padding: 1rem 0.5rem">
                <div class="row mb-3">
                    <div class="col">
                        <b>Daftar Perusahaan</b>
                    </div>
                </div>
            </div>
            
            <div class="card-body" style="font-size: 12px">
                <form action="{{ url('profil') }}" method="POST">
                    @csrf
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Nama</div>
                        <div class="col-sm-9">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Ganti Nama</div>
                        <div class="col-sm-9"><input type="text" class="form-control" name="name"></div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Email</div>
                        <div class="col-sm-9">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-sm btn-primary">Simpan perubahan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>
@endsection