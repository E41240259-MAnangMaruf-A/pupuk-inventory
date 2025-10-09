<?php
$page = 'petani';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold" style="color: #ff9933;">Detail Pengajuan Petani</h4>
                <h6>Informasi lengkap pengajuan data petani</h6>
            </div>
            <div class="page-btn">
                @if($submission->status == 'pending')
                    <a href="{{ route('kepala-desa.petani.pending') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                @elseif($submission->status == 'rejected')
                    <a href="{{ route('kepala-desa.petani.rejected') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                @else
                    <a href="{{ route('kepala-desa.petani.validated') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                        <h5 class="fw-bold mb-0 text-white">Informasi Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">NIK</label>
                                    <p class="form-control-plaintext">{{ $submission->nik }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Nama Lengkap</label>
                                    <p class="form-control-plaintext">{{ $submission->farmer_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Tanggal Lahir</label>
                                    <p class="form-control-plaintext">{{ $submission->birth_date ? \Carbon\Carbon::parse($submission->birth_date)->format('d/m/Y') : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Jenis Kelamin</label>
                                    <p class="form-control-plaintext">
                                        @if($submission->gender == 'L')
                                            Laki-laki
                                        @else
                                            Perempuan
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Alamat</label>
                            <p class="form-control-plaintext">{{ $submission->address }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Nomor Telepon</label>
                            <p class="form-control-plaintext">{{ $submission->phone_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                        <h5 class="fw-bold mb-0 text-white">Informasi Lahan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Luas Lahan</label>
                                    <p class="form-control-plaintext">{{ $submission->land_area ? $submission->land_area . ' Ha' : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Status Lahan</label>
                                    <p class="form-control-plaintext">
                                        @if($submission->land_status == 'milik')
                                            Milik Sendiri
                                        @elseif($submission->land_status == 'sewa')
                                            Sewa
                                        @elseif($submission->land_status == 'garap')
                                            Garap
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Lokasi Lahan</label>
                            <p class="form-control-plaintext">{{ $submission->land_location ?? '-' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Komoditas Utama</label>
                            <p class="form-control-plaintext">{{ $submission->main_commodity ?? '-' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Rata-rata Panen</label>
                            <p class="form-control-plaintext">{{ $submission->average_harvest ? $submission->average_harvest . ' Ton/Ha' : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                        <h5 class="fw-bold mb-0 text-white">Status Pengajuan</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group text-center">
                            <label class="fw-bold">Status</label>
                            <div class="mt-2">
                                @if ($submission->status == 'pending')
                                    <span class="badge bg-warning p-2 fs-6">Menunggu Validasi</span>
                                @elseif ($submission->status == 'approved')
                                    <span class="badge bg-success p-2 fs-6">Disetujui</span>
                                @else
                                    <span class="badge bg-danger p-2 fs-6">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="fw-bold">Tanggal Pengajuan</label>
                            <p class="form-control-plaintext">{{ $submission->submitted_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($submission->status == 'rejected')
                        <div class="form-group">
                            <label class="fw-bold">Alasan Penolakan</label>
                            <p class="form-control-plaintext text-danger">{{ $submission->rejection_reason }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Tanggal Penolakan</label>
                            <p class="form-control-plaintext">{{ $submission->validated_at ? $submission->validated_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                        @endif

                        @if($submission->status == 'approved')
                        <div class="form-group">
                            <label class="fw-bold">Tanggal Validasi</label>
                            <p class="form-control-plaintext">{{ $submission->validated_at ? $submission->validated_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection