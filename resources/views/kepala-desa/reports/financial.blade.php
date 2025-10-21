@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Laporan Keuangan Koperasi</h4>
                <h6>Ringkasan Pemasukan dan Pengeluaran</h6>
            </div>
            <div class="page-btn">
                <button type="button" class="btn btn-primary" onclick="exportPDF()">
                    <i class="ti ti-download me-1"></i>Export PDF
                </button>
            </div>
        </div>

        <!-- Filter Form - DIPERBAIKI -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Periode</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kepala-desa.reports.financial') }}" method="GET" id="filterForm">
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
                                <a href="{{ route('kepala-desa.reports.financial') }}" class="btn btn-secondary">
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
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold">Rp {{ number_format($summary['total_income'] ?? 0, 0, ',', '.') }}</h3>
                                <p class="mb-0">Total Pemasukan</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-arrow-down-left fs-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold">Rp {{ number_format($summary['total_expense'] ?? 0, 0, ',', '.') }}</h3>
                                <p class="mb-0">Total Pengeluaran</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-arrow-up-right fs-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold">Rp {{ number_format($summary['net_income'] ?? 0, 0, ',', '.') }}</h3>
                                <p class="mb-0">Laba Bersih</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-chart-line fs-30"></i>
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
                                <h3 class="fw-bold">{{ number_format($summary['total_transactions'] ?? 0) }}</h3>
                                <p class="mb-0">Total Transaksi</p>
                            </div>
                            <div class="avatar">
                                <i class="ti ti-shopping-cart fs-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTables -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Detail Transaksi Keuangan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="financialReportTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Tanggal</th>
                                <th width="15%">No. Transaksi</th>
                                <th width="20%">Petani</th>
                                <th width="15%">Total</th>
                                <th width="12%">Pembayaran</th>
                                <th width="10%">Status</th>
                                <th width="11%">Kasir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($transaction->transaction_date instanceof \Carbon\Carbon)
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                    @else
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>{{ $transaction->transaction_number }}</td>
                                <td>{{ $transaction->farmer_name ?? '-' }}</td>
                                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->total_payment ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaction->payment_status == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                    @else
                                    <span class="badge bg-warning">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->cashier_name ?? 'System' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="ti ti-inbox fs-50 text-muted"></i>
                                        <h5 class="mt-3">Tidak Ada Data Transaksi</h5>
                                        <p class="text-muted">Belum ada transaksi untuk periode yang dipilih.</p>
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
    $(document).ready(function() {
        $('#financialReportTable').DataTable({
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

    console.log('Export Financial PDF - Start:', startDate, 'End:', endDate);

    let url = '{{ route("kepala-desa.reports.financial.export-pdf") }}';
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

    console.log('Opening URL:', url);
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
</style>
@endpush