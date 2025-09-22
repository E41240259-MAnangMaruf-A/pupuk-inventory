<?php $page = 'add-product'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Tambah Jenis Pupuk</h4>
                        <h6>Buat jenis pupuk baru</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn mt-0">
                    <a href="{{ url('fertilizers') }}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Kembali ke Daftar Pupuk</a>
                </div>
            </div>
            
            <!-- Cooperative Info -->
            <div class="alert alert-info mb-4">
                <strong>Koperasi: {{ $cooperative->cooperative_name ?? 'Koperasi Merah Putih Desa Suci' }}</strong><br>
                <small>{{ $cooperative->address ?? 'Desa Suci, Kecamatan Mangli, Kabupaten Jember' }}</small>
            </div>
            
            <form action="{{ route('fertilizer.store') }}" method="POST" class="add-product-form">
                @csrf
                <div class="add-product">
                    <div class="accordions-items-seperate" id="accordionSpacingExample">
                        <div class="accordion-item border mb-4">
                            <h2 class="accordion-header" id="headingSpacingOne">
                                <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingOne" aria-expanded="true" aria-controls="SpacingOne">
                                    <div class="d-flex align-items-center justify-content-between flex-fill">
                                        <h5 class="d-flex align-items-center"><i data-feather="info" class="text-primary me-2"></i><span>Informasi Pupuk</span></h5>
                                    </div>
                                </div>
                            </h2>
                            <div id="SpacingOne" class="accordion-collapse collapse show" aria-labelledby="headingSpacingOne">
                                <div class="accordion-body border-top">
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Kode Pupuk<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control @error('fertilizer_code') is-invalid @enderror" 
                                                       name="fertilizer_code" value="{{ old('fertilizer_code') }}" required>
                                                @error('fertilizer_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pupuk<span class="text-danger ms-1">*</span></label>
                                                <input type="text" class="form-control @error('fertilizer_name') is-invalid @enderror" 
                                                       name="fertilizer_name" value="{{ old('fertilizer_name') }}" required>
                                                @error('fertilizer_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                                                <select class="select @error('unit') is-invalid @enderror" name="unit" required>
                                                    <option value="">Pilih Unit</option>
                                                    <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                                                    <option value="Sak" {{ old('unit') == 'Sak' ? 'selected' : '' }}>Sak</option>
                                                    <option value="Ton" {{ old('unit') == 'Ton' ? 'selected' : '' }}>Ton</option>
                                                    <option value="Karung" {{ old('unit') == 'Karung' ? 'selected' : '' }}>Karung</option>
                                                    <option value="Liter" {{ old('unit') == 'Liter' ? 'selected' : '' }}>Liter</option>
                                                </select>
                                                @error('unit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                                <select class="select @error('is_active') is-invalid @enderror" name="is_active" required>
                                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                                @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Harga Subsidi (Rp)<span class="text-danger ms-1">*</span></label>
                                                <input type="number" class="form-control @error('subsidized_price') is-invalid @enderror" 
                                                       name="subsidized_price" value="{{ old('subsidized_price') }}" min="0" step="0.01" required>
                                                @error('subsidized_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Harga Eceran (Rp)<span class="text-danger ms-1">*</span></label>
                                                <input type="number" class="form-control @error('retail_price') is-invalid @enderror" 
                                                       name="retail_price" value="{{ old('retail_price') }}" min="0" step="0.01" required>
                                                @error('retail_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                                          name="description" rows="3" placeholder="Deskripsi singkat tentang pupuk">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border mb-4"> 
                            <h2 class="accordion-header" id="headingSpacingTwo">
                                <div class="accordion-button collapsed bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingTwo" aria-expanded="true" aria-controls="SpacingTwo">
                                    <div class="d-flex align-items-center justify-content-between flex-fill">
                                        <h5 class="d-flex align-items-center"><i data-feather="life-buoy" class="text-primary me-2"></i><span>Stok Awal</span></h5>
                                    </div>
                                </div>
                            </h2>
                            <div id="SpacingTwo" class="accordion-collapse collapse show" aria-labelledby="headingSpacingTwo">
                                <div class="accordion-body border-top">
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Stok Awal<span class="text-danger ms-1">*</span></label>
                                                <input type="number" class="form-control @error('initial_stock') is-invalid @enderror" 
                                                       name="initial_stock" value="{{ old('initial_stock', 0) }}" min="0" required>
                                                @error('initial_stock')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Update Stok<span class="text-danger ms-1">*</span></label>
                                                <div class="input-groupicon calender-input">
                                                    <i data-feather="calendar" class="info-img"></i>
                                                    <input type="date" class="form-control datetimepicker @error('update_date') is-invalid @enderror" 
                                                           name="update_date" value="{{ old('update_date', date('Y-m-d')) }}" required>
                                                </div>
                                                @error('update_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Catatan Stok</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                          name="notes" rows="2" placeholder="Catatan tentang stok awal">{{ old('notes') }}</textarea>
                                                @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <a href="{{ url('fertilizers') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pupuk</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2014 - 2025 &copy; Sistem Inventori Pupuk. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">Tim Pengembang</a></p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize datepicker
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false
    });
    
    // Form validation
    $('.add-product-form').validate({
        rules: {
            fertilizer_code: {
                required: true,
                minlength: 3
            },
            fertilizer_name: {
                required: true,
                minlength: 3
            },
            subsidized_price: {
                required: true,
                min: 0
            },
            retail_price: {
                required: true,
                min: 0
            },
            initial_stock: {
                required: true,
                min: 0
            }
        },
        messages: {
            fertilizer_code: {
                required: "Kode pupuk harus diisi",
                minlength: "Kode pupuk minimal 3 karakter"
            },
            fertilizer_name: {
                required: "Nama pupuk harus diisi",
                minlength: "Nama pupuk minimal 3 karakter"
            },
            subsidized_price: {
                required: "Harga subsidi harus diisi",
                min: "Harga subsidi tidak boleh negatif"
            },
            retail_price: {
                required: "Harga eceran harus diisi",
                min: "Harga eceran tidak boleh negatif"
            },
            initial_stock: {
                required: "Stok awal harus diisi",
                min: "Stok awal tidak boleh negatif"
            }
        }
    });
});
</script>
@endpush