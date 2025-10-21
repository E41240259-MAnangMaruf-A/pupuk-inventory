<?php 
$page = 'reports'; 
?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Laporan Alokasi Subsidi Pupuk</h4>
                <h6>Monitoring Penggunaan Subsidi oleh Petani</h6>
            </div>
            <div class="page-btn">
                <button type="button" class="btn btn-primary" id="exportPdfBtn">
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
                <form method="GET" action="{{ route('kepala-desa.reports.subsidy-allocation') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                   value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info flex-fill">
                                    <i class="ti ti-filter me-1"></i>Terapkan Filter
                                </button>
                                <a href="{{ route('kepala-desa.reports.subsidy-allocation') }}" class="btn btn-secondary">
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
                                <h3 class="fw-bold">{{ number_format($summary['total_allocated']) }}</h3>
                                <p class="mb-0">Total Dialokasi (kg)</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-package fs-30"></i>
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
                                <h3 class="fw-bold">{{ number_format($summary['total_used']) }}</h3>
                                <p class="mb-0">Total Terpakai (kg)</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-check fs-30"></i>
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
                                <h3 class="fw-bold">{{ number_format($summary['total_remaining']) }}</h3>
                                <p class="mb-0">Sisa Kuota (kg)</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-archive fs-30"></i>
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
                                <h3 class="fw-bold">Rp {{ number_format($summary['total_subsidy_value'], 0, ',', '.') }}</h3>
                                <p class="mb-0">Nilai Subsidi</p>
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
                <h5 class="fw-bold mb-0">Detail Alokasi Subsidi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="subsidyAllocationTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama Petani</th>
                                <th width="15%">NIK</th>
                                <th width="15%">Jenis Pupuk</th>
                                <th width="10%">Kuota</th>
                                <th width="10%">Terpakai</th>
                                <th width="10%">Sisa</th>
                                <th width="10%">Persentase</th>
                                <th width="10%">Nilai Subsidi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $index => $allocation)
                            @php
                                $percentage = $allocation->maximum_quota > 0 
                                    ? round(($allocation->used_quota / $allocation->maximum_quota) * 100, 1)
                                    : 0;
                                $subsidyValue = $allocation->used_quota * ($allocation->subsidized_price ?? 0);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $allocation->farmer_name ?? 'N/A' }}</td>
                                <td>{{ $allocation->nik ?? 'N/A' }}</td>
                                <td>{{ $allocation->fertilizer_name ?? 'N/A' }}</td>
                                <td>{{ number_format($allocation->maximum_quota) }} kg</td>
                                <td>{{ number_format($allocation->used_quota) }} kg</td>
                                <td>{{ number_format($allocation->remaining_quota) }} kg</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar 
                                            @if($percentage >= 80) bg-success
                                            @elseif($percentage >= 50) bg-info
                                            @elseif($percentage >= 20) bg-warning
                                            @else bg-danger @endif" 
                                            role="progressbar" 
                                            style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                </td>
                                <td>Rp {{ number_format($subsidyValue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="ti ti-package fs-50 text-muted"></i>
                                        <h5 class="mt-2">Tidak ada data alokasi subsidi</h5>
                                        <p class="text-muted">Belum ada alokasi subsidi untuk periode yang dipilih</p>
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
    // Pastikan jQuery tersedia
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
    }

    $(document).ready(function() {
        console.log('Document ready - Initializing DataTable');
        
        // Inisialisasi DataTable
        $('#subsidyAllocationTable').DataTable({
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
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [0, 7] }
            ],
            "pageLength": 25
        });

        // Event listener untuk tombol export
        $('#exportPdfBtn').on('click', function() {
            console.log('Export button clicked');
            exportSubsidyPDF();
        });

        // Juga assign ke window object untuk backup
        window.exportPDF = exportSubsidyPDF;
    });

    // Fungsi export PDF
    function exportSubsidyPDF() {
        try {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            console.log('Exporting with dates - Start:', startDate, 'End:', endDate);

            let url = '{{ route("kepala-desa.reports.subsidy-allocation.export-pdf") }}';
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
            
            console.log('Redirecting to URL:', url);
            window.location.href = url;
            
        } catch (error) {
            console.error('Export error:', error);
            alert('Terjadi kesalahan saat export: ' + error.message);
        }
    }

    // Fallback: juga assign fungsi ke window object dengan nama yang berbeda
    window.exportSubsidyPDF = exportSubsidyPDF;
</script>

<style>
.progress {
    background-color: #e9ecef;
    border-radius: 4px;
    margin-bottom: 5px;
}
.progress-bar {
    border-radius: 4px;
}
.empty-state {
    padding: 40px 20px;
    text-align: center;
}
.avatar {
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar i {
    font-size: 30px;
}
</style>
@endpush