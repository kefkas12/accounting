<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ url('/') }}">
            {{ Auth::user()->nama_perusahaan }}
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ url('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'laporan' ? 'active' : '' }}" href="{{ url('laporan') }}">
                        <i class="fa fa-store text-primary"></i> {{ __('Laporan') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'pembelian' ? 'active' : '' }}" href="{{ url('pembelian') }}">
                        <i class="fa fa-store text-primary"></i> {{ __('Pembelian') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'penjualan' ? 'active' : '' }}" href="{{ url('penjualan') }}">
                        <i class="fa fa-store text-primary"></i> {{ __('Penjualan') }}
                    </a>
                </li>
                <li class="nav-item {{ $sidebar == 'supplier' || $sidebar == 'pelanggan' ? 'active' : '' }}">
                    <a class="nav-link" href="#navbar_kontak" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'supplier' || $sidebar == 'pelanggan' ? 'true' : 'false' }}" aria-controls="navbar_kontak">
                        <i class="fa fa-address-book text-primary"></i>
                        <span class="nav-link-text">{{ __('Kontak') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'supplier' || $sidebar == 'pelanggan' ? 'show' : '' }}" id="navbar_kontak">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'supplier' ? 'active' : '' }}" href="{{ url('supplier') }}">
                                    {{ __('Supplier') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'pelanggan' ? 'active' : '' }}" href="{{ url('pelanggan') }}">
                                    {{ __('Pelanggan') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'produk' ? 'active' : '' }}" href="{{ url('produk') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('Produk') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'akun' ? 'active' : '' }}" href="{{ url('akun') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('Daftar Akun') }}
                    </a>
                </li>
                @can('read_role')
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'role' ? 'active' : '' }}" href="{{ url('role') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('Role') }}
                    </a>
                </li>
                @endcan
                @canany(['read_user', 'read_user_palembang', 'read_user_lampung', 'read_user_bengkulu', 'read_user_ntt', 'read_user_ntb', 'read_user_jambi'])
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'user' ? 'active' : '' }}" href="{{ url('user') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('User') }}
                    </a>
                </li>
                @endcan
                @canany(['read_receive_item_palembang', 'read_receive_item_lampung', 'read_receive_item_bengkulu', 'read_receive_item_ntt', 'read_receive_item_ntb', 'read_receive_item_jambi'])
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'receive_item' ? 'active' : '' }}" href="{{ url('receive_item') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('Receive Item') }}
                    </a>
                </li>
                @endcan
                @canany(['read_unit_palembang', 'read_unit_lampung', 'read_unit_bengkulu', 'read_unit_ntt', 'read_unit_ntb', 'read_unit_jambi'])
                <li class="nav-item {{ $sidebar == 'unit' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('unit') }}">
                        <i class="fa fa-id-card text-primary"></i> {{ __('Unit') }}
                    </a>
                </li>
                @endcan
                @canany(['read_job_request_palembang', 'read_job_request_lampung', 'read_job_request_bengkulu', 'read_job_request_ntt', 'read_job_request_ntb', 'read_job_request_jambi'])
                <li class="nav-item {{ $sidebar == 'job_request' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('job_request') }}">
                        <i class="fa fa-id-card text-primary"></i> {{ __('Job Request') }}
                    </a>
                </li>
                @endcan
                @canany(['read_service_palembang', 'read_service_lampung', 'read_service_bengkulu', 'read_service_ntt', 'read_service_ntb', 'read_service_jambi'])
                <li class="nav-item {{ $sidebar == 'service' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('service') }}">
                        <i class="fa fa-id-card text-primary"></i> {{ __('Service') }}
                    </a>
                </li>
                @endcan
                @canany(['create_setting', 'read_setting', 'update_setting', 'delete_setting'])
                <li class="nav-item {{ $sidebar == 'setting' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('setting') }}">
                        <i class="fa fa-id-card text-primary"></i> {{ __('Setting') }}
                    </a>
                </li>
                @endcan
                @canany(['export_service_palembang', 'export_service_lampung', 'export_service_bengkulu', 'export_service_ntt', 'export_service_ntb', 'export_service_jambi', 'export_unit_palembang', 'export_unit_lampung', 'export_unit_bengkulu', 'export_unit_ntt', 'export_unit_ntb', 'export_unit_jambi'])
                <li class="nav-item {{ $sidebar == 'export_service' || $sidebar == 'export_unit' ? 'active' : '' }}">
                    <a class="nav-link" href="#navbar_export" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'export_service' || $sidebar == 'export_unit' ? 'true' : 'false' }}" aria-controls="navbar_export">
                        <i class="fa fa-truck"></i>
                        <span class="nav-link-text">{{ __('Export') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'export_service' || $sidebar == 'export_unit' ? 'show' : '' }}" id="navbar_export">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'export_service' ? 'active' : '' }}" href="{{ url('export/service') }}">
                                    {{ __('Export Service') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'export_unit' ? 'active' : '' }}" href="{{ url('export/unit') }}">
                                    {{ __('Export Unit') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
                <li class="nav-item" hidden>
                    <a class="nav-link" href="#navbar_mobil" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'mobil_semua' || $sidebar == 'mobil_engkel' || $sidebar == 'mobil_tronton' ? 'true' : 'false' }}" aria-controls="navbar_mobil">
                        <i class="fa fa-truck"></i>
                        <span class="nav-link-text">{{ __('Mobil') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'mobil_semua' || $sidebar == 'mobil_engkel' || $sidebar == 'mobil_tronton' ? 'show' : '' }}" id="navbar_mobil">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'mobil_semua' ? 'active' : '' }}" href="{{ url('mobil') }}">
                                    {{ __('Seluruh') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'mobil_engkel' ? 'active' : '' }}" href="{{ url('mobil/engkel') }}">
                                    {{ __('Engkel') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'mobil_tronton' ? 'active' : '' }}" href="{{ url('mobil/tronton') }}">
                                    {{ __('Tronton') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item" hidden>
                    <a class="nav-link" href="#navbar_transaksi" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'transaksi_engkel' || $sidebar == 'transaksi_tronton' ? 'true' : 'false' }}" aria-controls="navbar_transaksi">
                        <i class="fa fa-file"></i>
                        <span class="nav-link-text">{{ __('Transaksi') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'transaksi_engkel' || $sidebar == 'transaksi_tronton' ? 'show' : '' }}" id="navbar_transaksi">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'transaksi_engkel' ? 'active' : '' }}" href="{{ url('transaksi/engkel') }}">
                                    {{ __('Engkel') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'transaksi_tronton' ? 'active' : '' }}" href="{{ url('transaksi/tronton') }}">
                                    {{ __('Tronton') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item" hidden>
                    <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                        <i class="fa fa-cog"></i>
                        <span class="nav-link-text">{{ __('Barang') }}</span>
                    </a>

                    <div class="collapse" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('barang') }}">
                                    {{ __('Stock Barang') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('transaksi_barang') }}">
                                    {{ __('Beli Barang') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('pbarang') }}">
                                    {{ __('Pengeluaran Barang') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if(Auth::user()->name == 'pemilik')
                <li class="nav-item" hidden>
                    <a class="nav-link" href="#navbar-examples2" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'mobil_gemilang_engkel' || $sidebar == 'mobil_gemilang_tronton' ? 'true' : 'false' }}" aria-controls="navbar-examples2">
                        <i class="fa fa-file"></i>
                        <span class="nav-link-text">{{ __('Data Mobil Gemilang') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'mobil_gemilang_engkel' || $sidebar == 'mobil_gemilang_tronton' ? 'show' : '' }}" id="navbar-examples2">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'mobil_gemilang_engkel' ? 'active' : '' }}" href="{{ url('mobil_gemilang/engkel') }}">
                                    {{ __('Engkel') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'mobil_gemilang_tronton' ? 'active' : '' }}" href="{{ url('mobil_gemilang/tronton') }}">
                                    {{ __('Tronton') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ $sidebar == 'invoice' ? 'active' : '' }}" hidden>
                    <a class="nav-link" href="{{ url('invoice') }}">
                        <i class="fa fa-receipt text-primary"></i> {{ __('Invoice') }}
                    </a>
                </li>
                <li class="nav-item" hidden>
                    <a class="nav-link" href="#navbar-report" data-toggle="collapse" role="button" aria-expanded="{{ $sidebar == 'report_nomor_polisi_engkel' || $sidebar == 'report_nomor_polisi_tronton' || $sidebar == 'report_customer' || $sidebar == 'report_partner' ? 'true' : 'false' }}" aria-controls="navbar-report">
                        <i class="fa fa-file"></i>
                        <span class="nav-link-text">{{ __('Report') }}</span>
                    </a>

                    <div class="collapse {{ $sidebar == 'report_nomor_polisi_engkel' || $sidebar == 'report_nomor_polisi_tronton' || $sidebar == 'report_customer' || $sidebar == 'report_partner' ? 'show' : '' }}" id="navbar-report">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#navbar-nomor_polisi" data-toggle="collapse" role="button" aria-expanded=" {{ $sidebar == 'report_nomor_polisi_engkel' || $sidebar == 'report_nomor_polisi_tronton' ? 'true' : 'false' }}" aria-controls="navbar-nomor_polisi" >
                                    <span class="nav-link-text">{{ __('Per Nomor Polisi') }}</span>
                                </a>
                                <div class="collapse {{ $sidebar == 'report_nomor_polisi_engkel' || $sidebar == 'report_nomor_polisi_tronton' ? 'show' : '' }}" id="navbar-nomor_polisi">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link {{ $sidebar == 'report_nomor_polisi_engkel' ? 'active' : '' }}" href="{{ url('report/nomor_polisi/engkel') }}">
                                                <span class="nav-link-text">{{ __('Engkel') }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ $sidebar == 'report_nomor_polisi_tronton' ? 'active' : '' }}" href="{{ url('report/nomor_polisi/tronton') }}">
                                                <span class="nav-link-text">{{ __('Tronton') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'report_customer' ? 'active' : '' }}" href="{{ url('report/customer') }}">
                                    {{ __('Per Customer') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $sidebar == 'report_partner' ? 'active' : '' }}" href="{{ url('report/partner') }}">
                                    {{ __('Per Partner') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>