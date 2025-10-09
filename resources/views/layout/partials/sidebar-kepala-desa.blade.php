<div class="sidebar" id="sidebar">
    <!-- logo -->
    <div class="sidebar-logo active">
        <a href="{{ route('kepala-desa.dashboard') }}" class="logo logo-normal">
            <img src="{{ URL::asset('build/img/logo.svg') }}" alt="img">
        </a>
        <a href="{{ route('kepala-desa.dashboard') }}" class="logo logo-white">
            <img src="{{ URL::asset('build/img/logo-white.svg') }}" alt="img">
        </a>
        <a href="{{ route('kepala-desa.dashboard') }}" class="logo-small">
            <img src="{{ URL::asset('build/img/logo-small.png') }}" alt="img">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->
    
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="{{ URL::asset('build/img/customer/customer15.jpg') }}" alt="img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-12 fw-normal mb-1">{{ Auth::user()->name ?? 'Kepala Desa' }}</h6>
            <p class="fs-10 mb-0">Kepala Desa</p>
        </div>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ Request::routeIs('kepala-desa.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('kepala-desa.dashboard') }}">
                        <i class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Menu Data Petani dengan Submenu -->
                <li class="submenu {{ Request::routeIs('kepala-desa.petani.*') ? 'active' : '' }}">
                    <a href="javascript:void(0);">
                        <i class="ti ti-users-group fs-16 me-2"></i><span>Data Petani</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li class="{{ Request::routeIs('kepala-desa.petani.validated') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.petani.validated') }}">Petani Tervalidasi</a>
                        </li>
                        <li class="{{ Request::routeIs('kepala-desa.petani.pending') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.petani.pending') }}">Menunggu Validasi</a>
                        </li>
                        <li class="{{ Request::routeIs('kepala-desa.petani.rejected') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.petani.rejected') }}">Pengajuan Ditolak</a>
                        </li>
                    </ul>
                </li>

                <!-- Menu Laporan dengan Submenu -->
                <li class="submenu {{ Request::routeIs('kepala-desa.reports.*') ? 'active' : '' }}">
                    <a href="javascript:void(0);">
                        <i class="ti ti-file-analytics fs-16 me-2"></i><span>Laporan</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li class="{{ Request::routeIs('kepala-desa.reports.fertilizer-movement') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.reports.fertilizer-movement') }}">
                                <i class="ti ti-arrow-left-right fs-14 me-2"></i>Pergerakan Pupuk
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('kepala-desa.reports.subsidy-allocation') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.reports.subsidy-allocation') }}">
                                <i class="ti ti-discount fs-14 me-2"></i>Alokasi Subsidi
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('kepala-desa.reports.financial') ? 'active' : '' }}">
                            <a href="{{ route('kepala-desa.reports.financial') }}">
                                <i class="ti ti-chart-bar fs-14 me-2"></i>Laporan Keuangan
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="{{ route('signout') }}">
                        <i class="ti ti-logout fs-16 me-2"></i><span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>