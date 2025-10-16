<?php $page = 'reports'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Informasi Database</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center py-5">
                <div class="alert alert-warning">
                    <i class="ti ti-database-off fs-1 text-warning mb-3"></i>
                    <h4 class="alert-heading">Tabel Database Belum Tersedia</h4>
                    <p class="mb-3">Tabel <strong>{{ $tableName }}</strong> belum ada dalam database.</p>
                    <hr>
                    <p class="mb-0">
                        Untuk menggunakan fitur ini, Anda perlu:<br>
                        1. Membuat migration untuk tabel yang diperlukan<br>
                        2. Menjalankan <code>php artisan migrate</code><br>
                        3. Mengisi data melalui aplikasi
                    </p>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('kepala-desa.dashboard') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left me-1"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection