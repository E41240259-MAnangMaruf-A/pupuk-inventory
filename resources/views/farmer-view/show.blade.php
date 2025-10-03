<?php
$page = 'farmer-view';
?>
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
                <a href="{{ route('farmer-view.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informasi Utama -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-user me-2"></i>Informasi Pribadi
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>NIK</strong></td>
                                <td>: <code>{{ $farmer->nik }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: {{ $farmer->farmer_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>: {{ $farmer->birth_date ? $farmer->birth_date->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: 
                                    <span class="badge bg-{{ $farmer->gender == 'L' ? 'primary' : 'info' }}">
                                        {{ $farmer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $farmer->address }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Telepon</strong></td>
                                <td>: {{ $farmer->phone_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informasi Lahan -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-map-pin me-2"></i>Informasi Lahan & Pertanian
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Luas Lahan</strong></td>
                                <td>: <span class="badge bg-light text-dark">{{ $farmer->land_area ?? '0' }} Ha</span></td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi Lahan</strong></td>
                                <td>: {{ $farmer->land_location ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status Lahan</strong></td>
                                <td>: 
                                    @if($farmer->land_status)
                                        <span class="badge bg-warning text-dark">{{ ucfirst($farmer->land_status) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Komoditas Utama</strong></td>
                                <td>: {{ $farmer->main_commodity ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rata-rata Panen</strong></td>
                                <td>: <span class="badge bg-info">{{ $farmer->average_harvest ?? '0' }} Ton/Musim</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Timeline -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="ti ti-timeline me-2"></i>Status & Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Penerima Manfaat</label>
                                    <div>
                                        @if ($farmer->status == 'active')
                                            <span class="badge bg-success fs-6">
                                                <i class="ti ti-circle-check me-1"></i>
                                                PENERIMA MANFAAT AKTIF
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6">
                                                <i class="ti ti-circle-x me-1"></i>
                                                TIDAK AKTIF
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Registrasi</label>
                                    <div>
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $farmer->created_at->format('d F Y, H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($farmer->status == 'active')
                        <div class="alert alert-success mt-3">
                            <i class="ti ti-check-circle me-2"></i>
                            <strong>Petani ini telah tervalidasi dan berhak menerima manfaat pupuk bersubsidi.</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection