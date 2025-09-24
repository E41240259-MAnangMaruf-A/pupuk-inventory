<?php 
// resources/views/farmer-submissions/show.blade.php
$page = 'farmer-submissions'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Detail Pengajuan Petani</h4>
                <h6>{{ $farmerSubmission->farmer_name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmer-submissions.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Pengajuan</h5>
                <div>
                    @if ($farmerSubmission->status == 'pending')
                        <span class="badge bg-warning">Menunggu Validasi</span>
                    @elseif ($farmerSubmission->status == 'approved')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIK:</label>
                            <p>{{ $farmerSubmission->nik }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama:</label>
                            <p>{{ $farmerSubmission->farmer_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat:</label>
                            <p>{{ $farmerSubmission->address }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telepon:</label>
                            <p>{{ $farmerSubmission->phone_number ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir:</label>
                            <p>{{ $farmerSubmission->birth_date ? $farmerSubmission->birth_date->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin:</label>
                            <p>{{ $farmerSubmission->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Lahan:</label>
                            <p>{{ $farmerSubmission->land_area ?? '-' }} Ha</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Lahan:</label>
                            <p>{{ $farmerSubmission->land_location ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Lahan:</label>
                            <p>{{ $farmerSubmission->land_status ? ucfirst($farmerSubmission->land_status) : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Komoditas Utama:</label>
                            <p>{{ $farmerSubmission->main_commodity ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rata-rata Hasil Panen:</label>
                            <p>{{ $farmerSubmission->average_harvest ?? '-' }} Ton</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Pengajuan:</label>
                            <p>{{ $farmerSubmission->submitted_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if($farmerSubmission->status == 'rejected' && $farmerSubmission->rejection_reason)
                    <div class="alert alert-danger">
                        <h6><i class="ti ti-alert-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0">{{ $farmerSubmission->rejection_reason }}</p>
                    </div>
                @endif

                @if($farmerSubmission->validated_at)
                    <div class="mt-3 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Divalidasi pada:</strong> {{ $farmerSubmission->validated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Divalidasi oleh:</strong> {{ $farmerSubmission->validator->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection