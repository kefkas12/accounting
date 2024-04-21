@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
    <div class="card" style="border-radius: 0">
        <div class="card-header">Info Perusahaan</div>
        <div class="card-body">
            <form action="{{ url('pengaturan/perusahaan/insert') }}" method="POST" enctype="multipart/form-data" style="font-size: 12px" id="insertForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3"><label for="name">Nama Pengguna</label></div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="nama_perusahaan" id="nama_perusahaan" required value="{{ $perusahaan->nama_perusahaan }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">Alamat perusahaan</div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="alamat_perusahaan" id="alamat_perusahaan" required value="{{ $perusahaan->alamat_perusahaan }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">Nomor telepon</div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" required value="{{ $perusahaan->nomor_telepon }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">Logo Perusahaan</div>
                    <div class="col-md-7">
                        <input type="file" class="form-control" name="logo_perusahaan" id="logo_perusahaan" accept="image/*" required value="{{ $perusahaan->logo_perusahaan }}">
                    </div>
                </div>
                <div class="form-group col-md-10 d-flex justify-content-end">
                    <a href="{{ url('pengaturan/pengguna') }}" class="btn btn-light">Batalkan</a>
                    <button type="button" class="btn btn-primary" onclick="check_password()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function check_password() {
            event.preventDefault();
            if (!$('#nama_perusahaan').val()) {
                Swal.fire({
                    title: 'Nama Pengguna didn`t match',
                    text: 'Nama Pengguna tidak boleh kosong',
                    icon: 'error'
                })
            } else {
                $('#insertForm').submit();
            }
        }
    </script>
@endsection
