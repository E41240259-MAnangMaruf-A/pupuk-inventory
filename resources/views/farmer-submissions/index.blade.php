<?php 
// resources/views/farmer-submissions/index.blade.php
$page = 'farmer-submissions'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Data Petani Desa</h4>
                    <h6>Pengajuan Data Petani</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="{{ route('farmer-submissions.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i>Tambah Pengajuan
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

        <!-- Submissions list -->
        <div class="card">
            <div class="card-header">
                <h5 class="fw-bold mb-0">List Pengajuan Data Petani</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Gender</th>
                                <th>Luas Lahan</th>
                                <th>Lokasi Lahan</th>
                                <th>Status Lahan</th>
                                <th>Komoditas Utama</th>
                                <th>Rata-rata Panen</th>
                                <th>Status</th>
                                <th>Tanggal Ajuan</th>
                                <th class="no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->nik }}</td>
                                <td>{{ $submission->farmer_name }}</td>
                                <td>{{ Str::limit($submission->address, 30) }}</td>
                                <td>{{ $submission->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $submission->land_area ?? '-' }} Ha</td>
                                <td>{{ $submission->land_location ?? '-' }}</td>
                                <td>
                                    @if($submission->land_status)
                                        <span class="badge bg-info">{{ ucfirst($submission->land_status) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $submission->main_commodity ?? '-' }}</td>
                                <td>{{ $submission->average_harvest ?? '-' }} Ton</td>
                                <td>
                                    @if ($submission->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif ($submission->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $submission->submitted_at->format('d/m/Y') }}</td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 mb-0" href="{{ route('farmer-submissions.show', $submission->id) }}">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        @if($submission->status == 'pending')
                                            <a class="me-2 p-2 mb-0" href="{{ route('farmer-submissions.edit', $submission->id) }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('farmer-submissions.destroy', $submission->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 mb-0 btn btn-link text-danger" 
                                                        onclick="return confirm('Yakin hapus pengajuan ini?')">
                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection