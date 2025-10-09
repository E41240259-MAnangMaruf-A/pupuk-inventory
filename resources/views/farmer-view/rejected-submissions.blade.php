<?php
// resources/views/farmer-view/rejected-submissions.blade.php
$page = 'farmer-view';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Data Pengajuan Ditolak</h4>
                <h6>Daftar Pengajuan yang Ditolak beserta Alasan</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmer-view.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Rejected Submissions -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Penolakan Pengajuan</h5>
                <span class="badge bg-danger">{{ $rejectedSubmissions->total() }} Data Ditolak</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pengaju</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>Alasan Penolakan</th>
                                <th>Tanggal Ditolak</th>
                                <th>Divalidasi Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rejectedSubmissions as $submission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-danger bg-opacity-10 rounded me-2">
                                            <i class="ti ti-user-x fs-12 text-danger"></i>
                                        </div>
                                        <div class="fw-medium">{{ $submission->farmer_name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $submission->nik }}</code>
                                </td>
                                <td>
                                    <small data-bs-toggle="tooltip" title="{{ $submission->address }}">
                                        {{ Str::limit($submission->address, 25) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="text-danger" data-bs-toggle="tooltip"
                                         title="{{ $submission->rejection_reason }}">
                                        <i class="ti ti-alert-circle me-1"></i>
                                        {{ Str::limit($submission->rejection_reason, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($submission->validated_at)
                                            {{ $submission->validated_at->format('d/m/Y H:i') }}
                                        @else
                                            {{ $submission->updated_at->format('d/m/Y H:i') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $submission->validator->name ?? 'System' }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('farmer-view.submission-detail', $submission->id) }}" 
                                       class="btn btn-sm btn-outline-danger"
                                       data-bs-toggle="tooltip"
                                       title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="ti ti-mood-smile fs-2 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data pengajuan yang ditolak</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($rejectedSubmissions->hasPages())
                <div class="card-footer">
                    {{ $rejectedSubmissions->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card bg-light-danger">
                    <div class="card-body text-center">
                        <i class="ti ti-alert-triangle fs-2 text-danger mb-2"></i>
                        <h3 class="fw-bold text-danger">{{ $rejectedSubmissions->total() }}</h3>
                        <p class="mb-0 text-danger">Total Pengajuan Ditolak</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light-warning">
                    <div class="card-body text-center">
                        <i class="ti ti-refresh fs-2 text-warning mb-2"></i>
                        <h3 class="fw-bold text-warning">{{ $rejectedSubmissions->where('rejection_reason', '!=', '')->count() }}</h3>
                        <p class="mb-0 text-warning">Dengan Alasan Spesifik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection