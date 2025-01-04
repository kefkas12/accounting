@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
    <div class="card" style="border-radius: 0">
        <div class="card-header">Dokumen</div>
        <div class="card-body">
            <h3>Info Dokumen</h3>
            <form @if(isset($is_edit)) action="{{ url('pengaturan/dokumen/edit').'/'.$dokumen->id }}" @else action="{{ url('pengaturan/dokumen/insert') }}" @endif method="POST" style="font-size: 12px" id="insertForm">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nama">Nama Dokumen</label>
                        <input type="text" class="form-control" name="nama" id="nama" @if(isset($is_edit)) value="{{ $dokumen->nama }}" @endif required>
                    </div>
                    <div class="form-group col-md-8 d-flex justify-content-end">
                        <a href="{{ url('pengaturan/dokumen') }}" class="btn btn-light">Batalkan</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
