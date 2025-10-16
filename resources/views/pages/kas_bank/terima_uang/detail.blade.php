@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <style>
        .form-control {
            height: 40px !important;

        }

        .table th,
        .table td {
            padding: 10px !important;
        }
    </style>
    <div class="mt--6">
        <div class="row">
            <div class="col">
                <div class="card mb-5">
                    <div class="card-header bg-transparent border-0">
                        <div class="row">
                            <div class="col-sm-8">Transaksi <br>
                                <h2>{{ $transfer_uang->no_str }}</h2></div>
                            <div class="col-sm-4 d-flex justify-content-end">
                                <h1 class="text-dark">SELESAI</h1>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body" style="padding: 0px !important;">
                        <div style="background-color: #E0F7FF; border-top: 2px solid #B3D7E5;">
                            <div class="container-fluid mt-5">
                                <div class="row">
                                    <div class="col-sm-2 mt-2">Transfer Dari</div>
                                    <div class="col-sm-4 mt-2"><a href="{{ url('kas_bank/detail').'/'.$transfer_uang->id_transfer_dari }}">{{ $transfer_uang->transfer_dari }}</a></div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end">No Transaksi: <br> {{ $transfer_uang->no }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 mt-2">Setor Ke</div>
                                    <div class="col-sm-4 mt-2"><a href="{{ url('kas_bank/detail').'/'.$transfer_uang->id_setor_ke }}">{{ $transfer_uang->setor_ke }}</a></div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end"><br><br></div>    
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 mt-2">Tgl Transaksi:</div>
                                    <div class="col-sm-2 mt-2">{{ date('d/m/Y',strtotime($transfer_uang->tanggal_transaksi)) }}</div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end"><br><br></div>  
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-2 mt-2">Tgl Transaksi:</div>
                                    <div class="col-sm-2 mt-2">Rp {{ number_format($transfer_uang->jumlah,2,',','.') }} <br> <a href="#" class="text-xs" data-toggle="modal" data-target="#exampleModal">Lihat Jurnal Entry</a></h2></div>
                                    <div class="col-sm-4 mt-2 d-flex justify-content-end"><br><br><br><br></div>  
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <form id="deleteForm" action="{{ url('kas_bank/transfer_uang/hapus') . '/' . $transfer_uang->id }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-outline-danger"onclick="confirmDelete(event)">Hapus</button>
                                </form>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <a href="{{ url('kas_bank/detail') . '/' . $transfer_uang->id_transfer_dari }}" class="btn btn-dark">Kembali</a>
                                <a href="{{ url('kas_bank/transfer_uang/edit') . '/' . $transfer_uang->id }}" class="btn btn-outline-primary">Ubah</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"><div class="text-xs mb-1">Laporan Jurnal</div><strong>{{ $transfer_uang->no_str }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Akun</th>
                                <th scope="col">Debit</th>
                                <th scope="col">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_debit = 0;
                                $total_kredit = 0;
                            @endphp
                            @foreach($jurnal->detail_jurnal as $v)
                            <tr>
                                <td>{{ $v->akun->nomor }} - {{ $v->akun->nama }}</td>
                                <td>Rp. {{ number_format($v->debit,2,',','.') }}</td>
                                <td>Rp. {{ number_format($v->kredit,2,',','.') }}</td>
                            </tr>
                            @php
                                $total_debit += $v->debit;
                                $total_kredit += $v->kredit;
                            @endphp
                            @endforeach
                            <tr>
                                <td>Total</td>
                                <td>Rp. {{ number_format($total_debit,2,',','.') }}</td>
                                <td>Rp. {{ number_format($total_kredit,2,',','.')    }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        var total = {};

        function change_total(no) {
            total[no] = parseInt($('#total_' + no).val());
            load();
        }

        function load() {
            result_total = 0;
            for (var key in total) {
                result_total += total[key];
            }
            $('#subtotal').text(rupiah(result_total));
            $('#total_pembayaran').text(rupiah(result_total));

            $('#input_subtotal').val(result_total);
        }
    </script>
@endsection
