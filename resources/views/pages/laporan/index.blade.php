@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <!-- Page content -->
    <div class="mt--6">
        <!-- Dark table -->
        <div class="row">
            <div class="col">
                <div class="card ">
                    <div class="card-header border-0">

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                                    type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">Home</button>
                                {{-- <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                                    type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">Profile</button>
                                <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                                    type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Contact</button> --}}
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab">
                                <div class="row mt-3">
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Neraca</h5>
                                                <p class="card-text">Menampilkan apa yang dimiliki (aset), apa saja utangnya (liabilitas), dan apa yang sudah diinvestasikan ke perusahaan ini (ekuitas) pada tanggal tertentu.
                                                </p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/neraca') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Buku Besar</h5>
                                                <p class="card-text">Menampilkan semua transaksi berdasarkan akun dalam periode tertentu, termasuk kronologi pergerakan transaksinya selama periode berlangsung.
                                                </p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/buku_besar') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Laba rugi</h5>
                                                <p class="card-text">Menampilkan semua pendapatan yang diperoleh dan biaya yang dikeluarkan dalam periode tertentu. Template laporan versi terkini bisa Anda custom sesuai kebutuhan.
                                                </p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/laba_rugi') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Jurnal</h5>
                                                <p class="card-text">Menampilkan semua journal entry per transaksi dalam periode tertentu. Anda dapat melacak transaksi yang masuk ke masing-masing akun.
                                                </p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/jurnal') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Daftar penjualan</h5>
                                                <p class="card-text">Menampilkan transaksi penjualan secara kronologis
                                                    berdasarkan
                                                    tipenya dalam periode tertentu. Template laporan ini bisa Anda custom
                                                    sesuai
                                                    kebutuhan.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/penjualan/penagihan') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Daftar Pembelian</h5>
                                                <p class="card-text">Menampilkan transaksi pembelian secara kronologis
                                                    berdasarkan
                                                    tipenya dalam periode tertentu. Template laporan ini bisa Anda custom
                                                    sesuai
                                                    kebutuhan.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ url('laporan/pembelian/faktur') }}" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Penjualan per pelanggan</h5>
                                                <p class="card-text">Menampilkan semua transaksi penjualan dari setiap
                                                    pelanggan
                                                    dalam periode tertentu.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Pembelian per supplier</h5>
                                                <p class="card-text">Menampilkan semua transaksi pembelian dari setiap
                                                    supplier
                                                    dalam periode tertentu.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Piutang pelanggan</h5>
                                                <p class="card-text">Menampilkan semua faktur yang belum dibayar dan saldo
                                                    memo
                                                    kredit pelanggan pada tanggal tertentu.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Utang supplier</h5>
                                                <p class="card-text">Menampilkan semua faktur yang belum dibayar dan saldo
                                                    memo
                                                    debit supplier pada tanggal tertentu.</p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Penjualan per produk</h5>
                                                <p class="card-text">Menampilkan semua kuantitas produk yang terjual,
                                                    kuantitas
                                                    retur, penjualan bersih, dan harga penjualan rata-rata dalam periode
                                                    tertentu.
                                                </p>

                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Pembelian per produk</h5>
                                                <p class="card-text">Menampilkan semua kuantitas produk yang dibeli,
                                                    kuantitas
                                                    retur, pembelian bersih, dan harga pembelian rata-rata dalam periode
                                                    tertentu.
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="#" class="btn btn-primary">Lihat Laporan</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab">...</div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                aria-labelledby="nav-contact-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
