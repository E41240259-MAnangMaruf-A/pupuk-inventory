<?php $page = 'kepala-desa-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('dashboard.kepala-desa') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter Waktu</label>
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            <option value="custom" {{ $startDate && $endDate ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>
                    @if($startDate && $endDate)
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Ringkasan Cepat -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-count" style="background: #ff9933;">
                    <div class="dash-counts">
                        <h4 class="mb-1">{{ number_format($stats['total_farmers']) }}</h4>
                        <p class="text-white mb-0">Total Petani Terdaftar</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="users"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das1 bg-info">
                    <div class="dash-counts">
                        <h4 class="mb-1">{{ number_format($stats['total_transactions']) }}</h4>
                        <p class="text-white mb-0">Total Transaksi Penyaluran</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das2 bg-success">
                    <div class="dash-counts">
                        <h4 class="mb-1">Rp {{ number_format($stats['total_subsidy_value'], 0, ',', '.') }}</h4>
                        <p class="text-white mb-0">Total Nilai Subsidi Tersalurkan</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3 bg-warning">
                    <div class="dash-counts">
                        <h4 class="mb-1">{{ number_format($stats['remaining_subsidy_quota']) }} kg</h4>
                        <p class="text-white mb-0">Sisa Kuota Subsidi</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="package"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Tambahan -->
        <div class="row mt-4">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span style="background: #ff9933;"><i data-feather="package" class="text-white"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1">{{ number_format($stats['total_allocation']) }} kg</h5>
                            <p class="mb-0">Total Alokasi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash1 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span style="background: #28a745;"><i data-feather="truck" class="text-white"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1">{{ number_format($stats['total_distribution']) }} kg</h5>
                            <p class="mb-0">Total Penyaluran</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash2 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span style="background: #17a2b8;"><i data-feather="box" class="text-white"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1">{{ number_format($stats['remaining_stock']) }} kg</h5>
                            <p class="mb-0">Sisa Stok</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash3 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span style="background: #6f42c1;"><i data-feather="dollar-sign" class="text-white"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1">Rp {{ number_format($stats['cash_flow'], 0, ',', '.') }}</h5>
                            <p class="mb-0">Arus Kas Koperasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Grafik Distribusi Pupuk -->
            <div class="col-xl-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Distribusi Pupuk Subsidi & Non-Subsidi</h5>
                        <div class="graph-sets">
                            <ul class="mb-0">
                                <li>
                                    <span class="bg-success"></span>
                                    <span>Tersalurkan</span>
                                </li>
                                <li>
                                    <span class="bg-warning"></span>
                                    <span>Tersisa</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="fertilizerDistributionChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi Terbaru -->
            <div class="col-xl-5 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Riwayat Transaksi Terbaru</h4>
                        <a href="#" class="fs-13 fw-medium text-decoration-underline">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive dataview">
                            <table class="table dashboard-recent-products">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Petani</th>
                                        <th>Jenis Pupuk</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ Str::limit($transaction['farmer_name'], 15) }}</td>
                                        <td>{{ Str::limit($transaction['fertilizer_names'], 20) }}</td>
                                        <td>{{ $transaction['quantity'] }} kg</td>
                                        <td>
                                            @if($transaction['payment_status'] == 'Lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($transaction['payment_status'] == 'Belum Bayar')
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            @else
                                                <span class="badge bg-warning">{{ $transaction['payment_status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada transaksi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Pupuk -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Stok Pupuk</h4>
                <div class="view-all-link">
                    <a href="#" class="fs-13 fw-medium text-decoration-underline">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table dashboard-expired-products">
                        <thead class="thead-light">
                            <tr>
                                <th>Jenis Pupuk</th>
                                <th>Tipe</th>
                                <th>Stok Awal (kg)</th>
                                <th>Masuk (kg)</th>
                                <th>Keluar (kg)</th>
                                <th>Sisa (kg)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fertilizerStocks as $stock)
                            <tr>
                                <td class="fw-bold">{{ $stock['fertilizer_name'] }}</td>
                                <td>
                                    @if($stock['is_subsidized'])
                                        <span class="badge bg-success">Subsidi</span>
                                    @else
                                        <span class="badge bg-info">Non-Subsidi</span>
                                    @endif
                                </td>
                                <td>{{ number_format($stock['initial_stock']) }}</td>
                                <td class="text-success">+{{ number_format($stock['stock_in']) }}</td>
                                <td class="text-danger">-{{ number_format($stock['stock_out']) }}</td>
                                <td class="fw-bold">{{ number_format($stock['current_stock']) }}</td>
                                <td>
                                    @if($stock['current_stock'] > 100)
                                        <span class="badge bg-success">Aman</span>
                                    @elseif($stock['current_stock'] > 20)
                                        <span class="badge bg-warning">Menipis</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data stok</td>
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
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function() {
        // Data untuk grafik distribusi pupuk
        var distributionData = @json($fertilizerDistribution);

        var categories = distributionData.map(item => item.name);
        var distributedData = distributionData.map(item => item.distributed);
        var remainingData = distributionData.map(item => item.remaining);

        var options = {
            series: [{
                name: 'Tersalurkan',
                data: distributedData,
                color: '#28a745'
            }, {
                name: 'Tersisa',
                data: remainingData,
                color: '#ffc107'
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: categories,
            },
            yaxis: {
                title: {
                    text: 'Jumlah (kg)'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " kg"
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            }
        };

        var chart = new ApexCharts(document.querySelector("#fertilizerDistributionChart"), options);
        chart.render();

        // Inisialisasi DataTables
        $('.dashboard-expired-products').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data stok",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya"
                }
            },
            "pageLength": 5,
            "responsive": true
        });

        $('.dashboard-recent-products').DataTable({
            "searching": false,
            "lengthChange": false,
            "paging": false,
            "info": false,
            "ordering": false,
            "responsive": true
        });
    });
</script>

<style>
.dash-count {
    border-radius: 10px;
    padding: 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.dash-counts h4 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
}

.dash-counts p {
    font-size: 14px;
    margin-bottom: 0;
    opacity: 0.9;
}

.dash-imgs i {
    font-size: 40px;
    opacity: 0.8;
}

.dash-widgetimg span {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.dash-widgetimg i {
    font-size: 24px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.bg-cyan-900 {
    background-color: #085f63 !important;
}

.graph-sets ul {
    display: flex;
    gap: 15px;
    margin-bottom: 0;
}

.graph-sets ul li {
    display: flex;
    align-items: center;
    gap: 5px;
}

.graph-sets ul li span:first-child {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}
</style>
@endsection