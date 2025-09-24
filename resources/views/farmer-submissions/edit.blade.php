<?php 
// resources/views/farmer-submissions/edit.blade.php
$page = 'farmer-submissions'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Edit Pengajuan Data Petani</h4>
                <h6>{{ $farmerSubmission->farmer_name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmer-submissions.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('farmer-submissions.update', $farmerSubmission->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                       value="{{ old('nik', $farmerSubmission->nik) }}" maxlength="16" required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="farmer_name" class="form-control @error('farmer_name') is-invalid @enderror" 
                                       value="{{ old('farmer_name', $farmerSubmission->farmer_name) }}" required>
                                @error('farmer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address', $farmerSubmission->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="phone_number" class="form-control" 
                                       value="{{ old('phone_number', $farmerSubmission->phone_number) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control" 
                                       value="{{ old('birth_date', $farmerSubmission->birth_date) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Pilih Gender</option>
                                    <option value="L" {{ old('gender', $farmerSubmission->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $farmerSubmission->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Luas Lahan (Ha)</label>
                                <input type="number" step="0.01" name="land_area" class="form-control" 
                                       value="{{ old('land_area', $farmerSubmission->land_area) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lokasi Lahan (Desa/Kecamatan)</label>
                                <input type="text" name="land_location" class="form-control" 
                                       value="{{ old('land_location', $farmerSubmission->land_location) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status Lahan</label>
                                <select name="land_status" class="form-select">
                                    <option value="">Pilih Status</option>
                                    <option value="milik" {{ old('land_status', $farmerSubmission->land_status) == 'milik' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="sewa" {{ old('land_status', $farmerSubmission->land_status) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="garap" {{ old('land_status', $farmerSubmission->land_status) == 'garap' ? 'selected' : '' }}>Penggarap</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Komoditas Utama</label>
                                <input type="text" name="main_commodity" class="form-control" 
                                       value="{{ old('main_commodity', $farmerSubmission->main_commodity) }}" 
                                       placeholder="Padi, Jagung, Kedelai, dll">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rata-rata Hasil Panen (Ton per Musim)</label>
                                <input type="number" step="0.01" name="average_harvest" class="form-control" 
                                       value="{{ old('average_harvest', $farmerSubmission->average_harvest) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection