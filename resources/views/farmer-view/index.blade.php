<?php
// resources/views/farmer-view/index.blade.php
$page = 'farmer-view';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">View Data Petani</h4>
                <h6>Dashboard Overview Data Petani & Validasi</h6>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['total_validated'] }}</h2>
                        <p class="mb-0">Petani Tervalidasi</p>
                        <small>Penerima Manfaat Aktif</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['total_pending'] }}</h2>
                        <p class="mb-0">Menunggu Validasi</p>
                        <small>Pengajuan Baru</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['total_approved'] }}</h2>
                        <p class="mb-0">Disetujui</p>
                        <small>Pengajuan Diterima</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['total_rejected'] }}</h2>
                        <p class="mb-0">Ditolak</p>
                        <small>Dengan Alasan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h4 class="fw-bold text-primary">{{ $stats['male_farmers'] }}</h4>
                        <p class="mb-0 text-muted">Petani Laki-laki</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h4 class="fw-bold text-info">{{ $stats['female_farmers'] }}</h4>
                        <p class="mb-0 text-muted">Petani Perempuan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="row mt-4">
            
            <!-- Petani Tervalidasi -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="ti ti-user-check text-success me-2"></i>
                            Petani Tervalidasi (Penerima Manfaat Aktif)
                        </h5>
                        <a href="{{ route('farmer-view.validated-farmers') }}" class="btn btn-sm btn-success">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama Petani</th>
                                        <th>Alamat</th>
                                        <th>Gender</th>
                                        <th>Luas Lahan</th>
                                        <th>Komoditas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($validatedFarmers->take(5) as $farmer)
                                    <tr>
                                        <td><code>{{ $farmer->nik }}</code></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-success bg-opacity-10 rounded me-2">
                                                    <i class="ti ti-user-check fs-12 text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $farmer->farmer_name }}</div>
                                                    <small class="text-muted">{{ $farmer->phone_number ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small data-bs-toggle="tooltip" title="{{ $farmer->address }}">
                                                {{ Str::limit($farmer->address, 30) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $farmer->gender == 'L' ? 'primary' : 'info' }}">
                                                {{ $farmer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $farmer->land_area ?? '0' }} Ha</span>
                                        </td>
                                        <td>
                                            <small>{{ $farmer->main_commodity ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Aktif</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('farmer-view.farmer-detail', $farmer->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-users-off fs-2 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data petani tervalidasi</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Menunggu Validasi -->
            @if($pendingSubmissions->count() > 0)
            <div class="col-md-6 mt-4">
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h5 class="fw-bold mb-0 text-warning">
                            <i class="ti ti-clock me-2"></i>
                            Pengajuan Menunggu Validasi
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($pendingSubmissions->take(3) as $submission)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $submission->farmer_name }}</h6>
                                    <small class="text-muted">NIK: {{ $submission->nik }}</small>
                                    <br>
                                    <small class="text-muted">Diajukan: {{ $submission->submitted_at->format('d/m/Y') }}</small>
                                </div>
                                <span class="badge bg-warning">Menunggu</span>
                            </div>
                        </div>
                        @endforeach
                        @if($pendingSubmissions->count() > 3)
                        <div class="text-center mt-2">
                            <a href="{{ route('farmer-view.pending-submissions') }}" class="btn btn-sm btn-warning">
                                Lihat {{ $pendingSubmissions->count() - 3 }} lainnya
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Pengajuan Ditolak -->
            @if($rejectedSubmissions->count() > 0)
            <div class="col-md-6 mt-4">
                <div class="card border-danger">
                    <div class="card-header bg-danger bg-opacity-10">
                        <h5 class="fw-bold mb-0 text-danger">
                            <i class="ti ti-user-x me-2"></i>
                            Pengajuan Ditolak
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($rejectedSubmissions->take(3) as $submission)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $submission->farmer_name }}</h6>
                                    <small class="text-danger">
                                        <i class="ti ti-alert-triangle me-1"></i>
                                        {{ Str::limit($submission->rejection_reason, 40) }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        Ditolak: 
                                        @if($submission->validated_at)
                                            {{ $submission->validated_at->format('d/m/Y') }}
                                        @else
                                            {{ $submission->updated_at->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-danger">Ditolak</span>
                            </div>
                        </div>
                        @endforeach
                        @if($rejectedSubmissions->count() > 3)
                        <div class="text-center mt-2">
                            <a href="{{ route('farmer-view.rejected-submissions') }}" class="btn btn-sm btn-danger">
                                Lihat {{ $rejectedSubmissions->count() - 3 }} lainnya
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>

        <!-- Jika tidak ada data sama sekali -->
        @if($validatedFarmers->count() == 0 && $pendingSubmissions->count() == 0 && $rejectedSubmissions->count() == 0)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-database-off fs-1 text-muted"></i>
                <h5 class="mt-3">Belum Ada Data</h5>
                <p class="text-muted">Tidak ada data petani atau pengajuan yang tersedia.</p>
                <a href="{{ route('farmer-submissions.create') }}" class="btn btn-primary">
                    <i class="ti ti-user-plus me-1"></i>Ajukan Data Petani Baru
                </a>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection