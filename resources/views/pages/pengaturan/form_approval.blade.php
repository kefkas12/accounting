@extends('pages.pengaturan.sidebar', ['sidebar' => $sidebar])

@section('content_pengaturan')
    <div class="card" style="border-radius: 0">
        <div class="card-header">Buat Aturan Approval</div>
        <div class="card-body">
            <form action="{{ url('pengaturan/approval/insert') }}" method="POST" enctype="multipart/form-data" style="font-size: 12px" id="insertForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3"><label for="name">Nama Aturan</label></div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label for="name">Tipe Transaksi</label></div>
                    <div class="col-md-5">
                        <select class="form-control" name="tipe_transaksi" id="tipe_transaksi" required>
                            <option>Faktur Penjualan</option>
                            <option>Pembayaran Penjualan</option>
                            <option>Pesanan Penjualan</option>
                            <option>Pembayaran Pesanan Penjualan</option>
                            <option>Penawaran Penjualan</option>
                            <option>Pengiriman Penjualan</option>
                            
                            <option>Faktur Pembelian</option>
                            <option>Pembayaran Pembelian</option>
                            <option>Pesanan Pembelian</option>
                            <option>Pembayaran Pesanan Pembelian</option>
                            <option>Penawaran Pembelian</option>
                            <option>Pengiriman Pembelian</option>
                            <option>Permintaan Pembelian</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label for="name">Transaksi yang dibuat oleh</label></div>
                    <div class="col-md-5">
                        <select class="form-control" name="requester" id="requester" required>
                            @foreach($requester as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3"><label for="name">Transaksi memerlukan approval dari</label></div>
                    <div class="col-md-5">
                        <select class="form-control" name="approver" id="approver" required>
                            @foreach($approver as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-8 d-flex justify-content-end">
                    <a href="{{ url('pengaturan/approval') }}" class="btn btn-light">Batalkan</a>
                    <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function simpan() {
            event.preventDefault();
            if (!$('#nama').val()) {
                Swal.fire({
                    title: 'Nama Aturan didn`t match',
                    text: 'Nama Aturan tidak boleh kosong',
                    icon: 'error'
                })
            } else {
                $('#insertForm').submit();
            }
        }
    </script>
@endsection
