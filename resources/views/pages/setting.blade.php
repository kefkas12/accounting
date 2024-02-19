@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<!-- Page content -->
<div class="container-fluid mt--6">
    
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-default shadow">
                <div class="card-body p-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item mr-2" role="presentation">
                            <button class="btn btn-primary active" id="pills-perusahaan-tab" data-toggle="pill" data-target="#pills-perusahaan" type="button" role="tab" aria-controls="pills-perusahaan" aria-selected="true">Perusahaan</button>
                        </li>
                        <li class="nav-item mr-2" role="presentation">
                            <button class="btn btn-primary" id="pills-cabang-tab" data-toggle="pill" data-target="#pills-cabang" type="button" role="tab" aria-controls="pills-cabang" aria-selected="false">Cabang</button>
                        </li>
                        <li class="nav-item mr-2" role="presentation">
                            <button class="btn btn-primary" id="pills-jabatan-tab" data-toggle="pill" data-target="#pills-jabatan" type="button" role="tab" aria-controls="pills-jabatan" aria-selected="false">Jabatan</button>
                        </li>
                        <li class="nav-item mr-2" role="presentation">
                            <button class="btn btn-primary" id="pills-model-unit-tab" data-toggle="pill" data-target="#pills-model-unit" type="button" role="tab" aria-controls="pills-model-unit" aria-selected="false">Model Unit</button>
                        </li>
                        <li class="nav-item mr-2" role="presentation">
                            <button class="btn btn-primary" id="pills-tipe-tab" data-toggle="pill" data-target="#pills-tipe" type="button" role="tab" aria-controls="pills-tipe" aria-selected="false">Tipe</button>
                        </li>
                    </ul>
                    <hr class="bg-white">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-perusahaan" role="tabpanel" aria-labelledby="pills-perusahaan-tab">
                            @can('create_setting')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#perusahaan_modal" onclick="tambah('perusahaan');">+ Tambah</button>
                            @endcan
                            <div class="table-responsive mt-3">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($perusahaan as $v)
                                        <tr id="perusahaan_{{ $v->id }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td class="nama_perusahaan">{{ $v->nama }}</td>
                                            <td>
                                                @can('update_setting')
                                                <a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#perusahaan_modal" onclick="edit({{ $v->id }},'perusahaan');"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endcan
                                                @can('delete_setting')
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus({{ $v->id }},'perusahaan');"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-cabang" role="tabpanel" aria-labelledby="pills-cabang-tab">
                            @can('create_setting')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#cabang_modal" onclick="tambah('cabang');">+ Tambah</button>
                            @endcan
                            <div class="table-responsive mt-3">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Cabang</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($cabang as $v)
                                        <tr id="cabang_{{ $v->id }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td class="nama_cabang">{{ $v->nama }}</td>
                                            <td>
                                                @can('update_setting')
                                                <a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#cabang_modal" onclick="edit({{ $v->id }},'cabang');"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endcan
                                                @can('delete_setting')
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus({{ $v->id }},'cabang');"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-jabatan" role="tabpanel" aria-labelledby="pills-jabatan-tab">
                            @can('create_setting')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#jabatan_modal" onclick="tambah('jabatan');">+ Tambah</button>
                            @endcan
                            <div class="table-responsive mt-3">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($jabatan as $v)
                                        <tr id="jabatan_{{ $v->id }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td class="nama_jabatan">{{ $v->nama }}</td>
                                            <td>
                                                @can('update_setting')
                                                <a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#jabatan_modal" onclick="edit({{ $v->id }},'jabatan');"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endcan
                                                @can('delete_setting')
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus({{ $v->id }},'jabatan');"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-model-unit" role="tabpanel" aria-labelledby="pills-model-unit-tab">
                            @can('create_setting')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#model_unit_modal" onclick="tambah('model_unit');">+ Tambah</button>
                            @endcan
                            <div class="table-responsive mt-3">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Model Unit</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($model_unit as $v)
                                        <tr id="model_unit_{{ $v->id }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td class="nama_model_unit">{{ $v->nama }}</td>
                                            <td>
                                                @can('update_setting')
                                                <a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#model_unit_modal" onclick="edit({{ $v->id }},'model_unit');"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endcan
                                                @can('delete_setting')
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus({{ $v->id }},'model_unit');"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-tipe" role="tabpanel" aria-labelledby="pills-tipe-tab">
                            @can('create_setting')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#tipe_modal" onclick="tambah('tipe');">+ Tambah</button>
                            @endcan
                            <div class="table-responsive mt-3">
                                <table class="table align-items-center table-dark table-flush">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Tipe</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($tipe as $v)
                                        <tr id="tipe_{{ $v->id }}">
                                            <td>{{ $loop->index+1 }}</td>
                                            <td class="nama_tipe">{{ $v->nama }}</td>
                                            <td>
                                                @can('update_setting')
                                                <a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#tipe_modal" onclick="edit({{ $v->id }},'tipe');"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endcan
                                                @can('delete_setting')
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus({{ $v->id }},'tipe');"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="perusahaan_modal" tabindex="-1" role="dialog" aria-labelledby="perusahaan_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="form_perusahaan">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="modal-title mb-3" id="perusahaan_modal_label">Tambah Perusahaan</h5>
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" class="form-control" name="nama" id="input_nama_perusahaan" required>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <button type="button" class="btn btn-white text-danger m-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-1">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cabang_modal" tabindex="-1" role="dialog" aria-labelledby="cabang_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="form_cabang">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="modal-title mb-3" id="cabang_modal_label">Tambah Cabang</h5>
                    
                    <div class="form-group">
                        <label>Nama Cabang</label>
                        <input type="text" class="form-control" name="nama" id="input_nama_cabang" required>
                    </div>
                    
                    <div class="row d-flex justify-content-end">
                        <button type="button" class="btn btn-white text-danger m-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-1">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="jabatan_modal" tabindex="-1" role="dialog" aria-labelledby="jabatan_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="form_jabatan">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="modal-title mb-3" id="jabatan_modal_label">Tambah Jabatan</h5>

                    <div class="form-group">
                        <label>Nama Jabatan</label>
                        <input type="text" class="form-control" name="nama" id="input_nama_jabatan" required>
                    </div>
                    
                    <div class="row d-flex justify-content-end">
                        <button type="button" class="btn btn-white text-danger m-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-1">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="model_unit_modal" tabindex="-1" role="dialog" aria-labelledby="model_unit_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="form_model_unit">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="modal-title mb-3" id="model_unit_modal_label">Tambah Model Unit</h5>
                    
                    <div class="form-group">
                        <label>Model Unit</label>
                        <input type="text" class="form-control" name="nama" id="input_nama_model_unit" required>
                    </div>
                    
                    <div class="row d-flex justify-content-end">
                        <button type="button" class="btn btn-white text-danger m-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-1">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tipe_modal" tabindex="-1" role="dialog" aria-labelledby="tipe_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" id="form_tipe">
                @csrf
                <div class="modal-body p-4">
                    <h5 class="modal-title mb-3" id="tipe_modal_label">Tambah Tipe</h5>
                    
                    <div class="form-group">
                        <label>Tipe</label>
                        <input type="text" class="form-control" name="nama" id="input_nama_tipe" required>
                    </div>
                    
                    <div class="row d-flex justify-content-end">
                        <button type="button" class="btn btn-white text-danger m-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary m-1">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function tambah(status) {
        $('#'+status+'_modal_label').text('Tambah '+status);
        $('#form_'+status).attr('action', '{{ url("/setting") }}/'+status);
        $('.form-control').val('');
        $('#modal_spinner_service').hide();
        $('#modal_body_service').show();
        $('#button_send_service').show();
    }
    function edit(id,status) {
        var data = $('tr#'+status+'_' + id);
        $('#'+status+'_modal_label').text("edit "+status);
        $('#input_nama_'+status+'').val(data.find('.nama_'+status+'').text());
        $('#form_'+status).attr("action", "{{ url('setting/edit') }}/"+status+"/"+ id);
    }
    function hapus(id,status){
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapusnya?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: `Batal`,
            confirmButtonColor: '#dd6b55'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Berhasil Dihapus!', '', 'success');
                window.location.href = '{{ url("setting/delete") }}/'+status+'/'+id;
            }
            // else if (result.isDenied) {
            //     Swal.fire('Changes are not saved', '', 'info')
            // }
        })
    }
</script>
@endsection