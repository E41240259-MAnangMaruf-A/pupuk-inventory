<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class KepalaDesaDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            // Filter waktu
            $filter = $request->filter ?? 'monthly';
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Set tanggal berdasarkan filter
            if ($filter == 'daily') {
                $startDate = $endDate = now()->format('Y-m-d');
            } elseif ($filter == 'monthly') {
                $startDate = now()->startOfMonth()->format('Y-m-d');
                $endDate = now()->endOfMonth()->format('Y-m-d');
            } elseif ($filter == 'yearly') {
                $startDate = now()->startOfYear()->format('Y-m-d');
                $endDate = now()->endOfYear()->format('Y-m-d');
            } elseif ($filter == 'custom' && $startDate && $endDate) {
                $startDate = Carbon::parse($startDate)->format('Y-m-d');
                $endDate = Carbon::parse($endDate)->format('Y-m-d');
            } else {
                $startDate = now()->startOfMonth()->format('Y-m-d');
                $endDate = now()->endOfMonth()->format('Y-m-d');
            }

            // Stats utama
            $stats = $this->getDashboardStats($startDate, $endDate);

            // Data untuk grafik
            $chartData = $this->getChartData($startDate, $endDate, $filter);

            // Data untuk grafik distribusi pupuk
            $fertilizerDistribution = $this->getFertilizerDistribution($startDate, $endDate);

            // Transaksi terbaru
            $recentTransactions = $this->getRecentTransactions();

            // Stok pupuk
            $fertilizerStocks = $this->getFertilizerStocks();

            return view('dashboard.kepala-desa', compact(
                'stats',
                'chartData',
                'fertilizerDistribution',
                'recentTransactions',
                'fertilizerStocks',
                'filter',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());

            return view('dashboard.kepala-desa', [
                'stats' => $this->getEmptyStats(),
                'chartData' => $this->getEmptyChartData(),
                'fertilizerDistribution' => [],
                'recentTransactions' => [],
                'fertilizerStocks' => [],
                'filter' => $request->filter ?? 'monthly',
                'startDate' => $startDate ?? now()->format('Y-m-d'),
                'endDate' => $endDate ?? now()->format('Y-m-d')
            ]);
        }
    }

    private function getDashboardStats($startDate, $endDate)
    {
        $stats = [
            'total_farmers' => 0,
            'total_transactions' => 0,
            'total_subsidy_value' => 0,
            'remaining_subsidy_quota' => 0,
            'total_allocation' => 0,
            'total_distribution' => 0,
            'remaining_stock' => 0,
            'cash_flow' => 0
        ];

        try {
            // Total Petani Terdaftar
            if (Schema::hasTable('farmers')) {
                $stats['total_farmers'] = DB::table('farmers')
                    ->where('status', 'active')
                    ->count();
            }

            // Total Transaksi dalam periode
            if (Schema::hasTable('transactions')) {
                $stats['total_transactions'] = DB::table('transactions')
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->count();

                // Arus Kas (Total pemasukan dari transaksi)
                $cashFlow = DB::table('transactions')
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');
                $stats['cash_flow'] = $cashFlow ?? 0;
            }

            // Data Subsidi
            if (Schema::hasTable('subsidy_allocations')) {
                $totalAllocation = DB::table('subsidy_allocations')->sum('maximum_quota');
                $stats['total_allocation'] = $totalAllocation ?? 0;

                $totalDistribution = DB::table('subsidy_allocations')->sum('used_quota');
                $stats['total_distribution'] = $totalDistribution ?? 0;

                $remainingSubsidy = DB::table('subsidy_allocations')->sum('remaining_quota');
                $stats['remaining_subsidy_quota'] = $remainingSubsidy ?? 0;

                // Total nilai subsidi yang sudah digunakan
                $subsidyValue = DB::table('subsidy_allocations as sa')
                    ->join('fertilizer_types as ft', 'sa.fertilizer_type_id', '=', 'ft.id')
                    ->select(DB::raw('SUM(sa.used_quota * ft.subsidized_price) as total_value'))
                    ->first();
                $stats['total_subsidy_value'] = $subsidyValue->total_value ?? 0;
            }

            // Stok pupuk
            if (Schema::hasTable('fertilizer_stocks')) {
                $remainingStock = DB::table('fertilizer_stocks')->sum('current_stock');
                $stats['remaining_stock'] = $remainingStock ?? 0;
            }

        } catch (\Exception $e) {
            \Log::error('Dashboard Stats Error: ' . $e->getMessage());
        }

        return $stats;
    }

    private function getChartData($startDate, $endDate, $filter)
    {
        $chartData = [
            'months' => [],
            'transactions' => [],
            'subsidy_usage' => [],
            'revenue' => []
        ];

        try {
            if (!Schema::hasTable('transactions')) {
                return $chartData;
            }

            // Tentukan format group by berdasarkan filter
            if ($filter == 'daily') {
                // Untuk harian, tampilkan per jam
                $groupFormat = 'DATE_FORMAT(transaction_date, "%H:00")';
                $orderFormat = 'HOUR(transaction_date)';
            } elseif ($filter == 'yearly') {
                // Untuk tahunan, tampilkan per bulan
                $groupFormat = 'DATE_FORMAT(transaction_date, "%Y-%m")';
                $orderFormat = 'MONTH(transaction_date)';
            } else {
                // Default: per hari dalam bulan
                $groupFormat = 'DATE(transaction_date)';
                $orderFormat = 'DATE(transaction_date)';
            }

            // Data transaksi
            $monthlyTransactions = DB::table('transactions')
                ->select(
                    DB::raw($groupFormat . ' as period'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get();

            // Data subsidi usage
            $subsidyUsage = [];
            if (Schema::hasTable('subsidy_allocation_histories')) {
                $monthlySubsidy = DB::table('subsidy_allocation_histories')
                    ->select(
                        DB::raw('DATE(created_at) as period'),
                        DB::raw('SUM(quantity) as total_usage')
                    )
                    ->where('type', 'use')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->groupBy('period')
                    ->orderBy('period', 'asc')
                    ->get()
                    ->keyBy('period');

                $subsidyUsage = $monthlySubsidy;
            }

            // Format data untuk grafik
            foreach ($monthlyTransactions as $data) {
                $period = $data->period;

                // Format label berdasarkan filter
                if ($filter == 'daily') {
                    $label = $period;
                } elseif ($filter == 'yearly') {
                    $date = Carbon::createFromFormat('Y-m', $period);
                    $label = $date->format('M Y');
                } else {
                    $date = Carbon::parse($period);
                    $label = $date->format('d M');
                }

                $chartData['months'][] = $label;
                $chartData['transactions'][] = (int) $data->count;
                $chartData['revenue'][] = (int) $data->revenue;

                // Cari subsidy usage untuk periode yang sama
                $usage = isset($subsidyUsage[$period]) ? (int) $subsidyUsage[$period]->total_usage : 0;
                $chartData['subsidy_usage'][] = $usage;
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // dd($chartData);

        return $chartData;
    }

    private function getFertilizerDistribution($startDate, $endDate)
    {
        $distribution = [];
        try {
            if (!Schema::hasTable('fertilizer_types')) {
                return $distribution;
            }

            $fertilizers = DB::table('fertilizer_types')
                ->select('id', 'fertilizer_name as name', 'is_subsidized')
                ->get();

            foreach ($fertilizers as $fertilizer) {
                $distributed = 0;
                $remaining = 0;

                // Hitung yang sudah didistribusikan dari transaksi
                if (Schema::hasTable('transaction_details') && Schema::hasTable('transactions')) {
                    $distributedQuery = DB::table('transaction_details as td')
                        ->join('transactions as t', 'td.transaction_id', '=', 't.id')
                        ->where('td.fertilizer_type_id', $fertilizer->id)
                        ->whereBetween('t.transaction_date', [$startDate, $endDate])
                        ->sum('td.quantity');

                    $distributed = $distributedQuery ?? 0;
                }

                // Hitung sisa stok
                if (Schema::hasTable('fertilizer_stocks')) {
                    $remainingQuery = DB::table('fertilizer_stocks')
                        ->where('fertilizer_type_id', $fertilizer->id)
                        ->value('current_stock');

                    $remaining = $remainingQuery ?? 0;
                }

                $distribution[] = [
                    'name' => $fertilizer->name,
                    'is_subsidized' => $fertilizer->is_subsidized,
                    'distributed' => (int) $distributed,
                    'remaining' => (int) $remaining
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Fertilizer Distribution Error: ' . $e->getMessage());
        }

        return $distribution;
    }

    private function getRecentTransactions()
    {
        $transactions = [];

        try {
            if (!Schema::hasTable('transactions') || !Schema::hasTable('farmers')) {
                return $transactions;
            }

            $recentData = DB::table('transactions as t')
                ->join('farmers as f', 't.farmer_id', '=', 'f.id')
                ->select(
                    't.id',
                    'f.farmer_name',
                    't.total_amount',
                    't.payment_status',
                    't.transaction_date',
                    't.transaction_number'
                )
                ->orderBy('t.transaction_date', 'desc')
                ->orderBy('t.created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($recentData as $item) {
                $fertilizerNames = [];
                $totalQuantity = 0;

                if (Schema::hasTable('transaction_details') && Schema::hasTable('fertilizer_types')) {
                    $fertilizerData = DB::table('transaction_details as td')
                        ->join('fertilizer_types as ft', 'td.fertilizer_type_id', '=', 'ft.id')
                        ->where('td.transaction_id', $item->id)
                        ->select('ft.fertilizer_name', 'td.quantity')
                        ->get();

                    foreach ($fertilizerData as $fert) {
                        $fertilizerNames[] = $fert->fertilizer_name;
                        $totalQuantity += $fert->quantity;
                    }
                }

                $fertilizerNames = !empty($fertilizerNames) ? implode(', ', $fertilizerNames) : 'Pupuk';

                $transactions[] = [
                    'transaction_number' => $item->transaction_number,
                    'farmer_name' => $item->farmer_name ?? 'Unknown',
                    'fertilizer_names' => $fertilizerNames,
                    'quantity' => $totalQuantity,
                    'total_amount' => $item->total_amount,
                    'payment_status' => $item->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar',
                    'transaction_date' => $item->transaction_date
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Recent Transactions Error: ' . $e->getMessage());
        }

        return $transactions;
    }

    private function getFertilizerStocks()
    {
        $stocks = [];

        try {
            if (!Schema::hasTable('fertilizer_stocks') || !Schema::hasTable('fertilizer_types')) {
                return $stocks;
            }

            $stockData = DB::table('fertilizer_stocks as fs')
                ->join('fertilizer_types as ft', 'fs.fertilizer_type_id', '=', 'ft.id')
                ->select(
                    'ft.id as fertilizer_type_id',
                    'ft.fertilizer_name',
                    'ft.is_subsidized',
                    'fs.current_stock'
                )
                ->get();

            foreach ($stockData as $item) {
                $stockIn = 0;
                if (Schema::hasTable('fertilizer_stock_histories')) {
                    $stockIn = DB::table('fertilizer_stock_histories')
                        ->where('fertilizer_type_id', $item->fertilizer_type_id)
                        ->where('type', 'in')
                        ->sum('stock_change') ?? 0;
                }

                $stockOut = 0;
                if (Schema::hasTable('fertilizer_stock_histories')) {
                    $stockOutResult = DB::table('fertilizer_stock_histories')
                        ->where('fertilizer_type_id', $item->fertilizer_type_id)
                        ->where('type', 'out')
                        ->sum('stock_change') ?? 0;
                    $stockOut = abs($stockOutResult);
                }

                $initialStock = ($item->current_stock + $stockOut) - $stockIn;

                $stocks[] = [
                    'fertilizer_name' => $item->fertilizer_name ?? 'Unknown',
                    'is_subsidized' => $item->is_subsidized ? 'Subsidi' : 'Non-Subsidi',
                    'initial_stock' => $initialStock,
                    'stock_in' => $stockIn,
                    'stock_out' => $stockOut,
                    'current_stock' => $item->current_stock ?? 0,
                    'status' => $this->getStockStatus($item->current_stock)
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Fertilizer Stocks Error: ' . $e->getMessage());
        }

        return $stocks;
    }

    private function getStockStatus($currentStock)
    {
        if ($currentStock <= 10) {
            return 'Stok Rendah';
        } elseif ($currentStock <= 50) {
            return 'Stok Sedang';
        } else {
            return 'Stok Aman';
        }
    }

    private function getEmptyStats()
    {
        return [
            'total_farmers' => 0,
            'total_transactions' => 0,
            'total_subsidy_value' => 0,
            'remaining_subsidy_quota' => 0,
            'total_allocation' => 0,
            'total_distribution' => 0,
            'remaining_stock' => 0,
            'cash_flow' => 0
        ];
    }

    private function getEmptyChartData()
    {
        return [
            'months' => [],
            'transactions' => [],
            'subsidy_usage' => [],
            'revenue' => []
        ];
    }
}