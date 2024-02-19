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
                    <div class="row justify-content-between mb-2" >
                        <div class="col-sm-2">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahServiceModal" onclick="tambah();" hidden>
                                Tambah Service
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <form action="{{ url('/service') }}">
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
                                    <th scope="col">Customer</th>
                                    <th scope="col">No Service Bill Manual</th>
                                    <th scope="col">Tgl. Service</th>
                                    <th scope="col">No Seri Unit</th>
                                    <th scope="col">Model Unit</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">Hourmeter</th>
                                    <th scope="col">Jasa Service</th>
                                    <th scope="col">No Invoice</th>
                                    
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($service as $v)
                                <tr id="{{ $v->id }}">
                                    <td>
                                        @if(
                                            (auth()->user()->can('update_service_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('update_service_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('update_service_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('update_service_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('update_service_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('update_service_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-primary text-white btn-sm" data-toggle="modal" data-target="#tambahServiceModal" onclick="edit(`{{ $v->id }}`);"><i class="fa fa-pen text-white"></i> Edit</a>
                                        @endif
                                        @if(
                                            (auth()->user()->can('delete_service_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('delete_service_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('delete_service_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('delete_service_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('delete_service_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('delete_service_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus(`{{ $v->id }}`);"><i class="fa fa-trash text-white"></i> Delete</a>
                                        @endif
                                    </td>
                                    <td>{{ $v->nama_konsumen }} ({{ $v->cabang }})</td>
                                    <td><a href="#" data-toggle="modal" data-target="#foto_service_modal" onclick="lihat_foto_service('{{ $v->id }}');">{{ $v->no_service_bill_manual }}</a></td>
                                    <td>{{ date('d-m-Y', strtotime($v->tanggal)) }}</td>
                                    <td>{{ $v->no_seri_unit }}</td>
                                    <td>{{ $v->model_unit }}</td>
                                    <td>{{ $v->tipe }}</td>
                                    <td>{{ $v->hourmeter }}</td>
                                    <td>{{ $v->jasa_service }}</td>
                                    <td>{{ $v->no_invoice }}</td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $service->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="foto_service_modal" tabindex="-1" role="dialog" aria-labelledby="foto_service_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="foto_service_modal_label">Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_spinner_foto_service">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-body" id="modal_body_foto_service" style="display:none;">
                <p class="text-center" id="message_file"></p>
                <table class="table text-center table-hover table-dark" id="table_foto">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Foto</th>
                        </tr>
                    </thead>
                    <tbody id="foto_detail_no_service_bill_manual">
                    
                    </tbody>
                </table>
                <table class="table text-center table-hover table-dark" id="table_file">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">File</th>
                        </tr>
                    </thead>
                    <tbody id="file_detail_no_service_bill_manual">
                    
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ url('/service') }}" id="form" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Service</h5>
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
                            @foreach ($cabang as $v)
                            <option>{{ $v->nama }}</option>
                            @endforeach
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="button_send" style="display:none;">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahServiceModal" tabindex="-1" aria-labelledby="tambahServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ url('/service') }}" id="form_service" autocomplete="off"
                    enctype="multipart/form-data" onsubmit="return validateForm();">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahServiceModalLabel">Tambah Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_spinner_service">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-body" id="modal_body_service" style="display:none;">
                        <div class="form-group">
                            <label for="no_service_bill_manual">No Service Bill Manual <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_service_bill_manual"
                                name="no_service_bill_manual" required>
                        </div>
                        <div class="form-group">
                            <label for="no_invoice">No Invoice</label>
                            <input type="text" class="form-control" id="no_invoice" name="no_invoice">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_service">Tanggal Service <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_service" name="tanggal_service"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        </div>
                        <div class="form-group">
                            <label for="id_nama_teknisi_1">Mekanik 1 <span class="text-danger">*</span></label>
                            <select type="text" class="form-control" id="id_nama_teknisi_1" name="id_nama_teknisi_1"
                                required>
                                <option selected disabled hidden>Choose here</option>
                                @foreach ($karyawan as $row)
                                    <option value="{{ $row->id }}_{{ $row->nama }}">{{ $row->nama }} -
                                        {{ $row->alamat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_nama_teknisi_2">Mekanik 2</label>
                            <select type="text" class="form-control" id="id_nama_teknisi_2" name="id_nama_teknisi_2">
                                <option selected disabled hidden>Choose here</option>
                                @foreach ($karyawan as $row)
                                    <option value="{{ $row->id }}_{{ $row->nama }}">{{ $row->nama }} -
                                        {{ $row->alamat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hourmeter">Hourmeter <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="hourmeter" name="hourmeter" required>
                        </div>

                        <div class="form-group">
                            <label for="id_nama_driver">Driver</label>
                            <select type="text" class="form-control" id="id_nama_driver" name="id_nama_driver">
                                <option selected disabled hidden>Choose here</option>
                                @foreach ($driver as $row)
                                    <option value="{{ $row->id }}_{{ $row->nama }}">{{ $row->nama }} -
                                        {{ $row->alamat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="permasalahan_service">Permasalahan Service <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="permasalahan_service" name="permasalahan_service" required>
                        </div>
                        <div class="form-group">
                            <label for="penyebab">Penyebab <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="penyebab" name="penyebab" required>
                        </div>
                        <div class="form-group">
                            <label for="tindakan_perbaikan">Tindakan Perbaikan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tindakan_perbaikan" name="tindakan_perbaikan"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="jasa_service">Jasa Service <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jasa_service" name="jasa_service" required>
                        </div>
                        <div class="form-group">
                            <label for="sparepart">Sparepart <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="sparepart" name="sparepart" required>
                        </div>
                        <div class="form-group">
                            <label for="transport">Transport <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="transport" name="transport" required>
                        </div>
                        <div class="form-group">
                            <label for="garansi">Garansi <span class="text-danger">*</span></label>
                            <select type="text" class="form-control" id="garansi" name="garansi" required>
                                <option>Ya</option>
                                <option>Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_service">Foto</label>
                            <div class="row" id="foto_service">
                                <div class="container-image" id="wrap_foto_service_1">
                                    <img class="card img-list" id="img_service_1"
                                        src="{{ URL::asset('images/add.png') }}" alt="your image"
                                        onclick="javascript:document.getElementById('input_service_1').click();" />
                                    <div class="right">
                                        <a class="text-danger text-shadow shadow hide" role="button"
                                            id="button_hapus_foto_service_1" class="hide"
                                            onclick="hapus_foto_service(1)">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </div>
                                    <input type="file" name="foto_service[]" class="form-control col-sm-10"
                                        id="input_service_1" onchange="readURL_service(this);" hidden
                                        accept="image/jpg, image/jpeg, image/png">
                                    <input type="text" name="input_foto_service[]" class="form-control col-sm-10"
                                        id="input_foto_service_1" hidden>
                                </div>
                                <button class="btn btn-success col-sm-2" type="button"
                                    onclick="tambah_form_foto_service()" hidden><span
                                        class="material-icons">add</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="button_send">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div class="modal fade" id="serviceImportModal" tabindex="-1" role="dialog" aria-labelledby="serviceImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="{{ url('import_service') }}">
                @csrf
                <div class="modal-body p-4">

                    <h5 class="modal-title mb-4" id="serviceImportModalLabel">Import Service Data</h5>

                    <div class="form-group">
                        <input type="file" name="file" required>
                    </div>


                    <div class="row d-flex justify-content-end">
                        <input type="text" class="form-control" name="id" id="input_id" hidden>
                        <button type="button" class="btn btn-white text-danger mx-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info ml-1">Import</button>
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
    var select_id_nama_teknisi_1 = $("#id_nama_teknisi_1").selectize();
    var select_id_nama_teknisi_2 = $("#id_nama_teknisi_2").selectize();
    var select_id_nama_driver = $("#id_nama_driver").selectize();
    
    var isi_service = [];
    var banyak_service = 1;
    function tambah() {
        $('#exampleModalLabel').text('Tambah Service');
        $('#form').attr('action', '{{ url('/service ') }}');
        $('.form-control').val('');
        $('#modal_spinner_service').hide();
        $('#modal_body_service').show();
        $('#button_send_service').show();
    }

    function edit(id) {
        isi_service = [];
        $('#tambahServiceModalLabel').text('Edit Service');
        $('#form_service').attr('action', '{{ url('service') }}/' + id);
        $('.form-control').val('');
        $('#modal_spinner_service').show();
        $('#modal_body_service').hide();
        $('#lihatModal').modal('toggle');
        axios.get('{{ url('/service') }}/' + id)
            .then(function(response) {
                const service = response.data.service;
                $('#no_service_bill_manual').val(service.no_service_bill_manual);
                $('#no_invoice').val(service.no_invoice);
                $('#tanggal_service').val(service.tanggal);
                $('#jam_mulai').val(service.jam_mulai);
                $('#jam_selesai').val(service.jam_selesai);
                $('#hourmeter').val(service.hourmeter);
                $('#permasalahan_service').val(service.permasalahan);
                $('#penyebab').val(service.penyebab);
                $('#tindakan_perbaikan').val(service.tindakan_perbaikan);
                $('#jasa_service').val(service.jasa_service);
                $('#sparepart').val(service.sparepart);
                $('#transport').val(service.transport);
                $('#garansi').val(service.garansi);

                select_id_nama_teknisi_1[0].selectize.setValue(service.id_teknisi_1+'_'+service.nama_teknisi_1);
                if(service.id_teknisi_2){
                    select_id_nama_teknisi_2[0].selectize.setValue(service.id_teknisi_2+'_'+service.nama_teknisi_2);
                }
                select_id_nama_driver[0].selectize.setValue(service.id_driver+'_'+service.nama_driver);

                $('#modal_spinner_service').hide();
                $('#modal_body_service').show();

                var foto = service.images;
                if (foto) {
                    var str = foto.split("|");

                    for (var i = 0; i < str.length; i++) {
                        var x = i + 1;
                        $('#img_service_' + x).attr('src', '{{ url('/image_service/') }}/' + str[i] + '');
                        $('#img_service_' + x).addClass("card p-2 img-list");

                        $('#input_foto_service_' + x).val(str[i]);
                        if (isi_service.includes(x) === false) {
                            isi_service.push(x);
                            tambah_form_foto_service();
                        }
                        var no_button_hapus = banyak_service - 1;
                        $("#button_hapus_foto_service_" + no_button_hapus).removeClass("hide");
                        // banyak_service += 1;

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
                window.location.href = '{{ url("service/delete") }}/'+id;
            }
            // else if (result.isDenied) {
            //     Swal.fire('Changes are not saved', '', 'info')
            // }
        })
    }
    function readURL_service(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var id = input.id.split("_");
            reader.onload = function(e) {
                $('#img_service_' + id[2]).attr('src', e.target.result);
                $('#img_service_' + id[2]).addClass("card p-2 img-list");
            }

            reader.readAsDataURL(input.files[0]);
            if (isi_service.includes(id[2]) === false) {
                isi_service.push(id[2]);
                tambah_form_foto_service();
            }
            var no_button_hapus = banyak_service - 1;
            $("#button_hapus_foto_service_" + no_button_hapus).removeClass("hide");
        }
    }
    function tambah_form_foto_service() {
        banyak_service++;
        $('#foto_service').append(
            `<div class = "container-image"id = "wrap_foto_service_${banyak_service}" ><img class = "card img-list" id = "img_service_${banyak_service}"src = "{{ URL::asset('images/add.png') }}" alt = "your image" onclick = "javascript:document.getElementById('input_service_${banyak_service}').click();" / > <div class = "right"><a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_foto_service_${banyak_service}" class = "hide" onclick = "hapus_foto_service(${banyak_service})" > <i class="material-icons">close</i></a></div><input type = "file" name="foto_service[]" class = "form-control col-sm-10"id = "input_service_${banyak_service}" onchange = "readURL_service(this);"hidden accept="image/jpg, image/jpeg, image/png"><input type="text" name="input_foto_service[]" class="form-control col-sm-10" id="input_foto_service_${banyak_service}" hidden></div>`
        );
    }
    function hapus_foto_service(id) {
        $('#wrap_foto_service_' + id).remove();
    }
    
    function lihat_foto_service(id) {
        $('#foto_detail_no_service_bill_manual').empty();
        $('#file_detail_no_service_bill_manual').empty();
        $('#modal_spinner_foto_service').show();
        $('#modal_body_foto_service').hide();
        
        $('#table_foto').hide();
        $('#table_file').hide();
        $.ajax({
            url: "{{url('service/foto')}}/" + id,
            success: function(result) {
                var foto = result.service.images;
                if (foto) {
                    $('#table_foto').show();
                    
                    var str = foto.split("|");

                    for (var i = 0; i < str.length; i++) {
                        var x = i + 1;
                        $('#foto_detail_no_service_bill_manual').append(`
                        <tr>
                            <th scope="row">${x}</th>
                            <td>
                                <a href="{{ url("/image_service/")}}/${str[i]}" target="_blank"><img class="mb-3" width="200px" src="{{ url("/image_service/")}}/${str[i]}"></a>
                            </td>
                        </tr>
                        `);
                    }
                }
                var file = result.service.file;
                if (file) {
                    $('#table_file').show();
                    var str = file.split("|");

                    for (var i = 0; i < str.length; i++) {
                        var x = i + 1;
                        $('#file_detail_no_service_bill_manual').append(`
                        <tr>
                            <th scope="row">${x}</th>
                            <td>
                                <a href="{{ url("/file_service/")}}/${str[i]}" target="_blank">${str[i]}</a>
                            </td>
                        </tr>
                        `);
                    }
                }
                $('#modal_spinner').hide();
                if(!foto && !file){
                    $('#message_file').text('Tidak ada File');
                }else{
                    $('#message_file').text('');
                }
            }
        }).then(() => {
            $('#modal_spinner_foto_service').hide();
            $('#modal_body_foto_service').show();
        });
    }
</script>
@endsection