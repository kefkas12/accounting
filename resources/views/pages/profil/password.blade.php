@extends('pages.profil.sidebar', ['sidebar' => $sidebar])

@section('content_profil')
<div class="row">
    <div class="col-8">
        <div class="card mb-5">
            <div class="card-header border-0" style="padding: 1rem 0.5rem">
                <div class="row mb-3">
                    <div class="col">
                        <b>Ubah Password</b>
                    </div>
                </div>
            </div>
            
            <div class="card-body" style="font-size: 12px">
                <form action="{{ url('profil/password') }}" method="POST">
                    @csrf
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Password saat ini</div>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" required>
                            <div class="text-danger">
                                @if($errors->has('password'))
                                {{ $errors->first('password') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Password baru</div>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_baru" required>

                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-sm-3">Konfirmasi password baru</div>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="konfirmasi_password" required>
                            <div class="text-danger">
                                @if($errors->has('konfirmasi_password'))
                                {{ $errors->first('konfirmasi_password') }}
                                @endif
                            </div>
                        </div>
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