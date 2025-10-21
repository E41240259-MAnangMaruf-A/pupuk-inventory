<?php 
$page = 'reports'; 
?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4 class="fw-bold">Laporan Pergerakan Pupuk</h4>
                    <h6>Monitoring Masuk dan Keluar Stok Pupuk</h6>
                </div>
                <div class="page-btn">
                    <button type="button" class="btn btn-primary" onclick="exportPDF()">
                        <i class="ti ti-download me-1"></i>Export PDF
                    </button>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Periode</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('kepala-desa.reports.fertilizer-movement') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-info flex-fill">
                                        <i class="ti ti-filter me-1"></i>Terapkan Filter
                                    </button>
                                    <a href="{{ route('kepala-desa.reports.fertilizer-movement') }}"
                                        class="btn btn-secondary">
                                        <i class="ti ti-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fw-bold">{{ number_format($summary['total_stock_in']) }}</h3>
                                    <p class="mb-0">Total Masuk (kg)</p>
                                </div>
                                <div class="avatar">
                                    <i class="ti ti-arrow-down-left fs-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fw-bold">{{ number_format($summary['total_stock_out']) }}</h3>
                                    <p class="mb-0">Total Keluar (kg)</p>
                                </div>
                                <div class="avatar">
                                    <i class="ti ti-arrow-up-right fs-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fw-bold">{{ number_format($summary['current_stock']) }}</h3>
                                    <p class="mb-0">Stok Saat Ini (kg)</p>
                                </div>
                                <div class="avatar">
                                    <i class="ti ti-package fs-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fw-bold">Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</h3>
                                    <p class="mb-0">Nilai Stok</p>
                                </div>
                                <div class="avatar">
                                    <i class="ti ti-currency-dollar fs-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTables -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Detail Pergerakan Stok Pupuk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="fertilizerMovementTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="13%">Tanggal</th>
                                    <th width="15%">Jenis Pupuk</th>
                                    <th width="10%">Tipe</th>
                                    <th width="10%">Perubahan</th>
                                    <th width="10%">Stok Awal</th>
                                    <th width="10%">Stok Akhir</th>
                                    <th width="12%">Petugas</th>
                                    <th width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $index => $movement)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $movement->fertilizer_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($movement->type == 'in')
                                                <span class="badge bg-success">Masuk</span>
                                            @else
                                                <span class="badge bg-danger">Keluar</span>
                                            @endif
                                        </td>
                                        <td
                                            class="{{ $movement->type == 'in' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                            {{ $movement->type == 'in' ? '+' : '-' }}{{ number_format(abs($movement->stock_change)) }}
                                            kg
                                        </td>
                                        <td>{{ number_format($movement->current_stock) }} kg</td>
                                        <td>{{ number_format($movement->final_stock) }} kg</td>
                                        <td>{{ $movement->user_name ?? 'System' }}</td>
                                        <td>{{ $movement->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="ti ti-inbox fs-50 text-muted"></i>
                                                <h5 class="mt-3">Tidak Ada Data Pergerakan</h5>
                                                <p class="text-muted">Belum ada pergerakan stok pupuk untuk periode yang
                                                    dipilih.</p>
                                            </div>
                                        </td>
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#fertilizerMovementTable').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "order": [[1, 'desc']],
                "columnDefs": [
                    { "orderable": false, "targets": [0] }
                ],
                "pageLength": 25
            });
        });

        function exportPDF() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            let url = '{{ route("kepala-desa.reports.fertilizer-movement.export-pdf") }}';
            const params = new URLSearchParams();

            if (startDate) {
                params.append('start_date', startDate);
            }
            if (endDate) {
                params.append('end_date', endDate);
            }

            const queryString = params.toString();
            if (queryString) {
                url += '?' + queryString;
            }

            window.location.href = url;
        }
    </script>

    <style>
        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar i {
            font-size: 30px;
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 50px;
            color: #ccc;
        }

        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
        }
    </style>
@endpush