<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <div class="d-flex justify-content-center">
            <img width="75px" src="@if(Auth::user()->logo_perusahaan) {{ asset('argon/img/brand').'/'.Auth::user()->logo_perusahaan }} @else {{ asset('argon') }}/img/theme/team-4-800x800.jpg @endif" alt="">
        </div>
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
            @if(Auth::user()->email == 'superadmin@gmail.com')
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/company') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Company') }}
                    </a>
                </li>
            </ul>
            @else
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
                    <a class="nav-link {{ $sidebar == 'penjualan' ? 'active' : '' }}" href="{{ url('penjualan') }}">
                        <i class="fa fa-store text-primary"></i> {{ __('Penjualan') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'pembelian' ? 'active' : '' }}" href="{{ url('pembelian') }}">
                        <i class="fa fa-store text-primary"></i> {{ __('Pembelian') }}
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
                <li class="nav-item">
                    <a class="nav-link {{ $sidebar == 'pengaturan' ? 'active' : '' }}" href="{{ url('pengaturan') }}">
                        <i class="fa fa-address-book text-primary"></i> {{ __('Pengaturan') }}
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>