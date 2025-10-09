<?php
$page = 'petani';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold" style="color: #ff9933;">Detail Data Petani</h4>
                <h6>Informasi lengkap data petani tervalidasi</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('kepala-desa.petani.validated') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
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
                                    <p class="form-control-plaintext">{{ $farmer->nik }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Nama Lengkap</label>
                                    <p class="form-control-plaintext">{{ $farmer->farmer_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Tanggal Lahir</label>
                                    <p class="form-control-plaintext">{{ $farmer->birth_date ? \Carbon\Carbon::parse($farmer->birth_date)->format('d/m/Y') : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Jenis Kelamin</label>
                                    <p class="form-control-plaintext">
                                        @if($farmer->gender == 'L')
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
                            <p class="form-control-plaintext">{{ $farmer->address }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Nomor Telepon</label>
                            <p class="form-control-plaintext">{{ $farmer->phone_number ?? '-' }}</p>
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
                                    <p class="form-control-plaintext">{{ $farmer->land_area ? $farmer->land_area . ' Ha' : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fw-bold">Status Lahan</label>
                                    <p class="form-control-plaintext">
                                        @if($farmer->land_status == 'milik')
                                            Milik Sendiri
                                        @elseif($farmer->land_status == 'sewa')
                                            Sewa
                                        @elseif($farmer->land_status == 'garap')
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
                            <p class="form-control-plaintext">{{ $farmer->land_location ?? '-' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Komoditas Utama</label>
                            <p class="form-control-plaintext">{{ $farmer->main_commodity ?? '-' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Rata-rata Panen</label>
                            <p class="form-control-plaintext">{{ $farmer->average_harvest ? $farmer->average_harvest . ' Ton/Ha' : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                        <h5 class="fw-bold mb-0 text-white">Status & Informasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group text-center">
                            <label class="fw-bold">Status Petani</label>
                            <div class="mt-2">
                                @if ($farmer->status == 'active')
                                    <span class="badge bg-success p-2 fs-6">Aktif</span>
                                @else
                                    <span class="badge bg-danger p-2 fs-6">Non-Aktif</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="fw-bold">Tanggal Registrasi</label>
                            <p class="form-control-plaintext">{{ $farmer->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">Terakhir Diupdate</label>
                            <p class="form-control-plaintext">{{ $farmer->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection