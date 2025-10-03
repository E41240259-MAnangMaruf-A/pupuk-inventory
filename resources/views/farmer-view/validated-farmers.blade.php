<?php
// resources/views/farmer-view/validated-farmers.blade.php
$page = 'farmer-view';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header" style="background: linear-gradient(135deg, #FF6B35 0%, #FF8E53 100%); border-radius: 10px; padding: 2rem;">
            <div class="page-title">
                <h4 class="fw-bold text-white">
                    <i class="ti ti-user-check me-2"></i>DATA PETANI TERVALIDASI
                </h4>
                <h6 class="text-white mb-0">Daftar Lengkap Petani Penerima Manfaat Aktif</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmer-view.index') }}" class="btn btn-light text-orange">
                    <i class="ti ti-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mt-4">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-orange text-white" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['total'] }}</h2>
                        <p class="mb-0">Total Petani</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-primary text-white" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['male_count'] }}</h2>
                        <p class="mb-0">Petani Laki-laki</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-info text-white" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $stats['female_count'] }}</h2>
                        <p class="mb-0">Petani Perempuan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-warning text-dark" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ number_format($stats['average_land'], 2) }} Ha</h2>
                        <p class="mb-0">Rata-rata Lahan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mt-4" style="border-radius: 10px;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ route('farmer-view.search-farmers') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari berdasarkan NIK, nama, alamat, atau komoditas..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-orange text-white" type="submit">
                                    <i class="ti ti-search me-1"></i>Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('farmer-view.export-validated-farmers') }}" 
                               class="btn btn-success">
                                <i class="ti ti-download me-1"></i>Export CSV
                            </a>
                            <button class="btn btn-outline-orange" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="ti ti-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Farmers Table -->
        <div class="card mt-4" style="border-radius: 10px;">
            <div class="card-header bg-light-orange d-flex justify-content-between align-items-center" style="border-radius: 10px 10px 0 0;">
                <h5 class="fw-bold mb-0 text-orange">
                    <i class="ti ti-list me-2"></i>
                    Daftar Petani Tervalidasi
                </h5>
                <span class="badge bg-orange">{{ $validatedFarmers->total() }} Data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="bg-light-orange">#</th>
                                <th class="bg-light-orange">NIK</th>
                                <th class="bg-light-orange">Nama Petani</th>
                                <th class="bg-light-orange">Alamat</th>
                                <th class="bg-light-orange">Gender</th>
                                <th class="bg-light-orange">Luas Lahan</th>
                                <th class="bg-light-orange">Komoditas</th>
                                <th class="bg-light-orange">Status</th>
                                <th class="bg-light-orange text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($validatedFarmers as $index => $farmer)
                            <tr>
                                <td>{{ $validatedFarmers->firstItem() + $index }}</td>
                                <td><code class="text-orange">{{ $farmer->nik }}</code></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-success bg-opacity-10 rounded me-2">
                                            <i class="ti ti-user-check fs-12 text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium text-dark">{{ $farmer->farmer_name }}</div>
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
                                    <span class="badge bg-light-orange text-dark">{{ $farmer->land_area ?? '0' }} Ha</span>
                                </td>
                                <td>
                                    <small class="badge bg-light text-dark">{{ $farmer->main_commodity ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="ti ti-check me-1"></i>Aktif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('farmer-view.farmer-detail', $farmer->id) }}" 
                                       class="btn btn-sm btn-outline-orange"
                                       data-bs-toggle="tooltip"
                                       title="Lihat Detail Lengkap">
                                        <i class="ti ti-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="ti ti-users-off fs-1"></i>
                                        <p class="mt-2 mb-0">Belum ada data petani tervalidasi</p>
                                        <small>Data petani yang sudah divalidasi akan muncul di sini</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($validatedFarmers->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $validatedFarmers->firstItem() }} - {{ $validatedFarmers->lastItem() }} 
                                dari {{ $validatedFarmers->total() }} data
                            </small>
                        </div>
                        <div>
                            {{ $validatedFarmers->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-orange text-white">
                <h5 class="modal-title">
                    <i class="ti ti-filter me-2"></i>Filter Data Petani
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('farmer-view.filter-farmers') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="all">Semua Gender</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Lahan</label>
                        <select name="land_status" class="form-select">
                            <option value="all">Semua Status</option>
                            <option value="milik">Milik Sendiri</option>
                            <option value="sewa">Sewa</option>
                            <option value="garap">Penggarap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komoditas</label>
                        <input type="text" name="commodity" class="form-control" placeholder="Contoh: Padi, Jagung, dll">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-orange text-white">Terapkan Filter</button>
                </div>
            </form>
        </div>
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

<style>
.btn-orange {
    background-color: #FF6B35 !important;
    border-color: #FF6B35 !important;
    color: white !important;
}

.btn-orange:hover {
    background-color: #E55A2B !important;
    border-color: #E55A2B !important;
}

.btn-outline-orange {
    border-color: #FF6B35 !important;
    color: #FF6B35 !important;
}

.btn-outline-orange:hover {
    background-color: #FF6B35 !important;
    color: white !important;
}

.bg-light-orange {
    background-color: #FFE8DC !important;
}

.text-orange {
    color: #FF6B35 !important;
}

.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection