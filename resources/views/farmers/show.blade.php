<?php 
// resources/views/farmers/show.blade.php (Updated)
$page = 'farmers'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Detail Data Petani</h4>
                <h6>{{ $farmer->farmer_name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmers.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Petani</h5>
                <div>
                    @if ($farmer->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIK:</label>
                            <p>{{ $farmer->nik }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama:</label>
                            <p>{{ $farmer->farmer_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat:</label>
                            <p>{{ $farmer->address }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telepon:</label>
                            <p>{{ $farmer->phone_number ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir:</label>
                            <p>{{ $farmer->birth_date ? $farmer->birth_date->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin:</label>
                            <p>{{ $farmer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Lahan:</label>
                            <p>{{ $farmer->land_area ?? '-' }} Ha</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Lahan:</label>
                            <p>{{ $farmer->land_location ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Lahan:</label>
                            <p>{{ $farmer->land_status ? ucfirst($farmer->land_status) : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Komoditas Utama:</label>
                            <p>{{ $farmer->main_commodity ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rata-rata Hasil Panen:</label>
                            <p>{{ $farmer->average_harvest ?? '-' }} Ton per Musim</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Registrasi:</label>
                            <p>{{ $farmer->created_at ? $farmer->created_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
