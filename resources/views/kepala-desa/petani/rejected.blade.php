<?php
$page = 'petani';
?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4 class="fw-bold" style="color: #ff9933;">Data Pengajuan Ditolak</h4>
                    <h6>Riwayat pengajuan data petani yang tidak disetujui</h6>
                </div>
            </div>

            <!-- Statistik -->
            <div class="row">
                <div class="col-xl-6 col-sm-6 col-12">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-icon">
                                    <i class="ti ti-x"></i>
                                </div>
                                <div class="db-info text-white">
                                    <h3>{{ $stats['total'] }}</h3>
                                    <h6>Total Ditolak</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6 col-12">
                    <div class="card" style="background: #ff9933;">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-icon">
                                    <i class="ti ti-calendar"></i>
                                </div>
                                <div class="db-info text-white">
                                    <h3>{{ $stats['recent'] }}</h3>
                                    <h6>30 Hari Terakhir</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-header" style="background: #ff9933; border-color: #ff9933;">
                    <h5 class="fw-bold mb-0 text-white">Daftar Pengajuan Ditolak</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table" id="rejectedTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Alasan Penolakan</th>
                                    <th>Tanggal Penolakan</th>
                                    <th class="no-sort">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr>
                                        <td>{{ $submission->nik }}</td>
                                        <td>{{ $submission->farmer_name }}</td>
                                        <td>{{ Str::limit($submission->address, 25) }}</td>
                                        <td>{{ Str::limit($submission->rejection_reason, 40) }}</td>
                                        <td>{{ $submission->validated_at ? $submission->validated_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 mb-0 btn-action"
                                                    href="{{ route('kepala-desa.petani.submission.show', $submission->id) }}"
                                                    data-bs-toggle="tooltip" title="Lihat Detail" style="color: #ff9933;">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#rejectedTable').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data pengajuan ditolak",
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
                "order": [
                    [4, 'desc']
                ], // Sort by rejection date
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "pageLength": 10,
                "responsive": true
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Re-initialize Feather icons setelah DataTable draw
            table.on('draw', function() {
                feather.replace();
            });
        });
    </script>

    <style>
        .btn-action:hover {
            background: rgba(255, 153, 51, 0.1) !important;
            border-radius: 4px;
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
@endpush
