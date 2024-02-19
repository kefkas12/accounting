@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<!-- Page content -->
<div class="container-fluid mt--6">
    <!-- Dark table -->
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-body">
                    @if (session('berhasil'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('berhasil') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif (session('gagal'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('gagal') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="row justify-content-between mb-2">
                        <div class="col-sm-2">
                            @canany(['create_user_palembang', 'create_user_lampung', 'create_user_bengkulu', 'create_user_ntt', 'create_user_ntb', 'create_user_jambi', 'import_user'])
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Silahkan Pilih
                                </button>
                                <div class="dropdown-menu">
                                    @canany(['create_user_palembang', 'create_user_lampung', 'create_user_bengkulu', 'create_user_ntt', 'create_user_ntb', 'create_user_jambi'])
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal" onclick="tambah();">Tambah User</a>
                                    @endcan
                                    @can('import_user')
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#userImportModal">Import User</a>
                                    @endcan
                                </div>
                            </div>
                            @endcan
                        </div>
                        <div class="col-sm-4">
                            <form action="{{ url('/user') }}">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" name="search" id="search">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="table-responsive">

                        <table class="table align-items-center table-dark table-flush">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Aksi</th>
                                    <th scope="col" data-sort="nama_user">Nama (Cabang)</th>
                                    <th scope="col" data-sort="telepon">Telepon</th>
                                    <th scope="col" data-sort="alamat">Alamat</th>
                                    <th scope="col" data-sort="jabatan">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($user as $v)
                                <tr id="{{ $v->id }}">
                                    <td>
                                        @if(
                                            (auth()->user()->can('update_user_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('update_user_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('update_user_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('update_user_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('update_user_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('update_user_jambi') && $v->cabang == 'JMB') 
                                        )
                                            <a href="#" class="btn btn-primary text-white btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="edit(`{{ $v->id }}`);"><i class="fa fa-pen text-white"></i> Edit</a>
                                        @endif
                                    </td>
                                    <td>{{ $v->nama }} ({{ $v->cabang }})</td>
                                    <td>{{ $v->telepon_1 }}</td>
                                    <td>{{ $v->alamat }}</td>
                                    <td>{{ $v->jabatan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $user->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ url('/user') }}" id="form" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_spinner">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-body" id="modal_body" style="display:none;">
                    <div class="form-group">
                        <label for="name">Perusahaan <span class="text-danger">*</span></label>
                        <select class="form-control" name="perusahaan" id="perusahaan" require>
                            @foreach ($perusahaan as $v)
                            <option>{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cabang <span class="text-danger">*</span></label>
                        <select class="form-control" name="cabang" id="cabang" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jabatan <span class="text-danger">*</span></label>
                        <select class="form-control" name="jabatan" id="jabatan" required>
                            @foreach ($role as $v)
                            <option>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="telepon_1" id="telepon_1" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon 2</label>
                        <input type="text" class="form-control" name="telepon_2" id="telepon_2">
                    </div>
                    <div class="form-group">
                        <label>Telepon 3</label>
                        <input type="text" class="form-control" name="telepon_3" id="telepon_3">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea type="text" class="form-control" name="alamat" id="alamat"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Desa / Kelurahan</label>
                        <input type="text" class="form-control" name="desa_kelurahan" id="desa_kelurahan">
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" class="form-control" name="kecamatan" id="kecamatan">
                    </div>
                    <div class="form-group">
                        <label>Kota / Kabupaten</label>
                        <input type="text" class="form-control" name="kota_kabupaten" id="kota_kabupaten">
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control" name="provinsi" id="provinsi">
                    </div>
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="number" class="form-control" name="nik" id="nik">
                    </div>
                    <div class="form-group">
                        <label>NPWP</label>
                        <input type="number" class="form-control" name="npwp" id="npwp">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="col-form-label">Status</label>
                        <select class="form-control" name="status" id="status" required>
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="button_send" style="display:none;">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="userImportModal" tabindex="-1" role="dialog" aria-labelledby="userImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="{{ url('user/import') }}">
                @csrf
                <div class="modal-body p-4">

                    <h5 class="modal-title mb-4" id="userImportModalLabel">Import User Data</h5>
                    <div class="form-group">
                        <input type="file" name="file" accept=".xls,.xlsx"  required>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <input type="text" class="form-control" name="id" id="input_id" hidden>
                        <button type="button" class="btn btn-outline-primary mx-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-1">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    
    @if(isset($_GET['search']))
        $('#search').val('{{ $_GET["search"] }}')
    @endif

    function tambah() {
        $('#exampleModalLabel').text('Tambah User');
        $('#form').attr('action', '{{ url("/user") }}');
        $('.form-control').val('');
        $('#modal_spinner').hide();
        $('#modal_body').show();
        $('#button_send').show();
        $('#cabang').empty();
        $('#cabang').append(`
            @foreach($cabang as $v)
            <option>{{ $v->nama }}</option>
            @endforeach
        `)
    }

    function edit(id) {
        $('#exampleModalLabel').text('Edit User');
        $('#form').attr('action', '{{ url("/user") }}/' + id);
        $('.form-control').val('');
        $('#modal_spinner').show();
        $('#modal_body').hide();
        $('#button_send').hide();
        $('#cabang').empty();
        $('#cabang').append(`
            @foreach($cabang_edit as $v)
            <option>{{ $v->nama }}</option>
            @endforeach
        `)
        axios.get('{{ url("/user") }}/' + id)
            .then(function(response) {
                const user = response.data.user;
                console.log(user);
                $('#nama').val(user.nama);
                $('#email').val(user.email);
                $('#perusahaan').val(user.perusahaan);
                $('#cabang').val(user.cabang);
                $('#jabatan').val(user.jabatan);
                $('#status').val(user.status);
                $('#telepon_1').val(user.telepon_1);
                $('#telepon_2').val(user.telepon_2);
                $('#telepon_3').val(user.telepon_3);
                $('#nik').val(user.nik);
                $('#npwp').val(user.npwp);
                $('#alamat').val(user.alamat);
                $('#desa_kelurahan').val(user.desa_kelurahan);
                $('#kecamatan').val(user.kecamatan);
                $('#kota_kabupaten').val(user.kota_kabupaten);
                $('#provinsi').val(user.provinsi);
                $('#modal_spinner').hide();
                $('#modal_body').show();
                $('#button_send').show();
            });
    }
</script>
@endsection