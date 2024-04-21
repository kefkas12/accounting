<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main" tabindex="0">
    <div class="container-fluid" style="padding-left: 1rem !important;">
        <!-- Brand -->
        <a class=" text-white border-white d-none d-lg-inline-block btn btn-sm" href="{{ url('penjualan/penagihan') }}">
            <i class="fa fa-tag"></i> &nbsp; Jual
        </a>
        <a class=" text-white border-white d-none d-lg-inline-block btn btn-sm" href="{{ url('pembelian/faktur') }}">
            <i class="ni ni-cart"></i> &nbsp; Beli
        </a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
            <div class="form-group mb-0">
            </div>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="@if(Auth::user()->logo_perusahaan) {{ asset('argon/img/brand').'/'.Auth::user()->logo_perusahaan }} @else {{ asset('argon') }}/img/theme/team-4-800x800.jpg @endif">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ Auth::user()->nama.' - '.Auth::user()->jabatan }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>