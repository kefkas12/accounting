@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .select2-container {
            width: 150px !important;
        }

        /* Ubah tombol dropdown selectpicker jadi mirip input bootstrap */
        .bootstrap-select .dropdown-toggle {
            border: 1px solid #ced4da !important; /* Border Bootstrap */
            border-radius: 0.25rem !important;     /* Radius Bootstrap */
            background-color: #fff !important;     /* Background putih */
            color: #495057 !important;             /* Warna teks Bootstrap */
            height: calc(1.5em + .5rem + 2px) !important; /* Tinggi form-control-sm */
            padding: .25rem .5rem !important;      /* Padding form-control-sm */
        }

        /* Placeholder abu-abu */
        .bootstrap-select .dropdown-toggle.bs-placeholder,
        .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
            color: #6c757d !important; /* Warna placeholder */
        }

        /* Hilangkan bayangan saat normal maupun hover */
        .bootstrap-select .dropdown-toggle,
        .bootstrap-select .dropdown-toggle:focus,
        .bootstrap-select .dropdown-toggle:hover {
            box-shadow: none !important;   /* Hilangkan shadow */
            outline: none !important;      /* Hilangkan outline biru */
            background-color: #fff !important; /* Tetap putih saat hover */
            border-color: #ced4da !important;  /* Border tetap sama */
        }
    </style>
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body border-0 text-sm">
                        <div class="form-row">
                            <div class="form-group col-md-9 pr-2">
                                <a href="{{ url('penjualan') }}">Penjualan</a>
                                <h2>Buat Penagihan Penjualan</h2>
                            </div>
                            <div class="form-group col-md-3 pr-2">
                                <select class="form-control" onchange="location = this.value;" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                    <option selected disabled hidden>Penagihan Penjualan</option>
                                    <option value="{{ url('penjualan/penagihan') }}">Penagihan Penjualan</option>
                                    <option value="{{ url('penjualan/penawaran') }}">Penawaran Penjualan</option>
                                    <option value="{{ url('penjualan/pemesanan') }}">Pemesanan Penjualan</option>
                                </select>
                            </div>
                        </div>
                        <form method="POST" id="insertForm"
                            @if(isset($pemesanan_penagihan))
                                action="{{ url('penjualan/pemesanan').'/penagihan/'.$penjualan->id }}" 
                            @elseif(isset($pengiriman_penagihan))
                                action="{{ url('penjualan/pengiriman').'/penagihan/'.$penjualan->id }}" 
                            @elseif(isset($penagihan)) 
                                action="{{ url('penjualan/penagihan').'/'.$penjualan->id }}" 
                            @else 
                                action="{{ url('penjualan/penagihan') }}" 
                            @endif
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @if(isset($pengiriman))
                            <div class="form-row border-bottom border-top border-left border-right text-center pt-3 mb-3">
                                @if(isset($pengiriman))
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Pengiriman</label> <br>
                                        <a href="{{ url('penjualan/detail').'/'.$penjualan->id }}">{{ $penjualan->no_str }}</a>
                                    </div>
                                </div>
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Pemesanan</label> <br>
                                        <a href="{{ url('penjualan/detail').'/'.$penjualan->id_pemesanan }}">{{ $penjualan->no_str_pemesanan }}</a>
                                    </div>
                                </div>
                                @elseif(isset($pemesanan))
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Pemesanan</label> <br>
                                        <a href="{{ url('penjualan/detail').'/'.$penjualan->id }}">{{ $penjualan->no_str }}</a>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Penawaran</label> <br>
                                        <a href="{{ url('penjualan/detail').'/'.$penjualan->id_penawaran }}">{{ $penjualan->no_str_penawaran }}</a>
                                    </div>
                                </div>
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No RFQ</label> <br>
                                        <span>
                                        @if($penjualan->no_rfq)
                                            {{ $penjualan->no_rfq }}
                                        @else
                                            -
                                        @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @elseif(isset($pemesanan))
                            <div class="form-row border-bottom border-top border-left border-right text-center pt-3 mb-3">
                                <div class="col-md-3 border-right">
                                    <div class="form-group">
                                        <label>No Pemesanan</label> <br>
                                        <a href="{{ url('penjualan/detail').'/'.$penjualan->id }}">{{ $penjualan->no_str }}</a>
                                    </div>
                                </div>
                                </div>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-3 pr-2">
                                    <label for="pelanggan">Pelanggan <span class="text-danger">*</span></label>
                                    <select class="selectpicker form-control form-control-sm" data-live-search="true" title="Pilih Pelanggan" id="pelanggan" name="pelanggan" onchange="alamat_pelanggan(this)" required @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                        @foreach ($pelanggan as $v)
                                            <option value="{{ $v->id }}">{{ $v->nama }} -
                                                {{ $v->nama_perusahaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email">
                                </div>
                                <div class="form-group col-md-3 pr-2">
                                    <label for="tanggal_transaksi">Tgl. Transaksi</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_transaksi"
                                        name="tanggal_transaksi" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}">
                                </div>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="alamat">Pilih Alamat</label>
                                    <select class="form-control form-control-sm" id="alamat" name="alamat">
                                        <option selected disabled value="">Nothing Selected</option>
                                    </select>
                                </label>
                                <label class="form-group col-md-3 pr-2">
                                    <label for="detail_alamat">Detail Alamat</label>
                                    <textarea class="form-control form-control-sm" name="detail_alamat" id="detail_alamat" rows="1"></textarea>
                                </label>
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <div class="form-check" >
                                        <input class="form-check-input" type="checkbox" id="info_pengiriman" name="info_pengiriman">
                                        <label class="form-check-label" for="info_pengiriman">
                                            Info Pengiriman
                                        </label>
                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-3 d-flex justify-content-end"> -->
                                    <!-- <h1><strong>Total &nbsp; <span id="total_faktur"> Rp 0,00</span></strong></h1> -->
                                <!-- </div> -->
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 pr-2" style="display:none">
                                    <div class="form-group info_pengiriman" style="display:none">
                                        <label for="tanggal_pengiriman">Tgl. Pengiriman</label>
                                        <input type="date" class="form-control form-control-sm" id="tanggal_pengiriman"
                                            name="tanggal_pengiriman" style="background-color: #ffffff !important;" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group info_pengiriman" style="display:none">
                                        <label class="alamat_pengiriman" style="display:none">Alamat Pengiriman</label>
                                        <textarea class="form-control form-control-sm" name="alamat_pengiriman" id="alamat_pengiriman" rows="1" style="display:none"></textarea>
                                    </div>
                                    <div class="form-check mb-4 text-sm" style="display:none">
                                        <input class="form-check-input" type="checkbox" id="sama_dengan_penagihan" name="sama_dengan_penagihan" checked>
                                        <label class="form-check-label" for="sama_dengan_penagihan">
                                            Sama dengan penagihan
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 pr-2" style="display:none">
                                    <label for="tanggal_jatuh_tempo">Tgl. Jatuh Tempo</label>
                                    <input type="date" class="form-control form-control-sm" id="tanggal_jatuh_tempo"
                                        name="tanggal_jatuh_tempo" style="background-color: #ffffff !important;" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                </div>
                                <div class="form-group col-md-3 pr-2" style="display:none">
                                    <label for="gudang">Gudang</label>
                                    <select class="form-control form-control-sm" id="gudang" name="gudang" @if(isset($pengiriman)) disabled @endif>
                                        <option selected disabled hidden>Pilih Gudang</option>
                                        @if(isset($gudang))
                                        @foreach($gudang as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }}</option>
                                        @endforeach
                                        @else
                                        <option disabled>No result found</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-md-3 pr-2 info_pengiriman" style="display:none">
                                    <label for="kirim_melalui">Kirim Melalui</label>
                                    <input type="text" class="form-control form-control-sm" id="kirim_melalui" name="kirim_melalui">
                                </div>
                                <div class="form-group col-md-3 pr-2 info_pengiriman" style="display:none">
                                    <label for="no_pelacakan">No. Pelacakan</label>
                                    <input type="text" class="form-control form-control-sm" id="no_pelacakan" name="no_pelacakan">
                                </div>
                            </div>

                            <div style="overflow: auto">
                                <table class="table align-items-center table-flush">
                                    <!-- Your table headers -->
                                    <thead>
                                        <tr>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Produk</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Deskripsi</th>
                                            @if(isset($multiple_gudang))
                                                @if(isset($gudang))
                                                    @foreach($gudang as $v)
                                                    <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas {{ $v->nama }}</th>
                                                    @endforeach
                                                @else
                                                    <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                                @endif
                                            @else
                                            <th scope="col" style="min-width: 50px !important; padding: 10px !important;">Kuantitas</th>
                                            @endif
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Harga Satuan</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">% Diskon</th>
                                            <th scope="col" style="min-width: 100px !important;padding: 10px !important;">Nilai Diskon</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Pajak</th>
                                            <th scope="col" style="min-width: 150px !important;padding: 10px !important;">Jumlah</th>
                                            @if(!isset($pemesanan))<th scope="col" style="min-width: 50px !important;padding: 10px !important;"></th>@endif
                                        </tr>
                                    </thead>
                                    <tbody id="list">
                                        <tr>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control form-control-sm" name="produk[]" id="produk_1" onchange="get_data(this, 1)" required @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                                    <option selected disabled hidden value="">Pilih produk</option>
                                                    @foreach ($produk as $v)
                                                        <option value="{{ $v->id }}"
                                                            data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_1" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                                            </td>
                                            @if(isset($multiple_gudang))
                                                @if(isset($gudang))
                                                    @foreach($gudang as $v)
                                                    <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_{{ $v->id }}_1"
                                                            name="kuantitas_{{ $v->id }}[]" @if($loop->index == 0) value="1" @endif onkeyup="change_harga(1)" onblur="check_null(this)" step="any" @if(isset($pengiriman)) disabled @endif></td>
                                                    @endforeach
                                                @else
                                                <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_1"
                                                        name="kuantitas[]" value="1" onkeyup="change_harga(1)" onblur="check_null(this)" step="any" @if(isset($pengiriman)) disabled @endif></td>
                                                @endif
                                            @else
                                            <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_1"
                                                    name="kuantitas[]" value="1" onkeyup="change_harga(1)" onblur="check_null(this)" step="any" @if(isset($pengiriman)) disabled @endif></td>
                                            @endif
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_1"
                                                    name="harga_satuan[]" value="0" onblur="change_harga(1)" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif></td>
                                            <td style="padding: 10px !important;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" @if(isset($pemesanan) || isset($pengiriman)) style="background-color: #e9ecefc4;" @endif>%</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="diskon_per_baris_1"
                                                            name="diskon_per_baris[]" placeholder="0"
                                                            onkeyup="change_diskon_per_baris(1)" onblur="check_null(this)" step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                                    </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" @if(isset($pemesanan) || isset($pengiriman)) style="background-color: #e9ecefc4;" @endif>Rp</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="nilai_diskon_per_baris_1"
                                                        name="nilai_diskon_per_baris[]" placeholder="0"
                                                        onkeyup="change_nilai_diskon_per_baris(1)" onblur="check_null(this)" step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                                </div>
                                            </td>
                                            <td style="padding: 10px !important;">
                                                <select class="form-control form-control-sm" id="pajak_1" name="pajak[]"
                                                    onchange="get_pajak(this, 1)" required @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                                                    <option value="0" data-persen="0">Pilih pajak</option>
                                                    <option value="11" data-persen="11">PPN</option>
                                                </select>
                                            </td>
                                            <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_1" name="jumlah[]"
                                                    value="0" step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif></td>
                                            @if(!isset($pemesanan))<td style="padding: 10px !important;"><a href="javascript:;" onclick="create_row()"><i
                                                        class="fa fa-plus text-primary"></i></a></td>@endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-md-3 pr-2">
                                    <div class="form-group">
                                        <label for="pesan">Pesan</label>
                                        <textarea class="form-control form-control-sm" name="pesan" id="pesan"></textarea>
                                    </div>
                                    @if(isset($pengaturan_dokumen))
                                        @foreach($pengaturan_dokumen as $v)
                                        <div class="form-group">
                                            <span>Upload {{ $v->nama }}</span>
                                            @if(isset($dokumen_penjualan))
                                            @foreach($dokumen_penjualan as $w)
                                                @if($v->id == $w->id_dokumen)
                                                <a href="{{ asset('storage/uploads') }}/{{ $w->nama }}" target="_blank">{{ $w->nama }}</a>
                                                @endif
                                            @endforeach
                                            @endif
                                            <input type="file" class="form-control form-control-sm" name="{{ $v->id }}" id="file_{{ $v->id }}">
                                            <input type="number" name="id_dokumen[]" value="{{ $v->id }}" hidden id="id_{{ $v->id }}">
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group col-md-3 pr-2">
                                    <label for="memo">Memo</label>
                                    <textarea class="form-control form-control-sm" name="memo" id="memo"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Subtotal</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="subtotal">Rp 0,00</span>
                                            <input type="text" id="input_subtotal" name="input_subtotal" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Diskon per baris</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="diskon_per_baris">Rp 0,00</span>
                                            <input type="text" id="input_diskon_per_baris" name="input_diskon_per_baris"
                                                hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>PPN</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="ppn">Rp 0,00</span>
                                            <input type="text" id="input_ppn" name="input_ppn" hidden>
                                        </div>
                                    </div>
                                    @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
                                    <div class="row">
                                        <div class="col">
                                            <span>Ongkos Kirim</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="ongkos_kirim">Rp 0,00</span>
                                            <input type="text" id="input_ongkos_kirim" name="input_ongkos_kirim" hidden>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row mb-2 mt-2 pt-1 border-top ">
                                        <div class="col">
                                            <span>Total</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="total">Rp 0,00</span>
                                            <input type="text" id="input_total" name="input_total" hidden>
                                        </div>
                                    </div>
                                    @if(isset($penjualan))
                                    @if($penjualan->jumlah_terbayar != 0)
                                    <div class="row mb-2">
                                        <div class="col">
                                            <h4>Jumlah Terbayar</h4>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <h4>Rp. {{ number_format($penjualan->jumlah_terbayar, 2, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="row mb-1">
                                        <div class="col">
                                            <span>Sisa Tagihan</span>
                                        </div>
                                        <div class="col d-flex justify-content-end">
                                            <span id="sisa_tagihan">Rp 0,00</span>
                                            <input type="text" id="input_sisa_tagihan" name="input_sisa_tagihan" hidden>
                                        </div>
                                    </div>
                                    <div class="row my-4">
                                        <div class="col d-flex justify-content-end">
                                            @if(isset($penjualan))
                                            <a href="{{ url('penjualan').'/detail/'.$penjualan->id }}" class="btn btn-light">Batalkan</a>
                                            @else
                                            <a href="{{ url('penjualan') }}" class="btn btn-light">Batalkan</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">@if(isset($pembelian)) Simpan perubahan @elseif(isset($pemesanan)) Buat @else Buat Faktur @endif</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var i = 1;
        var ppn = {};
        var subtotal = {};
        var diskon_per_baris = {};
        var result_subtotal = 0;
        var result_ppn = 0;
        var result_diskon_per_baris = 0;
        var kuantitas_array = [];

        function load() {

            result_subtotal = 0;
            for (var key in subtotal) {
                result_subtotal += subtotal[key];
            }

            result_ppn = 0;
            for (var key in ppn) {
                result_ppn += ppn[key];
            }

            result_diskon_per_baris = 0;
            for (var key in diskon_per_baris) {
                result_diskon_per_baris += diskon_per_baris[key];
            }

            
            $('#subtotal').text(rupiah(result_subtotal));
            $('#ppn').text(rupiah(result_ppn));
            $('#diskon_per_baris').text(rupiah(result_diskon_per_baris));
            $('#total_faktur').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));

            @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
                $('#total').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris + {{ $penjualan->ongkos_kirim }}));
            @else
                $('#total').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));
            @endif
            @if(isset($penjualan) && $penjualan->jumlah_terbayar != 0)
                @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
                    $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris - {{ $penjualan->jumlah_terbayar }} + {{ $penjualan->ongkos_kirim }}));
                    $('#input_sisa_tagihan').val(result_subtotal + result_ppn - result_diskon_per_baris - {{ $penjualan->jumlah_terbayar }} + {{ $penjualan->ongkos_kirim }});
                @else
                    $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris - {{ $penjualan->jumlah_terbayar }}));
                    $('#input_sisa_tagihan').val(result_subtotal + result_ppn - result_diskon_per_baris - {{ $penjualan->jumlah_terbayar }});
                @endif
            @else
                @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
                    $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris + {{ $penjualan->ongkos_kirim }}));
                    $('#input_sisa_tagihan').val(result_subtotal + result_ppn - result_diskon_per_baris + {{ $penjualan->ongkos_kirim }});
                @else
                    $('#sisa_tagihan').text(rupiah(result_subtotal + result_ppn - result_diskon_per_baris));
                    $('#input_sisa_tagihan').val(result_subtotal + result_ppn - result_diskon_per_baris);
                @endif
            @endif

            $('#input_subtotal').val(result_subtotal);
            $('#input_ppn').val(result_ppn);
            $('#input_diskon_per_baris').val(result_diskon_per_baris);
            
            @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
            $('#input_total').val(result_subtotal + result_ppn - result_diskon_per_baris + {{ $penjualan->ongkos_kirim }});
            @else
            $('#input_total').val(result_subtotal + result_ppn - result_diskon_per_baris);
            @endif
        }

        function load_select_2(id) {
            $("#produk_" + id).select2({
                allowClear: true,
                placeholder: 'Pilih produk'
            });

            new AutoNumeric("#harga_satuan_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });
            new AutoNumeric("#jumlah_" + id, {
                commaDecimalCharDotSeparator: true,
                watchExternalChanges: true,
                modifyValueOnWheel : false
            });
        }

        function get_data(thisElement, no) {
            var selected = $(thisElement).find('option:selected').data('harga_jual');
            // $('#harga_satuan_' + no).val(selected);
            // $('#jumlah_' + no).val(selected);
            AutoNumeric.set('#harga_satuan_' + no,selected);
            AutoNumeric.set('#jumlah_' + no,selected);

            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif

            subtotal[no] = kuantitas * parseFloat(selected);

            float_diskon_per_baris = parseFloat($('#diskon_per_baris_' + no).val()) || 0;

            diskon_per_baris[no] = subtotal[no] * float_diskon_per_baris / 100;
            $('#nilai_diskon_per_baris_'+no).val(diskon_per_baris[no] == 0 ? "" : diskon_per_baris[no] );
            get_pajak($('#pajak_' + no), no);
            load();
        }

        function get_pajak(thisElement, no) {
            var selected = parseFloat($(thisElement).find('option:selected').data('persen'));

            if (selected != 0) {
                ppn[no] = selected * parseFloat(AutoNumeric.getNumber('#jumlah_' + no)) / 100;
            } else {
                ppn[no] = 0;
            }

            load();
        }

        function change_harga(no, val_harga_satuan = null) {
            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif

            if(val_harga_satuan){
                AutoNumeric.set('#harga_satuan_' + no,val_harga_satuan);
            }else{
                AutoNumeric.set('#harga_satuan_' + no,AutoNumeric.getNumber('#harga_satuan_' + no));
            }
            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = subtotal[no] * diskon / 100;
            AutoNumeric.set('#jumlah_' + no,subtotal[no] - diskon_per_baris[no]);
            get_pajak($('#pajak_' + no), no);
            change_diskon_per_baris(no);
            load();
        }

        function change_jumlah(no) {
            AutoNumeric.set('#jumlah_' + no,AutoNumeric.getNumber('#jumlah_' + no));
            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;
            
            AutoNumeric.set('#harga_satuan_' + no, (100/(100-diskon)) * AutoNumeric.getNumber('#jumlah_' + no) / kuantitas);

            subtotal[no] = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon_per_baris[no] = subtotal[no] * diskon / 100;
            get_pajak($('#pajak_'+no), no);
            load();
        }

        function change_diskon_per_baris(no) {
            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif
            // harga_satuan = $('#harga_satuan_' + no).val() ? parseFloat($('#harga_satuan_' + no).val()) : 0;
            var subtotal = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            diskon = $('#diskon_per_baris_' + no).val() ? parseFloat($('#diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = subtotal * diskon / 100;

            $('#nilai_diskon_per_baris_'+no).val(diskon_per_baris[no] == 0 ? "" : diskon_per_baris[no] );

            // $('#jumlah_' + no).val(subtotal - diskon_per_baris[no]);
            AutoNumeric.set('#jumlah_' + no,subtotal - diskon_per_baris[no]);

            get_pajak($('#pajak_'+no), no);
            
            load();
        }

        function change_nilai_diskon_per_baris(no) {
            @if(isset($multiple_gudang))
                @if(isset($gudang))
                    @foreach($gudang as $v)
                        kuantitas_array[{{ $loop->index }}] = $('#kuantitas_{{ $v->id }}_' + no).val() ? parseFloat($('#kuantitas_{{ $v->id }}_' + no).val()) : 0;
                    @endforeach
                    kuantitas = kuantitas_array.reduce((accumulator, currentValue) => {
                        return accumulator + currentValue
                    },0);
                @else
                    kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
                @endif
            @else
                kuantitas = $('#kuantitas_' + no).val() ? parseFloat($('#kuantitas_' + no).val()) : 0;
            @endif
            
            var subtotal = kuantitas * parseFloat(AutoNumeric.getNumber('#harga_satuan_' + no));
            nilai_diskon = $('#nilai_diskon_per_baris_' + no).val() ? parseFloat($('#nilai_diskon_per_baris_' + no).val()) : 0;
            diskon_per_baris[no] = nilai_diskon;
            $('#diskon_per_baris_'+no).val("");

            // $('#jumlah_' + no).val(subtotal - diskon_per_baris[no]);
            AutoNumeric.set('#jumlah_' + no,subtotal - diskon_per_baris[no]);

            get_pajak($('#pajak_'+no), no);
            
            load();
        }

        function check_null(element) {
            if (element.value.trim() === "") {
                element.value = "";
                load();
            }

        }

        function hapus(no) {
            $('#list_' + no).remove();
            subtotal[no] = 0;
            ppn[no] = 0;
            diskon_per_baris[no] = 0;
            load();
        }

        function create_row() {
            i++;
            $('#list').append(`
                <tr id="list_${i}">
                    <th style="padding: 10px !important;">
                        <select class="form-control form-control-sm" name="produk[]" id="produk_${i}" onchange="get_data(this, ${i})" required @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                            <option selected disabled hidden value="">Pilih produk</option>
                            @foreach ($produk as $v)
                                <option value="{{ $v->id }}" data-harga_jual="{{ $v->harga_jual }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </th>
                    <td style="padding: 10px !important;">
                        <textarea class="form-control form-control-sm" name="deskripsi[]" id="deskripsi_${i}" cols="30" rows="1" placeholder="Masukkan Deskripsi"></textarea>
                    </td>
                    @if(isset($multiple_gudang))
                        @if(isset($gudang))
                            @foreach($gudang as $v)
                                <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_{{ $v->id }}_${i}" 
                                        name="kuantitas_{{ $v->id }}[]" @if($loop->index == 0) value="1" @endif onkeyup="change_harga(${i})" onblur="check_null(this)" step="any" @if(isset($pengiriman)) disabled @endif></td>
                            @endforeach
                        @else
                            <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_harga(${i})" onblur="check_null(this)" step="any"></td>
                        @endif
                    @else
                        <td style="padding: 10px !important;"><input type="number" class="form-control form-control-sm" id="kuantitas_${i}" name="kuantitas[]" value="1" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any" @if(isset($pengiriman)) disabled @endif></td>
                    @endif
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="harga_satuan_${i}" name="harga_satuan[]" value="0" onkeyup="change_jumlah(${i})" onblur="check_null(this)" step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif></td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" @if(isset($pemesanan) || isset($pengiriman)) style="background-color: #e9ecefc4;" @endif>%</span>
                            </div>
                            <input type="number" class="form-control" id="diskon_per_baris_${i}" 
                                name="diskon_per_baris[]" placeholder="0" 
                                onkeyup="change_diskon_per_baris(${i})" onblur="check_null(this)" 
                                step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" @if(isset($pemesanan) || isset($pengiriman)) style="background-color: #e9ecefc4;" @endif>Rp</span>
                            </div>
                            <input type="number" class="form-control" id="nilai_diskon_per_baris_${i}" 
                                name="nilai_diskon_per_baris[]" placeholder="0" 
                                onkeyup="change_nilai_diskon_per_baris(${i})" onblur="check_null(this)" 
                                step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                        </div>
                    </td>
                    <td style="padding: 10px !important;">
                        <select class="form-control form-control-sm" id="pajak_${i}" name="pajak[]" onchange="get_pajak(this, ${i})" required @if(isset($pemesanan) || isset($pengiriman)) disabled @endif>
                            <option value="0" data-persen="0" >Pilih pajak</option>
                            <option value="11" data-persen="11">PPN</option>
                        </select>
                    </td>
                    <td style="padding: 10px !important;"><input type="text" class="form-control form-control-sm" id="jumlah_${i}" name="jumlah[]" value="0" step="any" @if(isset($pemesanan) || isset($pengiriman)) disabled @endif></td>
                    @if(!isset($pemesanan) || !isset($pengiriman))<td style="padding: 10px !important;"><a href="javascript:;" onclick="hapus(${i})"><i class="fa fa-trash text-primary"></i></a></td>@endif
                </tr>
            `);
            load_select_2(i);
        };

        $("#info_pengiriman").change(function() {
            // if(this.checked) {
                // $('.info_pengiriman').show();
            // }else{
                $('.info_pengiriman').hide();
            // }
        });

        $('#sama_dengan_penagihan').change(function() {
            if(this.checked) {
                $('#alamat_pengiriman').hide();
                $('.alamat_pengiriman').hide();
            }else{
                $('#alamat_pengiriman').show();
                $('.alamat_pengiriman').show();
            }
        });

        $( document ).ready(function() {
            $('#info_pengiriman').prop('checked', true).trigger("change");

            $('#pelanggan').selectpicker();
            
            const fp_transaksi = flatpickr("#tanggal_transaksi", {
                dateFormat: "d/m/Y" // Contoh format: DD/MM/YYYY
            });
            fp_transaksi.setDate(new Date('{{ date("Y-m-d") }}'));

            const fp_pengiriman = flatpickr("#tanggal_pengiriman", {
                dateFormat: "d/m/Y"
            });
            fp_pengiriman.setDate(new Date('{{ date("Y-m-d") }}'));

            const fp_jatuh_tempo = flatpickr("#tanggal_jatuh_tempo", {
                dateFormat: "d/m/Y"
            });
            fp_jatuh_tempo.setDate(new Date('{{ date("Y-m-d") }}'));
            @if(isset($penjualan))
                const pel = $('#pelanggan')
                pel.selectpicker('val','{{ $penjualan->id_pelanggan }}')
                alamat_pelanggan(pel[0]).then(function() {
                    $('#alamat').val('{{ $penjualan->alamat }}');
                })
                $('#email').val('{{ $penjualan->email }}')
                $('#detail_alamat').val('{{ $penjualan->detail_alamat }}')
                fp_transaksi.setDate(new Date('{{ $penjualan->tanggal_transaksi }}'));
                $('#tanggal_jatuh_tempo').val('{{ $penjualan->tanggal_jatuh_tempo }}')
                $('#gudang').val('{{ $penjualan->id_gudang }}')

                $('#kirim_melalui').val('{{ $penjualan->kirim_melalui }}')
                $('#no_pelacakan').val('{{ $penjualan->no_pelacakan }}')

                $('#pesan').val('{{ $penjualan->pesan }}')
                $('#memo').val('{{ $penjualan->memo }}')

                @if(isset($penjualan->ongkos_kirim) && $penjualan->ongkos_kirim > 0)
                $('#ongkos_kirim').text(rupiah('{{ $penjualan->ongkos_kirim }}'));
                $('#input_ongkos_kirim').val('{{ $penjualan->ongkos_kirim }}');
                @endif
                var x = 1;
                load_select_2(x);
                @foreach($detail_penjualan as $v)
                    $('#produk_'+x).val('{{ $v->id_produk }}').trigger('change');
                    $('#deskripsi_'+x).val('{{ $v->deskripsi }}');
                    @if(isset($multiple_gudang))
                    @php
                        $stokMap = [];
                        foreach ($v->stok_gudang as $w) {
                            $stokMap[$w->id_gudang] = $w->stok;
                        }
                    @endphp
                    @foreach($gudang as $g)
                        $('#kuantitas_'+{{ $g->id }}+'_'+x).val('{{ $stokMap[$g->id] ?? 0 }}').trigger('keyup');
                    @endforeach
                    @else
                        $('#kuantitas_'+x).val('{{ $v->kuantitas }}').trigger('keyup');
                    @endif
                    change_harga(x, {{ $v->harga_satuan }});
                    $('#diskon_per_baris_'+x).val('{{ $v->diskon_per_baris }}').trigger('keyup');
                    @if($v->pajak != 0)
                        $('#pajak_'+x).val('11').trigger('change');
                    @else
                        $('#pajak_'+x).val('0').trigger('change');
                    @endif
                    create_row();
                    x++;
                @endforeach
                hapus(x);
            @else
                load_select_2(1);
            @endif
            
        });

        @if(isset($pemesanan) || isset($pengiriman))
        $('#insertForm').submit(function() {
            $('.form-control').removeAttr('disabled');
        });
        @endif

        function alamat_pelanggan(thisElement) {
            var selected = $(thisElement).find('option:selected').val();
            $('#alamat').empty();
            return $.ajax({
                url: '{{ url("pelanggan/alamat") }}',
                type: 'GET',
                data: {
                    id: selected
                },
                success: function (response) {
                    $('#alamat').append('<option selected disabled hidden value="">Pilih Alamat</option>');
                    for(var i = 0; i < response.length; i++){
                        console.log(response[i]);
                        $('#alamat').append('<option>'+response[i].alamat+'</option>');
                    }
                }
            });
        }
    </script>
@endsection
