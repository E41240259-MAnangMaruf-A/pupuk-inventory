<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo active">
        <a href="{{ url('index') }}" class="logo logo-normal">
            <img src="{{ URL::asset('build/img/logo.svg') }}" alt="Img">
        </a>
        <a href="{{ url('index') }}" class="logo logo-white">
            <img src="{{ URL::asset('build/img/logo-white.svg') }}" alt="Img">
        </a>
        <a href="{{ url('index') }}" class="logo-small">
            <img src="{{ URL::asset('build/img/logo-small.png') }}" alt="Img">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="{{ URL::asset('build/img/customer/customer15.jpg') }}" alt="Img"
                    class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
            <p class="fs-10 mb-0">System Admin</p>
        </div>
        <div class="sidebar-nav mb-3">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="{{ url('chat') }}">Chats</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="{{ url('email') }}">Inbox</a></li>
            </ul>
        </div>
    </div>
    <div class="sidebar-header p-3 pb-0 pt-2">
        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
            <div class="avatar avatar-md onlin">
                <img src="{{ URL::asset('build/img/customer/customer15.jpg') }}" alt="Img"
                    class="img-fluid rounded-circle">
            </div>
            <div class="text-start sidebar-profile-info ms-2">
                <h6 class="fs-12 fw-normal mb-1">Adrian Herman</h6>
                <p class="fs-10">System Admin</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between menu-item mb-3">
            <div>
                <a href="{{ url('index') }}" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-layout-grid-remove"></i>
                </a>
            </div>
            <div>
                <a href="{{ url('chat') }}" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-brand-hipchat"></i>
                </a>
            </div>
            <div>
                <a href="{{ url('email') }}" class="btn btn-sm btn-icon bg-light position-relative">
                    <i class="ti ti-message"></i>
                </a>
            </div>
            <div class="notification-item">
                <a href="{{ url('activities') }}" class="btn btn-sm btn-icon bg-light position-relative">
                    <i class="ti ti-bell"></i>
                    <span class="notification-status-dot"></span>
                </a>
            </div>
            <div class="me-0">
                <a href="{{ url('general-settings') }}" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-settings"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ Request::is('dashboard/kepala-desa') ? 'active' : '' }}">
                    <a href="{{ url('dashboard/kepala-desa') }}">
                        <i class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ Route::is('farmers.*') ? 'active' : '' }}">
                    <a href="{{ route('farmers.index') }}">
                        <i class="ti ti-users-group fs-16 me-2"></i><span>Data Petani</span>
                    </a>
                </li>
                <li class="submenu {{ Request::is('laporan*') ? 'active' : '' }}">
                    <a href="javascript:void(0);"><i
                            class="ti ti-file-analytics fs-16 me-2"></i><span>Laporan</span><span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('laporan/pupuk') }}">Pergerakan Pupuk</a></li>
                        <li><a href="{{ url('laporan/subsidi') }}">Alokasi Subsidi & Realisasi</a></li>
                        <li><a href="{{ url('laporan/keuangan') }}">Laporan Keuangan</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('signout') }}"><i class="ti ti-logout fs-16 me-2"></i><span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
