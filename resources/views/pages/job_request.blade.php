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
                                @canany(['create_job_request_palembang', 'create_job_request_lampung', 'create_job_request_bengkulu', 'create_job_request_ntt', 'create_job_request_ntb', 'create_job_request_jambi'])
                                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                                    onclick="tambah();">
                                    Tambah Job Request
                                </button>
                                @endcan
                            </div>
                            <div class="col-sm-4">
                                <form action="{{ url('/job_request') }}">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                                            aria-describedby="button-addon2" name="search" id="search">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit">Search</button>
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
                                        <th scope="col" class="sort" data-sort="no_job_request">No Job Request</th>
                                        <th scope="col" class="sort" data-sort="cabang">Cabang</th>
                                        <th scope="col" class="sort" data-sort="tanggal">Tanggal Request</th>
                                        <th scope="col" class="sort" data-sort="tanggal">Tanggal Berangkat</th>
                                        <th scope="col" class="sort" data-sort="service">Service</th>
                                        <th scope="col" class="sort" data-sort="no_seri_unit">No Seri Unit</th>
                                        <th scope="col" class="sort" data-sort="model_unit">Model Unit</th>
                                        <th scope="col" class="sort" data-sort="tipe">Tipe</th>
                                        <th scope="col" class="sort" data-sort="detail" hidden>Detail</th>
                                        <th scope="col" class="sort" data-sort="cabang" hidden>Cabang</th>
                                        <th scope="col" class="sort" data-sort="permasalahan" hidden>Permasalahan</th>
                                        <th scope="col" class="sort" data-sort="status" hidden>Status</th>
                                        <th scope="col" class="sort" data-sort="nama_konsumen" hidden>Nama Konsumen
                                        </th>
                                        <th scope="col" class="sort" data-sort="perkiraan_hourmeter" hidden>Perkiraan
                                            Hourmeter</th>
                                        <th scope="col" class="sort" data-sort="lokasi">Lokasi</th>
                                        <th scope="col" class="sort" data-sort="status">Status</th>
                                        <th scope="col" class="sort" data-sort="mekanik">Mekanik</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($job_request as $v)
                                        <tr id="{{ $v->id }}">
                                            <td>
                                                @if(
                                                    (auth()->user()->can('update_job_request_palembang') && $v->cabang == 'PLM') ||
                                                    (auth()->user()->can('update_job_request_lampung') && $v->cabang == 'LMP') ||
                                                    (auth()->user()->can('update_job_request_bengkulu') && $v->cabang == 'BKL') ||
                                                    (auth()->user()->can('update_job_request_ntt') && $v->cabang == 'NTT') ||
                                                    (auth()->user()->can('update_job_request_ntb') && $v->cabang == 'NTB') ||
                                                    (auth()->user()->can('update_job_request_jambi') && $v->cabang == 'JMB') 
                                                )
                                                <a href="#" class="btn btn-primary text-white btn-sm" data-toggle="modal" data-target="#exampleModal"
                                                    onclick="edit(`{{ $v->id }}`);"><i class="fa fa-pen text-white"></i> Edit</a>
                                                @endif
                                                @if(
                                                    (auth()->user()->can('delete_job_request_palembang') && $v->cabang == 'PLM') ||
                                                    (auth()->user()->can('delete_job_request_lampung') && $v->cabang == 'LMP') ||
                                                    (auth()->user()->can('delete_job_request_bengkulu') && $v->cabang == 'BKL') ||
                                                    (auth()->user()->can('delete_job_request_ntt') && $v->cabang == 'NTT') ||
                                                    (auth()->user()->can('delete_job_request_ntb') && $v->cabang == 'NTB') ||
                                                    (auth()->user()->can('delete_job_request_jambi') && $v->cabang == 'JMB') 
                                                )
                                                <a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus(`{{ $v->id }}`);"><i class="fa fa-trash text-white"></i> Delete</a>
                                                @endif
                                            </td>
                                            <td>{{ $v->no_job_request }}</td>
                                            <td>{{ $v->cabang }}</td>
                                            <td>{{ date('d-m-Y', strtotime($v->tanggal)) }}</td>
                                            <td>@if($v->tanggal_berangkat) {{ date('d-m-Y', strtotime($v->tanggal_berangkat)) }} @endif</td>
                                            <td>
                                                @canany(['read_service_palembang', 'read_service_lampung', 'read_service_bengkulu', 'read_service_ntt', 'read_service_ntb', 'read_service_jambi'])
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#lihatModal"
                                                    onclick="lihat({{ $v->id }})">Lihat</button>
                                                @endcan
                                                @canany(['create_service_palembang', 'create_service_lampung', 'create_service_bengkulu', 'create_service_ntt', 'create_service_ntb', 'create_service_jambi'])
                                                @if( $v->status != 'Done' && $v->status != 'Cancelled')
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#tambahServiceModal"
                                                    onclick="tambah_service({{ $v->id }})">+</button>
                                                @endif
                                                @endcan
                                            </td>
                                            <td>{{ $v->no_seri_unit }} - @if($v->nama_konsumen) {{ $v->nama_konsumen }} @else Stock Baru @endif </td>
                                            <td>{{ $v->model_unit }}</td>
                                            <td>{{ $v->tipe }}</td>
                                            <td hidden>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#detailModal"
                                                    onclick="detail({{ $v->id }})">Detail</button>
                                            </td>
                                            <td hidden>{{ $v->cabang }}</td>
                                            <td hidden>{{ $v->permasalahan }}</td>
                                            <td hidden>{{ $v->status }}</td>
                                            <td hidden>{{ $v->nama_konsumen }}</td>
                                            <td hidden>{{ $v->perkiraan_hourmeter }}</td>
                                            <td>{{ $v->lokasi }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#lihatStatusModal"
                                                    onclick="lihat_status({{ $v->id }})">
                                                    <i class="fa fa-eye text-white"></i>
                                                </button>
                                                @if( $v->status != 'Done' && $v->status != 'Cancelled')
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" data-boundary="viewport">
                                                        {{ $v->status }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Open');" data-toggle="modal" data-target="#jobRequestStatusModal">Open</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'On Follow Up');" data-toggle="modal" data-target="#jobRequestStatusModal">On Follow Up</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'On Workshop Progress');" data-toggle="modal" data-target="#jobRequestStatusModal">On Workshop Progress</a> 
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Pending Sparepart');" data-toggle="modal" data-target="#jobRequestStatusModal">Pending Sparepart</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Pending Payment');" data-toggle="modal" data-target="#jobRequestStatusModal"c>Pending Payment</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Claim Warranty');" data-toggle="modal" data-target="#jobRequestStatusModal">Claim Warranty</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Done');" data-toggle="modal" data-target="#jobRequestStatusModal">Done</a>
                                                        <a class="dropdown-item" href="#" onclick="status_job_request({{ $v->id }}, 'Cancelled');" data-toggle="modal" data-target="#jobRequestStatusModal">Cancelled</a>
                                                    </div>
                                                </div>
                                                @else
                                                    {{ $v->status }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($v->nama_mekanik != '')
                                                {{ $v->nama_mekanik }}
                                                
                                                <!--<a href="#" data-toggle="modal" data-target="#detailMekanikModal" onclick="lihat_mekanik({{ $v->id_mekanik }});">{{ $v->nama_mekanik }}</a>-->
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $job_request->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ url('/job_request') }}" id="form" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Job Request</h5>
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
                            <label for="cabang">Cabang <span class="text-danger">*</span></label>
                            <select class="form-control" name="cabang" id="cabang" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_berangkat">Tanggal Berangkat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_berangkat" name="tanggal_berangkat">
                        </div>
                        <div class="form-group">
                            <label for="no_seri_unit">Nomor Seri Unit <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="No Seri Unit"
                                    name="no_seri_unit" id="no_seri_unit"  readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" id="button_unit"
                                        onclick="browse_unit()">Search</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipe">Tipe <span class="text-danger">*</span></label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option selected disabled hidden>Choose here</option>
                                @foreach ($tipe as $v)
                                    <option>{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="pemilik_baru" style="display:none">
                            <label for="konsumen">Pemilik Baru</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Konsumen"
                                    name="konsumen" id="konsumen"  readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" id="button_konsumen"
                                        onclick="browse_konsumen()">Search</button>
                                </div>
                            </div>
                            <input type="text" class="form-control" placeholder="Konsumen"
                                    name="id_konsumen" id="id_konsumen" hidden style="display:none;">
                        </div>
                        <div class="form-group">
                            <label for="perkiraan_hourmeter">Perkiraan Hourmeter <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="perkiraan_hourmeter"
                                name="perkiraan_hourmeter" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                        </div>
                        <div class="form-group">
                            <label for="permasalahan">Permasalahan/Keluhan</label>
                            <textarea class="form-control" id="permasalahan" name="permasalahan" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="id_nama_mekanik">Mekanik <span class="text-danger">*</span></label>
                            <select type="text" class="form-control" id="id_nama_mekanik" name="id_nama_mekanik"
                                required>
                                <option value="">Choose here</option>
                                @foreach ($karyawan as $row)
                                    <option value="{{ $row->id }}_{{ $row->nama }}">{{ $row->nama }} -
                                        {{ $row->alamat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_job_request">Foto</label>
                            <div class="row" id="foto_job_request">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="button_send_service">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahServiceModal" tabindex="-1" aria-labelledby="tambahServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ url('/job_request/service') }}" id="form_service" autocomplete="off"
                    enctype="multipart/form-data" onsubmit="return validation();">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahServiceModalLabel">Tambah Job Request</h5>
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
                                <option value="">Choose here</option>
                                @foreach ($karyawan as $row)
                                    <option value="{{ $row->id }}_{{ $row->nama }}">{{ $row->nama }} -
                                        {{ $row->alamat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_nama_teknisi_2">Mekanik 2</label>
                            <select type="text" class="form-control" id="id_nama_teknisi_2" name="id_nama_teknisi_2">
                                <option value="">Choose here</option>
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
                                <option value="">Choose here</option>
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
                            <label for="foto_service">Foto Service Bill <span class="text-danger">*</span></label>
                            <div class="row" id="foto_service">
                                <div class="container-image" id="wrap_foto_service_1">
                                    <img class="card img-list" id="img_service_1"
                                        src="{{ URL::asset('images/add.png') }}" alt="your image"
                                        onclick="javascript:document.getElementById('input_service_type_1').click();" />
                                    <div class="right">
                                        <a class="text-danger text-shadow shadow hide" role="button"
                                            id="button_hapus_foto_service_1" class="hide"
                                            onclick="hapus_foto_service(1)">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </div>
                                    <input type="file" name="foto_service[]" class="form-control col-sm-10" onchange="readURL_service(this);" hidden
                                        accept="image/jpg, image/jpeg, image/png" id="input_service_type_1">
                                    <input type="text" name="input_foto_service[]" class="form-control col-sm-10"
                                        id="input_foto_service_1" hidden>
                                </div>
                                <button class="btn btn-success col-sm-2" type="button"
                                    onclick="tambah_form_foto_service()" hidden><span
                                        class="material-icons">add</span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file_service">File</label>
                            <div class="row" id="file_service">
                                <div class="container-image" id="wrap_file_service_1">
                                    <img class="card img-list" id="file_service_1"
                                        src="{{ URL::asset('images/add_pdf.png') }}" alt="your file"
                                        onclick="javascript:document.getElementById('input_file_service_type_1').click();" />
                                    <label id="label_file_service_1"></label>
                                    <div class="right">
                                        <a class="text-danger text-shadow shadow hide" role="button"
                                            id="button_hapus_file_service_1" class="hide"
                                            onclick="hapus_file_service(1)">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </div>
                                    <input type="file" name="file_service[]" class="form-control col-sm-10" onchange="readURL_file_service(this);" hidden
                                        accept="application/pdf" id="input_file_service_type_1">
                                    <input type="text" name="input_file_service[]" class="form-control col-sm-10"
                                        id="input_file_service_1" hidden>
                                </div>
                                <button class="btn btn-success col-sm-2" type="button"
                                    onclick="tambah_form_file_service()" hidden><span
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

    <div class="modal fade" id="lihatModal" aria-labelledby="lihatModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lihatModalLabel">Lihat Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="notif_lihat_service">Belum Ada Service</div>
                    <div class="table-responsive" id="table_service">
                        <table class="table align-items-center ">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Tanggal Service</th>
                                    <th scope="col">Tipe</th>
                                    <th scope="col">No Seri Unit</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Mekanik</th>
                                    <th scope="col">Driver</th>
                                    <th scope="col">No Service Bill</th>
                                    <th scope="col">No Service Bill Manual</th>
                                    <th scope="col">No Invoice</th>
                                    <th scope="col">Permasalahan</th>
                                    <th scope="col">Hourmeter</th>
                                    <th scope="col">Penyebab</th>
                                    <th scope="col">Tindakan Perbaikan</th>
                                    <th scope="col">Jasa Service</th>
                                    <th scope="col">Sparepart</th>
                                    <th scope="col">Transport</th>
                                    <th scope="col">Garansi Service</th>
                                    <th scope="col">Foto</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="lihat">
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center" id="loading">
                        Loading...
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lihatStatusModal" aria-labelledby="lihatStatusModal" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lihatStatusModalLabel">Lihat Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="notif_lihat_status">Belum Ada Status</div>
                    <div class="table-responsive" id="table_status">
                        <table class="table align-items-center ">
                            <thead>
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="lihat_status">
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center" id="loading_status">
                        Loading...
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="job_requestImportModal" tabindex="-1" role="dialog"
        aria-labelledby="job_requestImportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" action="{{ url('import_job_request') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <h5 class="modal-title mb-4" id="job_requestImportModalLabel">Import Job Request Data</h5>
                        <div class="form-group">
                            <input type="file" name="file" required>
                        </div>
                        <div class="row d-flex justify-content-end">
                            <input type="text" class="form-control" name="id" id="input_id" hidden>
                            <button type="button" class="btn btn-white text-danger mx-1"
                                data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info ml-1">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="jobRequestStatusModal" tabindex="-1" aria-labelledby="jobRequestStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" id="form_job_request_status" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="jobRequestStatusModalLabel">Status Job Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!--<div class="modal-body" id="modal_spinner_job_request_status">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>-->
                    <!--<div class="modal-body" id="modal_body_job_request_status" style="display:none;">-->
                    <div class="modal-body" id="modal_body_job_request_status">
                        <div class="form-group">
                            <label for="tanggal_status">Tanggal Status <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_status" name="tanggal_status" required>
                        </div>
                        <div class="form-group">
                            <label for="namaPemilikJobRequest">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status">
                                <option selected disabled hidden>Choose...</option>
                                <option>Open</option>
                                <option>On Follow Up</option>
                                <option>On Workshop Progress</option>
                                <option>Pending Sparepart</option>
                                <option>Pending Payment</option>
                                <option>Claim Warranty</option>
                                <option>Done</option>
                                <option>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan">
                            </textarea>
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
    
    <div class="modal fade" id="detailMekanikModal" tabindex="-1" aria-labelledby="detailMekanikModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailMekanikModalLabel">Detail Mekanik</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="p-0 m-0">Nama Mekanik</p>
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="text-dark pt-1 pb-3 penerima" id="detail_nama_mekanik" style="display:none;"></h5>
    
                    <p class="p-0 m-0">Telepon Mekanik</p>
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="text-dark pt-1 pb-3 penerima" id="detail_telepon_mekanik" style="display:none;"></h5>
    
                    <p class="p-0 m-0">Alamat Mekanik</p>
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5 class="text-dark pt-1 pb-3 penerima" id="detail_alamat_mekanik" style="display:none;"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function validation() {
            
            var image = $('*[id^="input_service_type"]');
            
            if(image.val()!= null && image.val()!= ''){
                return true;
            }else{
                alert("Foto Service Bill harus diinput");
                return false;
            }
            
        }
        @if(isset($_GET['search']))
            $('#search').val('{{ $_GET["search"] }}')
        @endif
        var select_id_nama_mekanik = $("#id_nama_mekanik").selectize();
        var select_id_nama_teknisi_1 = $("#id_nama_teknisi_1").selectize();
        var select_id_nama_teknisi_2 = $("#id_nama_teknisi_2").selectize();
        var select_id_nama_driver = $("#id_nama_driver").selectize();
        
        function lihat_mekanik(id) {
            $('.spinner-border').show();
            $('.mekanik').hide();
            axios.get('{{ url("/job_request/mekanik") }}/' + id)
                .then(function(response) {
                    const mekanik = response.data.mekanik;
                    $('.spinner-border').hide();
                    $('.mekanik').show();
                    $('#detail_nama_mekanik').text(mekanik.nama);
                    $('#detail_telepon__mekanik').text(mekanik.telepon_1);
                    $('#detail_alamat__mekanik').text(mekanik.alamat);
                });
        }
    
        function status_job_request(id, status) {
            var data = $('tr#' + id);
            $('#form_job_request_status').attr('action', '{{ url("/job_request/status") }}/' + id);
    
            var today = new Date();
            var year = today.getFullYear();
            var month = (today.getMonth() + 1);
            if (month < 10) {
                month = '0' + month;
            } else {
                month = month;
            }
            if (date < 10) {
                date = '0' + today.getDate();
            } else {
                date = today.getDate();
            }
            var date = year + '-' + month + '-' + date;
            $('#tanggal_status').val(date);
            $('#status').val(status);
        }
        function validateForm(e){
            if(!$('#no_seri_unit').val()){
                Swal.fire(
                    'Oops...',
                    'No Seri Unit Harus Di Search Terlebih Dahulu',
                    'error'
                )
                return false;
            }
            
            return true;
        }
        
        $('#tipe').on('change', function() {
            if( this.value == 'Serah Terima'){
                $('#pemilik_baru').show();
            }else{
                $('#pemilik_baru').hide();
            }
        });
        
        var banyak_job_request = 1;
        var isi_job_request = [];
        var banyak_service = 1;
        var banyak_file_service = 1;
        var isi_service = [];
        var isi_file_service = [];

        function tambah() {
            $('#exampleModalLabel').text('Tambah Job Request');
            $('#form').attr('action', '{{ url('/job_request') }}');
            $('.form-control').val('');
            $('#modal_spinner').hide();
            $('#modal_body').show();
            $('#button_send').show();
            $('#cabang').append(`
                @foreach($cabang as $v)
                <option>{{ $v->nama }}</option>
                @endforeach
            `)
            $('#button_unit').attr('onclick','browse_unit("create")')
            reload_foto();
        }

        function tambah_service(id) {
            $('#tambahServiceModalLabel').text('Tambah Service');
            $('#form_service').attr('action', '{{ url('/job_request/service') }}/' + id);
            $('.form-control').val('');
            $('#modal_spinner_service').hide();
            $('#modal_body_service').show();
            $('#button_send_service').show();
            // reload_foto();
        }

        function edit(id) {
            banyak_job_request = 1;
            isi_job_request = [];
            $('#exampleModalLabel').text('Edit Job Request');
            $('#form').attr('action', '{{ url('/job_request') }}/' + id);
            $('.form-control').val('');
            $('#modal_spinner').show();
            $('#modal_body').hide();
            $('#button_send').hide();
            $('#cabang').append(`
                @foreach($cabang_edit as $v)
                <option>{{ $v->nama }}</option>
                @endforeach
            `)
            $('#button_unit').attr('onclick','browse_unit("update")')
            axios.get('{{ url("/job_request") }}/' + id)
                .then(function(response) {
                    const job_request = response.data.job_request;
                    const no_seri_unit = response.data.no_seri_unit;
                    $('#cabang').val(job_request.cabang);
                    $('#tanggal').val(job_request.tanggal);
                    if(job_request.tanggal_berangkat){
                        $('#tanggal_berangkat').val(job_request.tanggal_berangkat);
                    }
                    $('#no_seri_unit').show();
                    $('#no_seri_unit').attr('readonly',true);
                    $('#no_seri_unit').val(job_request.no_seri_unit);
                    $('#id_konsumen').val(job_request.id_konsumen);
                    $('#konsumen').show();
                    $('#konsumen').attr('readonly',true);
                    $('#konsumen').val(job_request.nama_konsumen);
                    $('#tipe').val(job_request.tipe);
                    $('#status').val(job_request.status);
                    $('#lokasi').val(job_request.lokasi);
                    $('#permasalahan').val(job_request.permasalahan);
                    $('#perkiraan_hourmeter').val(job_request.perkiraan_hourmeter);
                    select_id_nama_mekanik[0].selectize.setValue(job_request.id_mekanik+'_'+job_request.nama_mekanik);
                    $('#modal_spinner').hide();
                    $('#modal_body').show();
                    $('#button_send').show();

                    reload_foto();
                    var foto = job_request.images;
                    if (foto) {
                        var str = foto.split("|");

                        for (var i = 0; i < str.length; i++) {
                            var x = i + 1;
                            $('#img_job_request_' + x).attr('src', '{{ url('/image_job_request/') }}/' + str[i] + '');
                            $('#img_job_request_' + x).addClass("card p-2 img-list");

                            $('#input_foto_job_request_' + x).val(str[i]);
                            if (isi_job_request.includes(x) === false) {
                                isi_job_request.push(x);
                                tambah_form_foto_job_request();
                            }
                            var no_button_hapus = banyak_job_request - 1;
                            $("#button_hapus_foto_job_request_" + no_button_hapus).removeClass("hide");
                            // banyak_job_request += 1;

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
                    window.location.href = '{{ url("job_request/delete") }}/'+id;
                }
                // else if (result.isDenied) {
                //     Swal.fire('Changes are not saved', '', 'info')
                // }
            })
        }
        function hapus_service(id){
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus Service ini?',
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
                    window.location.href = '{{ url("job_request/service/delete") }}/'+id;
                }
                // else if (result.isDenied) {
                //     Swal.fire('Changes are not saved', '', 'info')
                // }
            })
        }
        function lihat(id) {
            $('#lihat').empty();
            $('#notif_lihat_service').hide();
            $('#table_service').show();
            $('#loading').text('Loading...');
            axios.get('{{ url('/job_request/service') }}/' + id)
                .then(function(response) {
                    $('#loading').empty();
                    const service = response.data.service;
                    if(service.length > 0){
                        for (let i = 0; i < service.length; i++) {
    
                            var image_service = '';
                            if (service[i].images) {
                                var str = service[i].images.split("|");
                                for (var j = 0; j < str.length; j++) {
                                    if (str[j] != null || str[j] != '') {
                                        image_service +=
                                            '<a class="btn btn-sm" data-toggle="modal" data-target="#lihatFotoModal" onclick="zoom_foto(`{{ url('/image_service/') }}/' +
                                        str[j] + '`);"><img class="card img-list" src="{{ url('/image_service/') }}/' + str[
                                                j] + '" width="100px"></a>';
                                    }
                                }
                            }
                            var teknisi = service[i].nama_teknisi_1;
                            if(service[i].nama_teknisi_2){
                                teknisi += ' dan '+service[i].nama_teknisi_2;
                            }
                            $('#lihat').append(`
                            <tr id="detail_${service[i].id}">
                                <td id="action_${service[i].id}"></td>
                                <td>${service[i].tanggal}</td>
                                <td>${service[i].tipe}</td>
                                <td>${service[i].no_seri_unit} (${service[i].model_unit})</td>
                                <td>${service[i].jam_mulai} sd ${service[i].jam_selesai}</td>
                                <td>${teknisi} </td>
                                <td>${service[i].nama_driver}</td>
                                <td>${service[i].no_service_bill}</td>
                                <td>${service[i].no_service_bill_manual}</td>
                                <td>${service[i].no_invoice}</td>
                                <td>${service[i].permasalahan}</td>
                                <td>${service[i].hourmeter}</td>
                                <td>${service[i].penyebab}</td>
                                <td>${service[i].tindakan_perbaikan}</td>
                                <td>${format_ribuan(service[i].jasa_service)}</td>
                                <td>${format_ribuan(service[i].sparepart)}</td>
                                <td>${format_ribuan(service[i].transport)}</td>
                                <td>${service[i].garansi}</td>
                                <td>${image_service}</td>
                            </tr>`);

                            if(
                                ('{{ auth()->user()->can("update_service_palembang") }}' && service[i].cabang == 'PLM') ||
                                ('{{ auth()->user()->can("update_service_lampung") }}' && service[i].cabang == 'LMP') ||
                                ('{{ auth()->user()->can("update_service_bengkulu") }}' && service[i].cabang == 'BKL') ||
                                ('{{ auth()->user()->can("update_service_ntt") }}' && service[i].cabang == 'NTT') ||
                                ('{{ auth()->user()->can("update_service_ntb") }}' && service[i].cabang == 'NTB') ||
                                ('{{ auth()->user()->can("update_service_jambi") }}' && service[i].cabang == 'JMB') 
                            ){
                                $('#action_'+service[i].id).append(`<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambahServiceModal" onclick="edit_service(${service[i].id})"><i class="fa fa-pen text-white"></i> Edit</button>`);
                            }
                            
                            if(
                                ('{{ auth()->user()->can("delete_service_palembang") }}' && service[i].cabang == 'PLM') ||
                                ('{{ auth()->user()->can("delete_service_lampung") }}' && service[i].cabang == 'LMP') ||
                                ('{{ auth()->user()->can("delete_service_bengkulu") }}' && service[i].cabang == 'BKL') ||
                                ('{{ auth()->user()->can("delete_service_ntt") }}' && service[i].cabang == 'NTT') ||
                                ('{{ auth()->user()->can("delete_service_ntb") }}' && service[i].cabang == 'NTB') ||
                                ('{{ auth()->user()->can("delete_service_jambi") }}' && service[i].cabang == 'JMB') 
                            ){
                                $('#action_'+service[i].id).append(`<a href="#" class="btn btn-danger text-white btn-sm" onclick="hapus_service(${service[i].id});"><i class="fa fa-trash text-white"></i> Delete</a>`);
                            }
                        }
                    } else {
                        $('#notif_lihat_service').show();
                        $('#table_service').hide();
                    }
                });
        }
        
        function lihat_status(id) {
            $('#lihat_status').empty();
            $('#notif_lihat_status').hide();
            $('#table_status').show();
            $('#loading_status').text('Loading...');
            axios.get('{{ url('/job_request/status') }}/' + id)
                .then(function(response) {
                    $('#loading_status').empty();
                    const status_job_request = response.data.status_job_request;
                    console.log(status_job_request)
                    if(status_job_request.length > 0){
                        for (let i = 0; i < status_job_request.length; i++) {
                            $('#lihat_status').append(`
                            <tr>
                                <td>${status_job_request[i].tanggal}</td>
                                <td>${status_job_request[i].status}</td>
                                <td>${status_job_request[i].keterangan}</td>
                            </tr>`);
                        }
                    } else {
                        $('#notif_lihat_status').show();
                        $('#table_status').hide();
                    }
                });
        }

        function edit_service(id) {
            isi_service = [];
            $('#tambahServiceModalLabel').text('Edit Service');
            $('#form_service').attr('action', '{{ url("job_request/service/edit") }}/' + id);
            $('.form-control').val('');
            $('#modal_spinner_service').show();
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

                    reload_foto();
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
                    var file = service.file;
                    if (file) {
                        var str = file.split("|");

                        for (var i = 0; i < str.length; i++) {
                            var x = i + 1;
                            $('#file_service_' + x).attr('src', '{{ URL::asset("images/pdf.jpg") }}');
                            $('#label_file_service_' + x).text(str[i]);
                            $('#input_file_service_' + x).val(str[i]);
                            if (isi_file_service.includes(x) === false) {
                                isi_file_service.push(x);
                                tambah_form_file_service();
                            }
                            var no_button_hapus = banyak_file_service - 1;
                            $("#button_hapus_file_service_" + no_button_hapus).removeClass("hide");
                            // banyak_service += 1;

                        }
                    }
                    $('#modal_spinner').hide();
                    $('#modal_body').show();
                    $('#button_send').show();
                });
        }

        function reload_foto() {
            $('#foto_job_request').empty();
            $('#foto_job_request').html(`
            <div class="container-image" id="wrap_foto_job_request_1">
                <img class="card img-list" id="img_job_request_1" src="{{ URL::asset('images/add.png') }}" alt="your image" onclick="javascript:document.getElementById('input_job_request_1').click();" />
                <div class="right">
                    <a class="text-danger text-shadow shadow hide" role="button" id="button_hapus_foto_job_request_1" class="hide" onclick="hapus_foto_job_request(1)">
                        <i class="material-icons">close</i>
                    </a>
                </div>
                <input type="file" name="foto_job_request[]" class="form-control col-sm-10" id="input_job_request_1" onchange="readURL_job_request(this);" hidden accept="image/jpg, image/jpeg, image/png">
                <input type="text" name="input_foto_job_request[]" class="form-control col-sm-10" id="input_foto_job_request_1" hidden>
            </div>
            <button class="btn btn-success col-sm-2" type="button" onclick="tambah_form_foto_job_request()" hidden><span class="material-icons">add</span></button>
        `);
        }

        function tambah_form_foto_job_request() {
            banyak_job_request++;
            $('#foto_job_request').append(
                `<div class = "container-image"id = "wrap_foto_job_request_${banyak_job_request}" >
                    <img class = "card img-list" id = "img_job_request_${banyak_job_request}"src = "{{ URL::asset('images/add.png') }}" alt = "your image" onclick = "javascript:document.getElementById('input_job_request_${banyak_job_request}').click();" / >
                    <div class = "right">
                        <a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_foto_job_request_${banyak_job_request}" class = "hide" onclick = "hapus_foto_job_request(${banyak_job_request})" > 
                            <i class="material-icons">close</i>
                        </a>
                    </div>
                    <input type = "file" name="foto_job_request[]" class = "form-control col-sm-10"id = "input_job_request_${banyak_job_request}" onchange = "readURL_job_request(this);"hidden accept="image/jpg, image/jpeg, image/png">
                    <input type="text" name="input_foto_job_request[]" class="form-control col-sm-10" id="input_foto_job_request_${banyak_job_request}" hidden>
                </div>`
            );
        }

        function hapus_foto_job_request(id) {
            $('#wrap_foto_job_request_' + id).remove();

        }

        function readURL_job_request(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var id = input.id.split("_");
                reader.onload = function(e) {
                    $('#img_job_request_' + id[3]).attr('src', e.target.result);
                    $('#img_job_request_' + id[3]).addClass("card p-2 img-list");
                }

                reader.readAsDataURL(input.files[0]);
                if (isi_job_request.includes(id[3]) === false) {
                    isi_job_request.push(id[3]);
                    tambah_form_foto_job_request();
                }
                var no_button_hapus = banyak_job_request - 1;
                $("#button_hapus_foto_job_request_" + no_button_hapus).removeClass("hide");
            }
        }

        function browse_unit(method) {
            popupWindow("{{ url('unit/browse') }}/kosong/job_request/"+method, 'Search Unit', window, 600, 500);
        }
        
        function browse_konsumen() {
            popupWindow("{{ url('konsumen/browse') }}/kosong", 'Search Konsumen', window, 600, 500);
        }

        function tambah_form_foto_service() {
            banyak_service++;
            $('#foto_service').append(
                `<div class = "container-image"id = "wrap_foto_service_${banyak_service}" >
                    <img class = "card img-list" id="img_service_${banyak_service}" src = "{{ URL::asset('images/add.png') }}" alt = "your image" onclick = "javascript:document.getElementById('input_service_type_${banyak_service}').click();" / >
                    <div class = "right">
                        <a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_foto_service_${banyak_service}" class = "hide" onclick = "hapus_foto_service(${banyak_service})" >
                            <i class="material-icons">close</i>
                        </a>
                    </div>
                    <input type = "file" name="foto_service[]" class = "form-control col-sm-10" id="input_service_type_${banyak_service}" onchange = "readURL_service(this);"hidden accept="image/jpg, image/jpeg, image/png" >
                    <input type="text" name="input_foto_service[]" class="form-control col-sm-10" id="input_foto_service_${banyak_service}" hidden>
                </div>`
            );
        }
        function tambah_form_file_service() {
            banyak_file_service++;
            $('#file_service').append(
                `<div class = "container-image"id = "wrap_file_service_${banyak_file_service}" >
                    <img class = "card img-list" id="file_service_${banyak_file_service}" src = "{{ URL::asset('images/add_pdf.png') }}" alt = "your file" onclick = "javascript:document.getElementById('input_file_service_type_${banyak_file_service}').click();" / >
                    <label id="label_file_service_${banyak_file_service}"></label>
                    <div class = "right">
                        <a class = "text-danger text-shadow shadow hide" role = "button" id = "button_hapus_file_service_${banyak_file_service}" class = "hide" onclick = "hapus_file_service(${banyak_file_service})" >
                            <i class="material-icons">close</i>
                        </a>
                    </div>
                    <input type = "file" name="file_service[]" class = "form-control col-sm-10" id="input_file_service_type_${banyak_file_service}" onchange = "readURL_file_service(this);"hidden accept="application/pdf">
                    <input type="text" name="input_file_service[]" class="form-control col-sm-10" id="input_file_service_${banyak_file_service}" hidden>
                </div>`
            );
        }

        function hapus_foto_service(id) {
            $('#wrap_foto_service_' + id).remove();
        }
        function hapus_file_service(id) {
            $('#wrap_file_service_' + id).remove();
        }

        function readURL_service(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var id = input.id.split("_");
                reader.onload = function(e) {
                    $('#img_service_' + id[3]).attr('src', e.target.result);
                    $('#img_service_' + id[3]).addClass("card p-2 img-list");
                }

                reader.readAsDataURL(input.files[0]);
                if (isi_service.includes(id[3]) === false) {
                    isi_service.push(id[3]);
                    tambah_form_foto_service();
                }
                var no_button_hapus = banyak_service - 1;
                $("#button_hapus_foto_service_" + no_button_hapus).removeClass("hide");
            }
        }
        function readURL_file_service(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var id = input.id.split("_");
                reader.onload = function(e) {
                    $('#file_service_' + id[4]).attr('src', '{{ URL::asset("images/pdf.jpg") }}');
                    $('#label_file_service_'+id[4]).text(input.files[0].name)
                }

                reader.readAsDataURL(input.files[0]);
                if (isi_file_service.includes(id[4]) === false) {
                    isi_file_service.push(id[4]);
                    tambah_form_file_service();
                }
                var no_button_hapus = banyak_file_service - 1;
                $("#button_hapus_file_service_" + no_button_hapus).removeClass("hide");
            }
        }
    </script>
@endsection
