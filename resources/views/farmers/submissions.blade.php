<?php 
// resources/views/farmers/submissions.blade.php (Halaman Validasi Pengajuan)
$page = 'farmers'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Validasi Pengajuan Data Petani</h4>
                <h6>Pengajuan yang Menunggu Persetujuan</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmers.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali ke Data Petani
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($submissions->count() == 0)
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ti ti-inbox fs-1 text-muted"></i>
                    <h5 class="mt-3">Tidak Ada Pengajuan</h5>
                    <p class="text-muted">Belum ada pengajuan data petani yang menunggu validasi.</p>
                </div>
            </div>
        @else
            @foreach($submissions as $submission)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ $submission->farmer_name }} - NIK: {{ $submission->nik }}</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" 
                                data-bs-target="#approve-modal-{{ $submission->id }}">
                            <i class="ti ti-check me-1"></i>Setujui
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#reject-modal-{{ $submission->id }}">
                            <i class="ti ti-x me-1"></i>Tolak
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Alamat:</strong> {{ $submission->address }}</p>
                            <p><strong>Telepon:</strong> {{ $submission->phone_number ?? '-' }}</p>
                            <p><strong>Tanggal Lahir:</strong> {{ $submission->birth_date ? $submission->birth_date->format('d/m/Y') : '-' }}</p>
                            <p><strong>Jenis Kelamin:</strong> {{ $submission->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            <p><strong>Luas Lahan:</strong> {{ $submission->land_area ?? '-' }} Ha</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Lokasi Lahan:</strong> {{ $submission->land_location ?? '-' }}</p>
                            <p><strong>Status Lahan:</strong> {{ $submission->land_status ? ucfirst($submission->land_status) : '-' }}</p>
                            <p><strong>Komoditas Utama:</strong> {{ $submission->main_commodity ?? '-' }}</p>
                            <p><strong>Rata-rata Panen:</strong> {{ $submission->average_harvest ?? '-' }} Ton</p>
                            <p><strong>Diajukan:</strong> {{ $submission->submitted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approve Modal -->
            <div class="modal fade" id="approve-modal-{{ $submission->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('farmer-submissions.validate', $submission->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <div class="modal-header">
                                <h5 class="modal-title">Setujui Pengajuan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menyetujui pengajuan data petani <strong>{{ $submission->farmer_name }}</strong>?</p>
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Data akan langsung ditambahkan ke database petani setelah disetujui.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Ya, Setujui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="reject-modal-{{ $submission->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('farmer-submissions.validate', $submission->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Pengajuan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Tolak pengajuan data petani <strong>{{ $submission->farmer_name }}</strong>?</p>
                                <div class="mb-3">
                                    <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea name="rejection_reason" class="form-control" rows="3" 
                                              placeholder="Masukkan alasan penolakan..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection