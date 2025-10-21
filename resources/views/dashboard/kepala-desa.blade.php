<?php 
// resources/views/dashboard/kepala-desa.blade.php
$page = 'kepala-desa-dashboard'; 
?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('kepala-desa.dashboard') }}" method="GET" class="row g-3">
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
                        <p class="text-white mb-0">Total Transaksi</p>
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
                        <p class="text-white mb-0">Nilai Subsidi Tersalur</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-count das3 bg-warning">
                    <div class="dash-counts">
                        <h4 class="mb-1">Rp {{ number_format($stats['cash_flow'], 0, ',', '.') }}</h4>
                        <p class="text-white mb-0">Arus Kas</p>
                    </div>
                    <div class="dash-imgs">
                        <i data-feather="trending-up"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Utama -->
        <div class="row mt-4">
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Grafik Aktivitas Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <div id="mainChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Distribusi Pupuk</h5>
                    </div>
                    <div class="card-body">
                        <div id="distributionChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tambahan -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Transaksi</th>
                                        <th>Petani</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction['transaction_number'] }}</td>
                                        <td>{{ Str::limit($transaction['farmer_name'], 15) }}</td>
                                        <td>Rp {{ number_format($transaction['total_amount'], 0, ',', '.') }}</td>
                                        <td>
                                            @if($transaction['payment_status'] == 'Lunas')
                                            <span class="badge bg-success">Lunas</span>
                                            @else
                                            <span class="badge bg-warning">Belum Bayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Tidak ada transaksi</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Stok Pupuk</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Jenis Pupuk</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fertilizerStocks as $stock)
                                    <tr>
                                        <td>{{ $stock['fertilizer_name'] }}</td>
                                        <td>{{ number_format($stock['current_stock']) }} kg</td>
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
                                        <td colspan="3" class="text-center py-3">Tidak ada data stok</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari controller
    const chartData = @json($chartData);
    const distributionData = @json($fertilizerDistribution);

    // Grafik Utama - Line Chart
    const mainChartOptions = {
        series: [{
            name: 'Jumlah Transaksi',
            data: chartData.transactions,
            type: 'column'
        }, {
            name: 'Pendapatan (Rp)',
            data: chartData.revenue,
            type: 'line'
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: {
                show: true
            }
        },
        stroke: {
            width: [0, 4]
        },
        title: {
            text: 'Aktivitas Bulanan',
            align: 'left'
        },
        dataLabels: {
            enabled: true,
            enabledOnSeries: [1]
        },
        labels: chartData.months,
        xaxis: {
            type: 'category'
        },
        yaxis: [{
            title: {
                text: 'Jumlah Transaksi',
            }
        }, {
            opposite: true,
            title: {
                text: 'Pendapatan (Rp)'
            },
            labels: {
                formatter: function(val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        }],
        tooltip: {
            y: {
                formatter: function(val, { seriesIndex }) {
                    if (seriesIndex === 1) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                    return val;
                }
            }
        }
    };

    const mainChart = new ApexCharts(document.querySelector("#mainChart"), mainChartOptions);
    mainChart.render();

    // Grafik Distribusi - Pie Chart
    const distributionChartOptions = {
        series: distributionData.map(item => item.distributed),
        chart: {
            type: 'donut',
            height: 350
        },
        labels: distributionData.map(item => item.name),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        legend: {
            position: 'bottom'
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " kg"
                }
            }
        }
    };

    const distributionChart = new ApexCharts(document.querySelector("#distributionChart"), distributionChartOptions);
    distributionChart.render();
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
</style>
@endpush