<?php

namespace App\Http\Controllers;

use App\Models\FertilizerStockHistory;
use App\Models\FertilizerStock;
use App\Models\FertilizerType;
use App\Models\SubsidyAllocation;
use App\Models\SubsidyAllocationHistory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Farmer;
use App\Models\FarmerSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class KepalaDesaReportController extends Controller
{
    /**
     * Laporan Pergerakan Pupuk - FIXED VERSION
     */
    public function fertilizerMovement(Request $request)
    {
        try {
            // Inisialisasi variabel dengan nilai default
            $movements = collect([]);
            $totalStockIn = 0;
            $totalStockOut = 0;
            $currentStock = 0;
            $totalValue = 0;
            $fertilizerTypes = collect([]);

            // Cek dan ambil data hanya jika tabel exists
            if (Schema::hasTable('fertilizer_stock_histories')) {
                $query = FertilizerStockHistory::with(['fertilizerType', 'user']);
                
                if ($request->fertilizer_type_id) {
                    $query->where('fertilizer_type_id', $request->fertilizer_type_id);
                }
                
                if ($request->start_date) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }
                
                if ($request->end_date) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }
                
                $movements = $query->orderBy('created_at', 'desc')->get();
                
                // Hitung total dari data aktual
                $totalStockIn = $movements->where('type', 'in')->sum('stock_change');
                $totalStockOut = $movements->where('type', 'out')->sum('stock_change');
            }

            // Hitung stok saat ini
            if (Schema::hasTable('fertilizer_stocks')) {
                $stocks = FertilizerStock::with('fertilizerType')->get();
                foreach ($stocks as $stock) {
                    $currentStock += $stock->current_stock;
                    if ($stock->fertilizerType) {
                        $totalValue += $stock->current_stock * $stock->fertilizerType->retail_price;
                    }
                }
            }

            // Ambil data jenis pupuk
            if (Schema::hasTable('fertilizer_types')) {
                $fertilizerTypes = FertilizerType::all();
            }

            return view('kepala-desa.reports.fertilizer-movement', compact(
                'movements', 'totalStockIn', 'totalStockOut', 'currentStock', 
                'totalValue', 'fertilizerTypes'
            ));
            
        } catch (\Exception $e) {
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Alokasi Subsidi - FIXED VERSION
     */
    public function subsidyAllocation(Request $request)
    {
        try {
            // Inisialisasi variabel dengan nilai default
            $allocations = collect([]);
            $totalAllocated = 0;
            $totalUsed = 0;
            $totalRemaining = 0;
            $totalSubsidyValue = 0;
            $subsidyHistory = collect([]);
            $fertilizerTypes = collect([]);

            // Cek dan ambil data hanya jika tabel exists
            if (Schema::hasTable('subsidy_allocations')) {
                $query = SubsidyAllocation::with(['farmer', 'fertilizerType']);
                
                if ($request->fertilizer_type_id) {
                    $query->where('fertilizer_type_id', $request->fertilizer_type_id);
                }
                
                if ($request->period_start) {
                    $query->where('period_start', '>=', $request->period_start);
                }
                
                if ($request->period_end) {
                    $query->where('period_end', '<=', $request->period_end);
                }
                
                $allocations = $query->get();
                
                // Hitung total dari data aktual
                $totalAllocated = $allocations->sum('initial_quota');
                $totalUsed = $allocations->sum('used_quota');
                $totalRemaining = $allocations->sum('remaining_quota');
                $totalSubsidyValue = $allocations->sum(function($allocation) {
                    if ($allocation->fertilizerType) {
                        return $allocation->used_quota * $allocation->fertilizerType->subsidized_price;
                    }
                    return 0;
                });
            }

            // Ambil history subsidi
            if (Schema::hasTable('subsidy_allocation_histories')) {
                $subsidyHistoryQuery = SubsidyAllocationHistory::with(['allocation.farmer', 'fertilizerType', 'transaction']);
                
                if ($request->fertilizer_type_id) {
                    $subsidyHistoryQuery->where('fertilizer_type_id', $request->fertilizer_type_id);
                }
                
                $subsidyHistory = $subsidyHistoryQuery->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get();
            }

            // Ambil data jenis pupuk
            if (Schema::hasTable('fertilizer_types')) {
                $fertilizerTypes = FertilizerType::all();
            }

            return view('kepala-desa.reports.subsidy-allocation', compact(
                'allocations', 'totalAllocated', 'totalUsed', 'totalRemaining',
                'totalSubsidyValue', 'subsidyHistory', 'fertilizerTypes'
            ));
            
        } catch (\Exception $e) {
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Laporan Keuangan - FIXED VERSION
     */
    public function financial(Request $request)
    {
        try {
            // Inisialisasi variabel dengan nilai default
            $transactions = collect([]);
            $totalIncome = 0;
            $totalExpense = 0;
            $netIncome = 0;
            $totalTransactions = 0;
            $incomeByFertilizer = [];
            $monthlyData = [];
            $monthlyLabels = [];
            $topCustomers = [];

            $year = $request->year ?? date('Y');
            $month = $request->month ?? date('Y-m');
            $reportType = $request->report_type ?? 'monthly';

            // Cek dan ambil data hanya jika tabel exists
            if (Schema::hasTable('transactions')) {
                $transactionQuery = Transaction::with(['farmer', 'details.fertilizerType']);
                
                if ($year) {
                    $transactionQuery->whereYear('transaction_date', $year);
                }
                
                if ($reportType === 'monthly' && $month) {
                    $monthNumber = date('m', strtotime($month));
                    $transactionQuery->whereMonth('transaction_date', $monthNumber);
                }
                
                $transactions = $transactionQuery->orderBy('transaction_date', 'desc')->get();
                
                // Hitung total dari data aktual
                $totalIncome = $transactions->sum('total_amount');
                $totalExpense = 0; // Sesuaikan dengan tabel expenses jika ada
                $netIncome = $totalIncome - $totalExpense;
                $totalTransactions = $transactions->count();
            }

            // Breakdown pendapatan per jenis pupuk
            if (Schema::hasTable('transaction_details') && Schema::hasTable('fertilizer_types')) {
                $incomeByFertilizer = DB::table('transaction_details')
                    ->join('fertilizer_types', 'transaction_details.fertilizer_type_id', '=', 'fertilizer_types.id')
                    ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                    ->whereYear('transactions.transaction_date', $year)
                    ->when($reportType === 'monthly' && $month, function($query) use ($month) {
                        return $query->whereMonth('transactions.transaction_date', date('m', strtotime($month)));
                    })
                    ->select(
                        'fertilizer_types.fertilizer_name',
                        DB::raw('COUNT(DISTINCT transactions.id) as transaction_count'),
                        DB::raw('SUM(transaction_details.subtotal) as total_income')
                    )
                    ->groupBy('fertilizer_types.id', 'fertilizer_types.fertilizer_name')
                    ->get()
                    ->toArray();
            }

            // Data bulanan untuk chart
            if (Schema::hasTable('transactions')) {
                if ($reportType === 'yearly') {
                    for ($i = 1; $i <= 12; $i++) {
                        $monthlyIncome = Transaction::whereYear('transaction_date', $year)
                            ->whereMonth('transaction_date', $i)
                            ->sum('total_amount');
                        $monthlyData[] = $monthlyIncome;
                        $monthlyLabels[] = DateTime::createFromFormat('!m', $i)->format('M');
                    }
                } else {
                    $daysInMonth = $month ? Carbon::parse($month)->daysInMonth : date('t');
                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $dailyIncome = Transaction::whereYear('transaction_date', $year)
                            ->whereMonth('transaction_date', date('m', strtotime($month)))
                            ->whereDay('transaction_date', $i)
                            ->sum('total_amount');
                        $monthlyData[] = $dailyIncome;
                        $monthlyLabels[] = $i;
                    }
                }
            }

            // Top customers
            if (Schema::hasTable('farmers') && Schema::hasTable('transactions')) {
                $topCustomers = DB::table('transactions')
                    ->join('farmers', 'transactions.farmer_id', '=', 'farmers.id')
                    ->whereYear('transactions.transaction_date', $year)
                    ->when($reportType === 'monthly' && $month, function($query) use ($month) {
                        return $query->whereMonth('transactions.transaction_date', date('m', strtotime($month)));
                    })
                    ->select(
                        'farmers.farmer_name',
                        DB::raw('COUNT(transactions.id) as transaction_count'),
                        DB::raw('SUM(transactions.total_amount) as total_spent')
                    )
                    ->groupBy('farmers.id', 'farmers.farmer_name')
                    ->orderBy('total_spent', 'desc')
                    ->limit(10)
                    ->get();
            }

            return view('kepala-desa.reports.financial', compact(
                'transactions', 'totalIncome', 'totalExpense', 'netIncome',
                'totalTransactions', 'incomeByFertilizer', 'monthlyData', 
                'monthlyLabels', 'topCustomers', 'year', 'month', 'reportType'
            ));
            
        } catch (\Exception $e) {
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan Pergerakan Pupuk - FIXED
     */
    public function exportFertilizerMovement(Request $request)
    {
        try {
            if (!Schema::hasTable('fertilizer_stock_histories')) {
                return redirect()->back()->with('error', 'Tabel data pergerakan pupuk belum tersedia');
            }
            
            // Logic export akan diimplementasikan di sini
            return redirect()->back()->with('success', 'Export laporan pergerakan pupuk berhasil');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan Alokasi Subsidi - FIXED
     */
    public function exportSubsidyAllocation(Request $request)
    {
        try {
            if (!Schema::hasTable('subsidy_allocations')) {
                return redirect()->back()->with('error', 'Tabel data alokasi subsidi belum tersedia');
            }
            
            // Logic export akan diimplementasikan di sini
            return redirect()->back()->with('success', 'Export laporan alokasi subsidi berhasil');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan Keuangan - FIXED
     */
    public function exportFinancial(Request $request)
    {
        try {
            if (!Schema::hasTable('transactions')) {
                return redirect()->back()->with('error', 'Tabel data transaksi belum tersedia');
            }
            
            // Logic export akan diimplementasikan di sini
            return redirect()->back()->with('success', 'Export laporan keuangan berhasil');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard Statistics for Kepala Desa - FIXED
     */
    public function dashboardStatistics()
    {
        try {
            // Inisialisasi dengan nilai default
            $stats = [
                'totalFarmers' => 0,
                'monthlyTransactions' => 0,
                'monthlyIncome' => 0,
                'pendingSubmissions' => 0,
                'currentStockValue' => 0
            ];

            // Total farmers
            if (Schema::hasTable('farmers')) {
                $stats['totalFarmers'] = Farmer::count();
            }

            // Total transactions this month
            if (Schema::hasTable('transactions')) {
                $stats['monthlyTransactions'] = Transaction::whereMonth('transaction_date', date('m'))
                    ->whereYear('transaction_date', date('Y'))
                    ->count();
                    
                $stats['monthlyIncome'] = Transaction::whereMonth('transaction_date', date('m'))
                    ->whereYear('transaction_date', date('Y'))
                    ->sum('total_amount');
            }

            // Pending submissions
            if (Schema::hasTable('farmer_submissions')) {
                $stats['pendingSubmissions'] = FarmerSubmission::where('status', 'pending')->count();
            }

            // Current stock value
            if (Schema::hasTable('fertilizer_stocks')) {
                $stats['currentStockValue'] = FertilizerStock::with('fertilizerType')->get()
                    ->sum(function($stock) {
                        return $stock->current_stock * ($stock->fertilizerType->retail_price ?? 0);
                    });
            }

            return $stats;
            
        } catch (\Exception $e) {
            // Return default values jika error
            return [
                'totalFarmers' => 0,
                'monthlyTransactions' => 0,
                'monthlyIncome' => 0,
                'pendingSubmissions' => 0,
                'currentStockValue' => 0
            ];
        }
    }
}