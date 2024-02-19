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
                            @canany(['import_unit'])
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Silahkan Pilih
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal" onclick="tambah();" hidden>Tambah Unit</a>
                                    @can('import_unit')
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#unitImportModal">Import Unit</a>
                                    @endcan
                                </div>
                            </div>
                            @endcan
                        </div>
                        <div class="col-sm-4">
                            <form action="{{ url('/unit') }}">
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
                                    <th scope="col" class="sort" data-sort="cabang">Cabang</th>
                                    <th scope="col" class="sort" data-sort="no_seri_unit">No Seri Unit</th>
                                    <th scope="col" class="sort" data-sort="no_engine">No Engine</th>
                                    <th scope="col" class="sort" data-sort="model_unit">Model Unit</th>
                                    <th scope="col" class="sort" data-sort="pemilik_terakhir">Pemilik Terakhir</th>
                                    <th scope="col" class="sort" data-sort="pemilik_terakhir">Tgl Serah Terima</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($unit as $v)
                                <tr id="{{ $v->id }}">
                                    <td>
                                        @if(
                                            (auth()->user()->can('update_unit_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('update_unit_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('update_unit_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('update_unit_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('update_unit_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('update_unit_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-primary text-white btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="edit(`{{ $v->id }}`);"><i class="fa fa-pen text-white"></i> Edit</a>
                                        @endif
                                        @if(
                                            (auth()->user()->can('delete_unit_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('delete_unit_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('delete_unit_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('delete_unit_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('delete_unit_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('delete_unit_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus(`{{ $v->id }}`);"><i class="fa fa-trash text-white"></i> Delete</a>
                                        @endif
                                    </td>
                                    <td><a href="#" data-toggle="modal" data-target="#mutasi_modal" onclick="lihat_mutasi('{{ $v->id }}');">{{ $v->cabang }}</a></td>
                                    <td>{{ $v->no_seri_unit }}</td>
                                    <td>{{ $v->no_engine }}</td>
                                    <td>{{ $v->model_unit }}</td>
                                    <td>{{ $v->nama_pemilik_terakhir_serah_terima }}</td>
                                    <td>{{ $v->tgl_serah_terima }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $unit->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ url('/unit') }}" id="form" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Unit</h5>
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
                    @can('mutasi')
                    <div class="form-group">
                        <label class="form-label">Cabang <span class="text-danger">*</span></label>
                        <select class="form-control" name="cabang" id="cabang" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mutasi</label>
                        <input type="date" class="form-control" name="tanggal_mutasi" id="tanggal_mutasi">
                    </div>
                    @endcan
                    <div class="form-group">
                        <label class="form-label">No Seri Unit</label>
                        <input type="text" class="form-control" name="no_seri_unit" id="no_seri_unit">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Engine</label>
                        <input type="text" class="form-control" name="no_engine" id="no_engine">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Model Unit</label>
                        <select class="form-control" name="model_unit" id="model_unit">
                            @foreach($model_unit as $row)
                            <option>{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No Buku Warranty</label>
                        <input type="text" class="form-control" name="no_buku_warranty" id="no_buku_warranty">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tracking Warranty</label>
                        <input type="text" class="form-control" name="tracking_warranty" id="tracking_warranty">
                    </div>
                    <div class="form-group">
                        <label for="id_pemilik_terakhir_serah_terima">Nama Pemilik Terakhir Serah Terima</label>
                        <select class="form-control" id="id_pemilik_terakhir_serah_terima" name="id_pemilik_terakhir_serah_terima">
                            <option selected disabled hidden>Choose here</option>
                            @foreach($konsumen as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }} - {{ $row->alamat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Serah Terima Unit</label>
                        <input type="date" class="form-control" name="tgl_serah_terima" id="tgl_serah_terima">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Foto</label>
                        <div class="row" id="foto_unit">
                        </div>
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
<div class="modal fade" id="unitImportModal" tabindex="-1" role="dialog" aria-labelledby="unitImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="{{ url('unit/import') }}">
                @csrf
                <div class="modal-body p-4">

                    <h5 class="modal-title mb-4" id="unitImportModalLabel">Import Unit Data</h5>

                    <div class="form-group">
                        <input type="file" name="file" required>
                    </div>


                    <div class="row d-flex justify-content-end">
                        <input type="text" class="form-control" name="id" id="input_id" hidden>
                        <button type="button" class="btn btn-white text-danger mx-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-1">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mutasi_modal" tabindex="-1" role="dialog" aria-labelledby="mutasi_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mutasi_modal_label">Mutasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_spinner_mutasi">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-body" id="modal_body_mutasi" style="display:none;">
                <p class="text-center" id="message"></p>
                <table class="table text-center table-hover table-dark" id="table_mutasi">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Dari</th>
                            <th scope="col">Ke</th>
                        </tr>
                    </thead>
                    <tbody id="mutasi">
                    
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var banyak_unit = 1;
    var isi_unit = [];
    
    var select_id_pemilik_terakhir_serah_terima = $("#id_pemilik_terakhir_serah_terima").selectize();
    
    @if(isset($_GET['search']))
        $('#search').val('{{ $_GET["search"] }}')
    @endif
    
    function tambah() {
        $('#exampleModalLabel').text('Tambah Unit');
        $('#form').attr('action', '{{ url("/unit") }}');
        $('.form-control').val('');
        $('#modal_spinner').hide();
        $('#modal_body').show();
        $('#button_send').show();
        $('#cabang').append(`
            @foreach($cabang as $v)
            <option>{{ $v->nama }}</option>
            @endforeach
        `)
    }

    function edit(id) {
        banyak_unit = 1;
        isi_unit = [];
        $('#exampleModalLabel').text('Edit Unit');
        $('#form').attr('action', '{{ url("/unit") }}/' + id);
        $('.form-control').val('');
        $('#modal_spinner').show();
        $('#modal_body').hide();
        $('#button_send').hide();
        $('#cabang').append(`
            @foreach($cabang_edit as $v)
            <option>{{ $v->nama }}</option>
            @endforeach
        `)

        axios.get('{{ url("/unit") }}/' + id)
            .then(function(response) {
                const unit = response.data.unit;
                $('#cabang').val(unit.cabang);
                $('#no_seri_unit').val(unit.no_seri_unit);
                $('#no_engine').val(unit.no_engine);
                $('#model_unit').val(unit.model_unit);
                $('#no_buku_warranty').val(unit.no_buku_warranty);
                $('#tracking_warranty').val(unit.tracking_warranty);
                $('#jabatan').val(unit.jabatan);
                $('#status_pdi').val(unit.status_pdi);
                select_id_pemilik_terakhir_serah_terima[0].selectize.setValue(unit.id_pemilik_terakhir_serah_terima);
                $('#tgl_serah_terima').val(unit.tgl_serah_terima);
                reload_foto();
                var foto = unit.images_unit;
                if (foto) {
                    var str = foto.split("|");
                    for (var i = 0; i < str.length; i++) {
                        var x = i + 1;
                        $('#img_unit_' + x).attr('src', '{{ url("/image_unit/")}}/' + str[i] + '');
                        $('#img_unit_' + x).addClass("card p-2 img-list");

                        $('#input_foto_unit_' + x).val(str[i]);

                        if (isi_unit.includes(x) === false) {
                            isi_unit.push(x);
                            tambah_form_foto_unit();
                        }

                        var no_button_hapus = banyak_unit - 1;
                        $("#button_hapus_foto_unit_" + no_button_hapus).removeClass("hide");
                        // banyak_unit += 1;
                    }
                }
                $('#modal_spinner').hide();
                $('#modal_body').show();
                $('#button_send').show();
            });
    }
    
    function hapus(id){
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapusnya?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: `Batal`,
            confirmButtonColor: '#dd6b55',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Berhasil Dihapus!', '', 'success');
                window.location.href = '{{ url("unit/delete") }}/'+id;
            }
        })
    }

    function reload_foto() {
        $('#foto_unit').empty();
        $('#foto_unit').html(`
            <div class="container-image" id="wrap_foto_unit_1">
                <img class="card img-list" id="img_unit_1" src="{{ URL::asset('images/add.png') }}" alt="your image" onclick="javascript:document.getElementById('input_unit_1').click();" />
                <div class="right">
                    <a class="text-danger text-shadow shadow hide" role="button" id="button_hapus_foto_unit_1" class="hide" onclick="hapus_foto_unit(1)">
                        <i class="material-icons">close</i>
                    </a>
                </div>
                <input type="file" name="foto_unit[]" class="form-control col-sm-10" id="input_unit_1" onchange="readURL_unit(this);" hidden accept="image/jpg, image/jpeg, image/png">
                <input type="text" name="input_foto_unit[]" class="form-control col-sm-10" id="input_foto_unit_1" hidden>
            </div>
            <button class="btn btn-success col-sm-2" type="button" onclick="tambah_form_foto_unit()" hidden><span class="material-icons">add</span></button>
        `);
    }

    function tambah_form_foto_unit() {
        banyak_unit++;
        $('#foto_unit').append(`<div class = "container-image"id = "wrap_foto_unit_${banyak_unit}" ><img class = "card img-list" id = "img_unit_${banyak_unit}"src = "{{ URL::asset("images/add.png") }}" alt = "your image" onclick = "javascript:document.getElementById('input_unit_${banyak_unit}').click();" / > <div class = "right"><a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_foto_unit_${banyak_unit}" class = "hide" onclick = "hapus_foto_unit(${banyak_unit})" > <i class="material-icons">close</i></a></div><input type = "file" name="foto_unit[]" class = "form-control col-sm-10"id = "input_unit_${banyak_unit}" onchange = "readURL_unit(this);"hidden accept="image/jpg, image/jpeg, image/png"><input type="text" name="input_foto_unit[]" class="form-control col-sm-10" id="input_foto_unit_${banyak_unit}" hidden></div>`);
    }

    function hapus_foto_unit(id) {
        $('#wrap_foto_unit_' + id).remove();

    }

    function readURL_unit(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var id = input.id.split("_");
            reader.onload = function(e) {
                $('#img_unit_' + id[2]).attr('src', e.target.result);
                $('#img_unit_' + id[2]).addClass("card p-2 img-list");
            }

            reader.readAsDataURL(input.files[0]);
            if (isi_unit.includes(id[2]) === false) {
                isi_unit.push(id[2]);
                tambah_form_foto_unit();
            }
            var no_button_hapus = banyak_unit - 1;
            $("#button_hapus_foto_unit_" + no_button_hapus).removeClass("hide");
        }
    }

    function edit_pemilik_terakhir(id, id_pemilik_terakhir) {
        $('#form_pemilik_terakhir').attr('action', '{{ url("edit_nama_pemilik") }}/' + id);

    }
    
    function lihat_mutasi(id) {
        $('#mutasi').empty();
        $('#modal_spinner_mutasi').show();
        $('#modal_body_mutasi').hide();
        $.ajax({
            url: "{{url('unit/mutasi')}}/" + id,
            success: function(response) {
                const mutasi = response.mutasi;
                if(mutasi.length > 0){
                    for (let i = 0; i < mutasi.length; i++) {
                        $('#mutasi').append(`
                        <tr>
                            <td>${mutasi[i].tanggal}</td>
                            <td>${mutasi[i].dari}</th>
                            <td>${mutasi[i].ke}</td>
                            
                        </tr>
                        `);
                    }
                }else{
                    $('#message').text('Belum pernah Mutasi');
                }
            }
        }).then(() => {
            $('#modal_spinner_mutasi').hide();
            $('#modal_body_mutasi').show();
        });
    }
</script>
@endsection