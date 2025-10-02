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
                <li><a href="{{ url('dashboard/admin-koperasi') }}"
                        class="{{ Request::is('dashboard/admin-koperasi') ? 'active' : '' }}"><i
                            class="ti ti-layout-grid fs-16 me-2"></i><span>Dashboard</span></a></li>

                <!-- Data Petani Menu dengan Badge -->
                <li class="{{ Request::is('petani*') ? 'active' : '' }}">
                    <a href="{{ route('farmers.index') }}">
                        <i class="ti ti-users-group fs-16 me-2"></i>
                        <span>Data Petani Desa</span>
                        @php
                            $pendingCount = App\Models\FarmerSubmission::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>

                <li><a href="{{ route('fertilizers.index') }}" class="{{ Request::is('pupuk*') ? 'active' : '' }}"><i
                            class="ti ti-box fs-16 me-2"></i><span>Pupuk</span></a></li>
                <li><a href="{{ route('fertilizers.stock') }}"
                        class="{{ Request::is('stok-pupuk') ? 'active' : '' }}"><i
                            class="ti ti-trending-up-2 fs-16 me-2"></i><span>Stok Pupuk</span></a></li>
                <li><a href="{{ route('fertilizers.stock-subsidies') }}"
                        class="{{ Request::is('subsidi-pupuk') ? 'active' : '' }}"><i
                            class="ti ti-stairs-up fs-16 me-2"></i><span>Alokasi Subsidi</span></a></li>

                <li><a href="{{ route('transactions.index') }}" class="{{ Route::is('transactions*') ? 'active' : '' }}"><i
                            class="ti ti-cash fs-16 me-2"></i><span>Transaksi</span></a></li>
                <li><a href="{{ url('invoice') }}" class="{{ Request::is('invoice') ? 'active' : '' }}"><i
                            class="ti ti-file-invoice fs-16 me-2"></i><span>Riwayat Transaksi</span></a></li>

                <li class="submenu">
                    <a href="javascript:void(0);" class="{{ Request::is('laporan*') ? 'active' : '' }}"><i
                            class="ti ti-report fs-16 me-2"></i><span>Laporan Inventori</span><span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ url('laporan/transaksi') }}">Laporan Transaksi</a></li>
                        <li><a href="{{ url('laporan/stok') }}">Laporan Stok Pupuk</a></li>
                        <li><a href="{{ url('laporan/subsidi') }}">Laporan Subsidi</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('signout') }}"><i class="ti ti-logout fs-16 me-2"></i><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
</div>
