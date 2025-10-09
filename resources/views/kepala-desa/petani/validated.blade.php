<?php
$page = 'petani';
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold" style="color: #ff9933;">Data Petani Tervalidasi</h4>
                <h6>Kelola data petani yang sudah tervalidasi</h6>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row">
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card" style="background: #ff9933;">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon">
                                <i class="ti ti-users"></i>
                            </div>
                            <div class="db-info text-white">
                                <h3>{{ $stats['total'] }}</h3>
                                <h6>Total Petani</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card bg-success">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon">
                                <i class="ti ti-user-check"></i>
                            </div>
                            <div class="db-info text-white">
                                <h3>{{ $stats['active'] }}</h3>
                                <h6>Petani Aktif</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="db-widgets d-flex justify-content-between align-items-center">
                            <div class="db-icon">
                                <i class="ti ti-user-off"></i>
                            </div>
                            <div class="db-info text-white">
                                <h3>{{ $stats['inactive'] }}</h3>
                                <h6>Petani Non-Aktif</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                <h5 class="fw-bold mb-0 text-white">Daftar Petani Tervalidasi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="validatedTable">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th class="no-sort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->nik }}</td>
                                <td>{{ $farmer->farmer_name }}</td>
                                <td>{{ Str::limit($farmer->address, 30) }}</td>
                                <td>{{ $farmer->phone_number ?? '-' }}</td>
                                <td>
                                    @if ($farmer->status == 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('kepala-desa.petani.show', $farmer->id) }}" 
                                       class="btn btn-sm btn-action" 
                                       style="background: #ff9933; color: white; border: none;"
                                       data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="ti ti-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data petani</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#validatedTable').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data petani",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya"
                }
            },
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false
            }],
            "order": [[1, 'asc']], // Sort by name
            "dom": '<"top"lf>rt<"bottom"ip><"clear">',
            "pageLength": 10,
            "responsive": true
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<style>
.btn-action:hover {
    background: #e68a2e !important;
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
}

.table th {
    background: #f8f9fa !important;
    color: #333 !important;
    font-weight: 600;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #ff9933 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #ff9933 !important;
    border-color: #ff9933 !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #ff9933 !important;
    border-color: #ff9933 !important;
    color: white !important;
}
</style>
@endsection