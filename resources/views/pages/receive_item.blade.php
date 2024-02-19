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
                            @canany(['create_receive_item_palembang', 'create_receive_item_lampung', 'create_receive_item_bengkulu', 'create_receive_item_ntt', 'create_receive_item_ntb', 'create_receive_item_jambi'])
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Silahkan Pilih
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal" onclick="tambah();">Tambah Receive Item</a>
                                    {{-- <a class="dropdown-item" href="#">Import Receive Item</a> --}}
                                </div>
                            </div>
                            @endcan
                        </div>
                        <div class="col-sm-4">
                            <form action="{{ url('/receive_item') }}">
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
                                    <th scope="col" class="sort" data-sort="tanggal">Tgl. Masuk</th>
                                    <th scope="col" class="sort" data-sort="supplier">Supplier</th>
                                    <th scope="col" class="sort" data-sort="pengirim">Pengirim</th>
                                    <th scope="col" class="sort" data-sort="penerima">Penerima</th>
                                    <th scope="col" class="sort" data-sort="foto">Foto</th>
                                    <th scope="col" class="sort" data-sort="detail">Unit</th>

                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($receive_item as $v)
                                <tr id="{{ $v->id }}">
                                    <td>
                                        @if(
                                            (auth()->user()->can('update_receive_item_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('update_receive_item_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('update_receive_item_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('update_receive_item_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('update_receive_item_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('update_receive_item_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-primary text-white btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="edit(`{{ $v->id }}`);"><i class="fa fa-pen text-white"></i> Edit</a>
                                        @endif
                                        @if(
                                            (auth()->user()->can('delete_receive_item_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('delete_receive_item_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('delete_receive_item_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('delete_receive_item_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('delete_receive_item_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('delete_receive_item_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus(`{{ $v->id }}`);"><i class="fa fa-trash text-white"></i> Delete</a>
                                        @endif
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($v->tanggal)) }} ({{ $v->cabang }})</td>
                                    <td>{{ $v->nama_supplier }}</td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#detailPengirimModal" onclick="lihat_pengirim({{ $v->id }});">{{ $v->nama_sopir }}</a>
                                    </td>
                                    <td>
                                        @if($v->nama_penerima != '')
                                        <a href="#" data-toggle="modal" data-target="#detailPenerimaModal" onclick="lihat_penerima({{ $v->id_karyawan_penerima }});">{{ $v->nama_penerima }}</a>
                                        @else
                                        @endif
                                    </td>
                                    <td>
                                        @if($v->images != '')
                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#lihatFotoModal" onclick="lihat_foto(`{{ $v->images }}`,'penerimaan_unit');">Lihat</a>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detailUnitModal" onclick="lihat_unit(`{{ $v->id }}`);">Lihat</button>
                                        @if(
                                            (auth()->user()->can('create_unit_palembang') && $v->cabang == 'PLM') ||
                                            (auth()->user()->can('create_unit_lampung') && $v->cabang == 'LMP') ||
                                            (auth()->user()->can('create_unit_bengkulu') && $v->cabang == 'BKL') ||
                                            (auth()->user()->can('create_unit_ntt') && $v->cabang == 'NTT') ||
                                            (auth()->user()->can('create_unit_ntb') && $v->cabang == 'NTB') ||
                                            (auth()->user()->can('create_unit_jambi') && $v->cabang == 'JMB') 
                                        )
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambahModal" onclick="tambah_unit({{ $v->id }})">+</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $receive_item->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ url('receive_item') }}" enctype="multipart/form-data" id="form" autocomplete="off">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Receive Item</h5>
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
                        <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                        <select class="form-control" name="perusahaan" id="perusahaan" required>
                            @foreach($perusahaan as $row)
                            <option>{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cabang <span class="text-danger">*</span></label>
                        <select class="form-control" name="cabang" id="cabang" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <select class="form-control" name="id_karyawan_penerima" id="id_karyawan_penerima" required>
                            <option>Select one</option>
                            @foreach($karyawan as $row)
                            <option value="{{ $row->id }}">{{ $row->nama }} - {{ $row->alamat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Sopir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_sopir" id="nama_sopir" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon Sopir <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="telepon_sopir" id="telepon_sopir" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Polisi Truk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="no_polisi_truk" id="no_polisi_truk" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Foto</label>
                        <div class="row" id="foto_penerimaan_unit">
                        </div>
                    </div>
                    <hr>
                    <div id="isi_unit" hidden>
                        <div class="form-group">
                            <label class="form-label">Unit</label>
                        </div>
                        <div class="row" id="unit">
                            <div class="col-sm-12 mb-3">
                                <div class="card">
                                    <div class="card-body p-2 pt-3">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <a class="text-link" onclick="tambah_unit()">Tambah Unit</a>
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
<div class="modal fade" id="userImportModal" tabindex="-1" role="dialog" aria-labelledby="userImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="{{ url('import_user') }}">
                @csrf
                <div class="modal-body p-4">

                    <h5 class="modal-title mb-4" id="userImportModalLabel">Import User Data</h5>

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
<div class="modal fade" id="detailPengirimModal" tabindex="-1" aria-labelledby="detailPengirimModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPengirimModalLabel">Detail Pengirim</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="p-0 m-0">Nama Sopir</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 pengirim" id="detail_nama_sopir" style="display:none;"></h5>

                <p class="p-0 m-0">Telepon Sopir</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 pengirim" id="detail_telepon_sopir" style="display:none;"></h5>

                <p class="p-0 m-0">Nomor Handphone</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 pengirim" id="detail_no_polisi_truk" style="display:none;"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailPenerimaModal" tabindex="-1" aria-labelledby="detailPenerimaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPenerimaModalLabel">Detail Penerima</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="p-0 m-0">Nama Penerima</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 penerima" id="detail_nama_penerima" style="display:none;"></h5>

                <p class="p-0 m-0">Telepon Penerima</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 penerima" id="detail_telepon_penerima" style="display:none;"></h5>

                <p class="p-0 m-0">Alamat Penerima</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h5 class="text-dark pt-1 pb-3 penerima" id="detail_alamat_penerima" style="display:none;"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailUnitModal" tabindex="-1" role="dialog" aria-labelledby="detailUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUnitModalLabel">Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="row d-flex justify-content-center">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Aksi</th>
                                    <th scope="col">No Seri</th>
                                    <th scope="col">Model</th>
                                    <th scope="col">No Warranty</th>
                                    <th scope="col">Foto</th>
                                </tr>
                            </thead>
                            <tbody id="detail_unit">
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" enctype="multipart/form-data" id="form_unit">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group col-sm-12">
                        <label class="form-label">No Seri Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="no_seri_unit" id="no_seri_unit" required>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">No Engine</label>
                        <input type="text" class="form-control" name="no_engine" id="no_engine">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">Model Unit <span class="text-danger">*</span></label>
                        <select class="form-control" name="model_unit" id="model_unit" required>
                            @foreach($model_unit as $row)
                            <option>{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">No Buku Warranty</label>
                        <input type="text" class="form-control" name="no_buku_warranty" id="no_buku_warranty">
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">Tracking Warranty</label>
                        <input type="text" class="form-control" name="tracking_warranty" id="tracking_warranty">
                    </div>
                    <div class="form-group col-sm-12" id="show_status_pdi">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status_pdi" id="status_pdi" required>
                            <option value="Open">Open PDI</option>
                            <option>Kurang Part</option>
                            <option>Rusak</option>
                            <option value="Done">Done PDI</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">Foto</label>
                        <div class="row" id="foto_unit">
                            <div class="container-image" id="wrap_foto_unit_1">
                                <img class="card img-list" id="foto_unit_1" src="{{ URL::asset('images/add.png') }}" alt="your image" onclick="javascript:document.getElementById('input_unit_1').click();" />
                                <div class="right">
                                    <a class="text-danger text-shadow shadow hide" role="button" id="button_hapus_foto_unit_1" class="hide" onclick="hapus_foto_unit(1)">
                                        <i class="material-icons">close</i>
                                    </a>
                                </div>
                                <input type="file" name="foto_unit[]" class="form-control col-sm-10" id="input_unit_1" onchange="readURL_unit(this);" hidden accept="image/jpg, image/jpeg, image/png">
    
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success col-sm-2" type="button" onclick="tambah_form_foto_unit()" hidden>
                        <span class="material-icons">add</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="button_send">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

    @if(isset($_GET['search']))
        $('#search').val('{{ $_GET["search"] }}')
    @endif
    var banyak_penerimaan_unit = 1;
    var banyak_unit = 1;
    var banyak_isi_unit;
    var isi_penerimaan_unit = [];
    var isi_unit = [];
    var unit = [];

    var select_id_karyawan_penerima = $("#id_karyawan_penerima").selectize();
    
    
    
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
                window.location.href = '{{ url("/receive_item/delete") }}/'+id;
            }
            // else if (result.isDenied) {
            //     Swal.fire('Changes are not saved', '', 'info')
            // }
        })
    }
    
    function hapus_unit(id) {
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus Unit ini?',
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
                window.location.href = '{{ url("receive_item/unit/delete") }}/'+id;
            }
            // else if (result.isDenied) {
            //     Swal.fire('Changes are not saved', '', 'info')
            // }
        })
    }

    function tambah() {
        //banyak_penerimaan_unit = 1;
        //banyak_unit = 1;
        //banyak_isi_unit;
        //isi_penerimaan_unit = [];
        //isi_unit = [];
        //unit = [];
        $('#exampleModalLabel').text('Tambah Receive Item');
        $('#form').attr('action', '{{ url("/receive_item") }}');
        $('.form-control').val('');
        $('#modal_spinner').hide();
        $('#modal_body').show();
        $('#button_send').show();
        $('#show_status_pdi').show();
        $('#status_pdi').prop('required',true);
        //$('#isi_unit').show();
        $('#cabang').empty();
        $('#cabang').append(`
            @foreach($cabang as $v)
            <option>{{ $v->nama }}</option>
            @endforeach
        `)
        select_id_karyawan_penerima[0].selectize.setValue('');
        reload_foto();
    }
    function tambah_unit(id) {
        $('#exampleModalLabel').text('Tambah Receive Item');
        $('#form_unit').attr('action', '{{ url("/receive_item/unit") }}/'+id);
        $('.form-control').val('');
        $('#modal_spinner').hide();
        $('#modal_body').show();
        $('#button_send').show();
        //select_id_karyawan_penerima[0].selectize.setValue('');
        reload_foto();
    }

    function edit(id) {
        banyak_penerimaan_unit = 1;
        banyak_unit = 1;
        banyak_isi_unit;
        isi_penerimaan_unit = [];
        isi_unit = [];
        unit = [];
        $('#exampleModalLabel').text('Edit Receive Item');
        $('#form').attr('action', '{{ url("receive_item")}}/' + id);
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
        axios.get('{{ url("/receive_item") }}/' + id)
            .then(function(response) {
                const receive_item = response.data.receive_item;
                $('#perusahaan').val(receive_item.perusahaan);
                $('#cabang').val(receive_item.cabang);
                $('#tanggal').val(receive_item.tanggal);
                select_id_karyawan_penerima[0].selectize.setValue(receive_item.id_karyawan_penerima);
                $('#nama_supplier').val(receive_item.nama_supplier);
                $('#nama_sopir').val(receive_item.nama_sopir);
                $('#telepon_sopir').val(receive_item.telepon_sopir);
                $('#no_polisi_truk').val(receive_item.no_polisi_truk);

                $('#isi_unit').hide();
                reload_foto();
                var foto = receive_item.images;
                if (foto != null) {
                    var str = foto.split("|");

                    for (var i = 0; i < str.length; i++) {
                        var x = i + 1;
                        $('#img_penerimaan_unit_' + x).attr('src', '{{ url("/image_penerimaan_unit/")}}/' + str[i] + '');
                        $('#img_penerimaan_unit_' + x).addClass("card p-2 img-list");

                        $('#input_foto_penerimaan_unit_' + x).val(str[i]);
                        if (isi_penerimaan_unit.includes(x) === false) {
                            isi_penerimaan_unit.push(x);
                            tambah_form_foto_penerimaan_unit();
                        }
                        var no_button_hapus = banyak_penerimaan_unit - 1;
                        $("#button_hapus_foto_penerimaan_unit_" + no_button_hapus).removeClass("hide");
                        // banyak_penerimaan_unit += 1;

                    }
                }

                $('#modal_spinner').hide();
                $('#modal_body').show();
                $('#button_send').show();
            });

    }
    function edit_unit(id) {
        banyak_unit = 1;
        isi_unit = [];
        $('#tambahModalLabel').text('Edit Unit');
        $('#form_unit').attr('action', '{{ url("receive_item/unit/edit")}}/' + id);
        $('.form-control').val('');
        $('#modal_spinner').show();
        $('#modal_body').hide();
        $('#button_send').hide();
        reload_foto_unit();
        
        axios.get('{{ url("/unit") }}/' + id)
            .then(function(response) {
                const unit = response.data.unit;
                $('#no_seri_unit').val(unit.no_seri_unit);
                $('#no_engine').val(unit.no_engine);
                $('#model_unit').val(unit.model_unit);
                $('#no_buku_warranty').val(unit.no_buku_warranty);
                $('#jabatan').val(unit.jabatan);
                $('#show_status_pdi').hide();
                $('#status_pdi').prop('required',false);
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

                        // var no_button_hapus = banyak_unit - 1;
                        $("#button_hapus_foto_unit_" + banyak_unit).removeClass("hide");
                        // banyak_unit += 1;
                    }
                }
                $('#modal_spinner').hide();
                $('#modal_body').show();
                $('#button_send').show();
            });

    }

    function reload_foto() {
        $('#foto_penerimaan_unit').empty();
        $('#foto_penerimaan_unit').html(`
            <div class="container-image" id="wrap_foto_penerimaan_unit_1">
                <img class="card img-list" id="img_penerimaan_unit_1" src="{{ URL::asset('images/add.png') }}" alt="your image" onclick="javascript:document.getElementById('input_penerimaan_unit_1').click();" />
                <div class="right">
                    <a class="text-danger text-shadow shadow hide" role="button" id="button_hapus_foto_penerimaan_unit_1" class="hide" onclick="hapus_foto_penerimaan_unit(1)">
                        <i class="material-icons">close</i>
                    </a>
                </div>
                <input type="file" name="foto_penerimaan_unit[]" class="form-control col-sm-10" id="input_penerimaan_unit_1" onchange="readURL_penerimaan_unit(this);" hidden accept="image/jpg, image/jpeg, image/png">
                <input type="text" name="input_foto_penerimaan_unit[]" class="form-control col-sm-10" id="input_foto_penerimaan_unit_1" hidden>
            </div>
            <button class="btn btn-success col-sm-2" type="button" onclick="tambah_form_foto_penerimaan_unit()" hidden><span class="material-icons">add</span></button>
        `);
    }
    function reload_foto_unit() {
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

    function lihat_pengirim(id) {
        $('.spinner-border').show();
        $('.pengirim').hide();
        axios.get('{{ url("/receive_item/pengirim") }}/' + id)
            .then(function(response) {
                const pengirim = response.data.pengirim;
                $('.spinner-border').hide();
                $('.pengirim').show();
                $('#detail_nama_sopir').text(pengirim.nama_sopir);
                $('#detail_telepon_sopir').text(pengirim.telepon_sopir);
                $('#detail_no_polisi_truk').text(pengirim.no_polisi_truk);
            });
    }

    function lihat_penerima(id) {
        $('.spinner-border').show();
        $('.penerima').hide();
        axios.get('{{ url("/receive_item/penerima") }}/' + id)
            .then(function(response) {
                const penerima = response.data.penerima;
                $('.spinner-border').hide();
                $('.penerima').show();
                $('#detail_nama_penerima').text(penerima.nama);
                $('#detail_telepon_penerima').text(penerima.telepon_1);
                $('#detail_alamat_penerima').text(penerima.alamat);
            });
    }

    function lihat_unit(id_penerimaan_unit) {
        $('#detail_unit').empty();
        $('#detail_unit').append(`
                    <tr class="text-center">
                        <td colspan="5">Loading . . .</th>
                    </tr>
                    `);

        axios.get('{{ url("/receive_item/unit") }}/' + id_penerimaan_unit)
            .then(function(response) {
                const unit = response.data.unit;
                $('#detail_unit').empty();
                if(unit.length > 0){
                    for (i = 0; i < unit.length; i++) {
                        var no = i + 1;
                        var image_unit = '';
                        if(unit[i].images_unit){
                            var str = unit[i].images_unit.split("|");
                            for (var j = 0; j < str.length; j++) {
                                image_unit += '<a class="btn btn-sm" data-toggle="modal" data-target="#lihatFotoModal" onclick="zoom_foto(`{{ url("/image_unit/")}}/' + str[j] + '`);"><img class="card img-list" src="{{ url("/image_unit/")}}/' + str[j] + '" width="100px"></a>';
                            }
                        }
                        
                        $('#detail_unit').append(`
                        <tr>
                            <td id="action_${unit[i].no_seri_unit}">
                            </td>
                            <td>${unit[i].no_seri_unit}</td>
                            <td>${unit[i].model_unit}</td>
                            <td>${unit[i].no_buku_warranty}</td>
                            <td>${image_unit}</td>
                        </tr>
                        `);

                        if(
                            ('{{ auth()->user()->can("update_unit_palembang") }}' && unit[i].cabang == 'PLM') ||
                            ('{{ auth()->user()->can("update_unit_lampung") }}' && unit[i].cabang == 'LMP') ||
                            ('{{ auth()->user()->can("update_unit_bengkulu") }}' && unit[i].cabang == 'BKL') ||
                            ('{{ auth()->user()->can("update_unit_ntt") }}' && unit[i].cabang == 'NTT') ||
                            ('{{ auth()->user()->can("update_unit_ntb") }}' && unit[i].cabang == 'NTB') ||
                            ('{{ auth()->user()->can("update_unit_jambi") }}' && unit[i].cabang == 'JMB') 
                        ){
                            $('#action_'+unit[i].no_seri_unit).append(`<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahModal" onclick="edit_unit(${unit[i].id})">Edit</a>`);
                        }
                        
                        if(
                            ('{{ auth()->user()->can("delete_unit_palembang") }}' && unit[i].cabang == 'PLM') ||
                            ('{{ auth()->user()->can("delete_unit_lampung") }}' && unit[i].cabang == 'LMP') ||
                            ('{{ auth()->user()->can("delete_unit_bengkulu") }}' && unit[i].cabang == 'BKL') ||
                            ('{{ auth()->user()->can("delete_unit_ntt") }}' && unit[i].cabang == 'NTT') ||
                            ('{{ auth()->user()->can("delete_unit_ntb") }}' && unit[i].cabang == 'NTB') ||
                            ('{{ auth()->user()->can("delete_unit_jambi") }}' && unit[i].cabang == 'JMB') 
                        ){
                            $('#action_'+unit[i].no_seri_unit).append(`<a href="#" class="text-danger" onclick="hapus_unit(${unit[i].id});">Delete</a>`);
                        }
                    }
                }else{
                    $('#detail_unit').append(`
                        <tr>
                            <td colspan='5' class="text-center">Belum Ada Unit</td>
                        </tr>
                        `);
                }
                
            });
    }

    function lihat_foto(foto, posisi) {
        $('#foto').empty();
        var str = foto.split("|");
        for (var i = 0; i < str.length; i++) {
            if (str[i] != null || str[i] != '') {
                $('#foto').append('<img class="mb-3" width="500px" src="{{ url("/image_penerimaan_unit/")}}/' + str[i] + '">');
            }
        }

    }

    function readURL_penerimaan_unit(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var id = input.id.split("_");
            reader.onload = function(e) {
                $('#img_penerimaan_unit_' + id[3]).attr('src', e.target.result);
                $('#img_penerimaan_unit_' + id[3]).addClass("card p-2 img-list");
            }

            reader.readAsDataURL(input.files[0]);
            if (isi_penerimaan_unit.includes(id[3]) === false) {
                isi_penerimaan_unit.push(id[3]);
                tambah_form_foto_penerimaan_unit();
            }
            var no_button_hapus = banyak_penerimaan_unit - 1;
            $("#button_hapus_foto_penerimaan_unit_" + no_button_hapus).removeClass("hide");

        }

    }

    function readURL_unit(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var id = input.id.split("_");
            reader.onload = function(e) {
                $('#foto_unit_' + id[2]).attr('src', e.target.result);
                $('#img_unit_' + id[2]).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            var y = id[2];
            tambah_form_foto_unit();
            $("#button_hapus_foto_unit_"+ y).removeClass("hide");

        }
    }

    

    function tambah_form_foto_penerimaan_unit() {
        banyak_penerimaan_unit++;
        $('#foto_penerimaan_unit').append(`<div class = "container-image"id = "wrap_foto_penerimaan_unit_${banyak_penerimaan_unit}" ><img class = "card img-list" id = "img_penerimaan_unit_${banyak_penerimaan_unit}"src = "{{ URL::asset("images/add.png") }}" alt = "your image" onclick = "javascript:document.getElementById('input_penerimaan_unit_${banyak_penerimaan_unit}').click();" / > <div class = "right"><a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_foto_penerimaan_unit_${banyak_penerimaan_unit}" class = "hide" onclick = "hapus_foto_penerimaan_unit(${banyak_penerimaan_unit})" > <i class="material-icons">close</i></a></div><input type = "file" name="foto_penerimaan_unit[]" class = "form-control col-sm-10"id = "input_penerimaan_unit_${banyak_penerimaan_unit}" onchange = "readURL_penerimaan_unit(this);"hidden accept="image/jpg, image/jpeg, image/png"><input type="text" name="input_foto_penerimaan_unit[]" class="form-control col-sm-10" id="input_foto_penerimaan_unit_${banyak_penerimaan_unit}" hidden></div>`);

    }

    function hapus_foto_penerimaan_unit(id) {
        $('#wrap_foto_penerimaan_unit_' + id).remove();

    }

    function tambah_form_foto_unit() {
        if (typeof banyak_isi_unit === 'undefined') {
            banyak_isi_unit = 2;
        } else {
            banyak_isi_unit++;
        }
        $('#foto_unit').append(`
            <div class="container-image" id="wrap_foto_unit_${banyak_isi_unit}">
                <img class="card img-list" id="foto_unit_${banyak_isi_unit}" src="{{ URL::asset("images/add.png") }}" alt="your image" onclick="javascript:document.getElementById('input_unit_${banyak_isi_unit}').click();" />
                <div class="right">
                    <a class="text-danger text-shadow shadow hide" role="button" id="button_hapus_foto_unit_${banyak_isi_unit}" class="hide" onclick="hapus_foto_unit(${banyak_isi_unit})">
                        <i class="material-icons">close</i>
                    </a>
                </div>
                <input type="file" name="foto_unit[]" class="form-control col-sm-10" id="input_unit_${banyak_isi_unit}" onchange="readURL_unit(this);" hidden accept="image/jpg, image/jpeg, image/png">
            </div>`);
    }

    function hapus_foto_unit(x) {
        $('#wrap_foto_unit_' + x).remove();
    }


    function hapus_form_foto_unit(id) {
        $(".form_foto_unit_" + id).last().remove();
        if ($('#foto_unit_' + id).children().length > 1) {
            $(".form_foto_unit_" + id).last().append('<button class="btn btn-danger col-sm-2 button_hapus_form_foto_unit_' + id + '" type="button" onclick="hapus_form_foto_unit(' + id + ')"><span class="material-icons">remove<span></button>');
        }
    }
</script>
@endsection