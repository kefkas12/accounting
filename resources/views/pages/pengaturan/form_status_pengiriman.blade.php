@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
    <div class="card" style="border-radius: 0">
        <div class="card-header">Status Pengiriman</div>
        <div class="card-body">
            <h3>Info Status Pengiriman</h3>
            <form @if(isset($is_edit)) action="{{ url('pengaturan/status_pengiriman/edit').'/'.$status_pengiriman->id }}" @else action="{{ url('pengaturan/status_pengiriman/insert') }}" @endif method="POST" style="font-size: 12px" id="insertForm">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="nama">Nama Status Pengiriman</label>
                        <input type="text" class="form-control" name="nama" id="nama" @if(isset($is_edit)) value="{{ $status_pengiriman->nama }}" readonly @endif required>
                    </div>
                    <div class="form-group col-md-8 d-flex justify-content-end">
                        <a href="{{ url('pengaturan/status_pengiriman') }}" class="btn btn-light">Batalkan</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
