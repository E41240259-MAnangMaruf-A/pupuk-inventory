<?php
// resources/views/farmers/index.blade.php (Updated)
$page = 'farmers'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Data Petani</h4>
                        <h6>Kelola Data Petani Tervalidasi</h6>
                    </div>
                </div>
                <div class="page-btn d-flex gap-2">
                    @if (Auth::user()->role === 'admin_koperasi')
                        @if ($pendingSubmissions > 0)
                            <a href="{{ route('farmers.submissions') }}" class="btn btn-warning">
                                <i class="ti ti-clock me-1"></i> Pengajuan Menunggu ({{ $pendingSubmissions }})
                            </a>
                        @endif
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-farmer">
                            <i class="ti ti-circle-plus me-1"></i>Tambah Petani
                        </a>
                    @endif
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- farmer list -->
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">List Data Petani Tervalidasi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Nomor Telepon</th>
                                    <th>Gender</th>
                                    <th>Luas Lahan</th>
                                    <th>Lokasi Lahan</th>
                                    <th>Status Lahan</th>
                                    <th>Komoditas</th>
                                    <th>Rata-rata Panen</th>
                                    <th>Status</th>
                                    <th class="no-sort">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($farmers as $farmer)
                                    <tr>
                                        <td>{{ $farmer->nik }}</td>
                                        <td>{{ $farmer->farmer_name }}</td>
                                        <td>{{ Str::limit($farmer->address, 30) }}</td>
                                        <td>{{ $farmer->phone_number ?? '-' }}</td>
                                        <td>{{ $farmer->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $farmer->land_area ?? '-' }} Ha</td>
                                        <td>{{ $farmer->land_location ?? '-' }}</td>
                                        <td>
                                            @if ($farmer->land_status)
                                                <span class="badge bg-info">{{ ucfirst($farmer->land_status) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $farmer->main_commodity ?? '-' }}</td>
                                        <td>{{ $farmer->average_harvest ?? '-' }} Ton</td>
                                        <td>
                                            @if ($farmer->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 mb-0" href="{{ route('farmers.show', $farmer->id) }}">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a>
                                                @if (Auth::user()->role === 'admin_koperasi')
                                                    <a class="me-2 p-2 mb-0" data-bs-toggle="modal"
                                                        data-bs-target="#edit-farmer-{{ $farmer->id }}">
                                                        <i data-feather="edit" class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('farmers.destroy', $farmer->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 mb-0 btn btn-link text-danger"
                                                            onclick="return confirm('Yakin hapus data petani ini?')">
                                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Farmer Modal -->
                                    <div class="modal fade" id="edit-farmer-{{ $farmer->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('farmers.update', $farmer->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Petani</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">NIK</label>
                                                                    <input type="text" name="nik"
                                                                        class="form-control" value="{{ $farmer->nik }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama</label>
                                                                    <input type="text" name="farmer_name"
                                                                        class="form-control"
                                                                        value="{{ $farmer->farmer_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Alamat</label>
                                                                    <textarea name="address" class="form-control" required>{{ $farmer->address }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor Telepon</label>
                                                                    <input type="text" name="phone_number"
                                                                        class="form-control"
                                                                        value="{{ $farmer->phone_number }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tanggal Lahir</label>
                                                                    <input type="date" name="birth_date"
                                                                        class="form-control"
                                                                        value="{{ $farmer->birth_date }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Jenis Kelamin</label>
                                                                    <select name="gender" class="form-select" required>
                                                                        <option value="L"
                                                                            {{ $farmer->gender == 'L' ? 'selected' : '' }}>
                                                                            Laki-laki</option>
                                                                        <option value="P"
                                                                            {{ $farmer->gender == 'P' ? 'selected' : '' }}>
                                                                            Perempuan</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Luas Lahan (Ha)</label>
                                                                    <input type="number" step="0.01" name="land_area"
                                                                        class="form-control"
                                                                        value="{{ $farmer->land_area }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Lokasi Lahan</label>
                                                                    <input type="text" name="land_location"
                                                                        class="form-control"
                                                                        value="{{ $farmer->land_location }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status Lahan</label>
                                                                    <select name="land_status" class="form-select">
                                                                        <option value="">Pilih Status</option>
                                                                        <option value="milik"
                                                                            {{ $farmer->land_status == 'milik' ? 'selected' : '' }}>
                                                                            Milik Sendiri</option>
                                                                        <option value="sewa"
                                                                            {{ $farmer->land_status == 'sewa' ? 'selected' : '' }}>
                                                                            Sewa</option>
                                                                        <option value="garap"
                                                                            {{ $farmer->land_status == 'garap' ? 'selected' : '' }}>
                                                                            Penggarap</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Komoditas Utama</label>
                                                                    <input type="text" name="main_commodity"
                                                                        class="form-control"
                                                                        value="{{ $farmer->main_commodity }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Rata-rata Panen (Ton)</label>
                                                                    <input type="number" step="0.01"
                                                                        name="average_harvest" class="form-control"
                                                                        value="{{ $farmer->average_harvest }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Status</label>
                                                                    <select name="status" class="form-select">
                                                                        <option value="active"
                                                                            {{ $farmer->status == 'active' ? 'selected' : '' }}>
                                                                            Active</option>
                                                                        <option value="inactive"
                                                                            {{ $farmer->status == 'inactive' ? 'selected' : '' }}>
                                                                            Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary me-2"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Data
                                                            Petani</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /farmer list -->
        </div>
    </div>

    <!-- Add Farmer Modal -->
    <div class="modal fade" id="add-farmer" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('farmers.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Petani</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="farmer_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" name="phone_number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Luas Lahan (Ha)</label>
                                    <input type="number" step="0.01" name="land_area" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Lahan</label>
                                    <input type="text" name="land_location" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Lahan</label>
                                    <select name="land_status" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="milik">Milik Sendiri</option>
                                        <option value="sewa">Sewa</option>
                                        <option value="garap">Penggarap</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Komoditas Utama</label>
                                    <input type="text" name="main_commodity" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rata-rata Panen (Ton)</label>
                                    <input type="number" step="0.01" name="average_harvest" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan Data Petani</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
