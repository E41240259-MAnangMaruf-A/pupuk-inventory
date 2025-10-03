<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\FertilizerStock;
use App\Models\FertilizerType;
use App\Models\SubsidyAllocation;
use App\Models\FarmerSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KepalaDesaDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Filter waktu
        $filter = $request->get('filter', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set tanggal berdasarkan filter
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
        } else {
            switch ($filter) {
                case 'daily':
                    $start = Carbon::now()->startOfDay();
                    $end = Carbon::now()->endOfDay();
                    break;
                case 'yearly':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    break;
                case 'monthly':
                default:
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
            }
        }

        // Statistik utama
        $stats = [
            'total_farmers' => Farmer::count(),
            'total_transactions' => Transaction::whereBetween('transaction_date', [$start, $end])->count(),
            'total_subsidy_value' => $this->calculateTotalSubsidyValue($start, $end),
            'remaining_subsidy_quota' => $this->calculateRemainingSubsidyQuota(),
            'pending_submissions' => FarmerSubmission::where('status', 'pending')->count(),
            'total_allocation' => $this->calculateTotalAllocation(),
            'total_distribution' => $this->calculateTotalDistribution($start, $end),
            'remaining_stock' => $this->calculateRemainingStock(),
            'cash_flow' => $this->calculateCashFlow($start, $end),
        ];

        // Data untuk grafik distribusi pupuk
        $fertilizerDistribution = $this->getFertilizerDistributionData($start, $end);
        
        // Riwayat transaksi terbaru
        $recentTransactions = $this->getRecentTransactions();
        
        // Data stok pupuk
        $fertilizerStocks = $this->getFertilizerStocks();

        return view('dashboard.kepala-desa', compact(
            'stats',
            'fertilizerDistribution',
            'recentTransactions',
            'fertilizerStocks',
            'filter',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Hitung total nilai subsidi tersalurkan
     */
    private function calculateTotalSubsidyValue($start, $end)
    {
        $subsidyTransactions = TransactionDetail::whereHas('transaction', function($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })
        ->whereHas('fertilizerType', function($query) {
            $query->where('is_subsidized', true);
        })
        ->get();

        $totalSubsidy = 0;
        foreach ($subsidyTransactions as $transaction) {
            $subsidyPerKg = $transaction->fertilizerType->retail_price - $transaction->fertilizerType->subsidized_price;
            $totalSubsidy += $transaction->quantity * $subsidyPerKg;
        }

        return $totalSubsidy;
    }

    /**
     * Hitung sisa kuota subsidi
     */
    private function calculateRemainingSubsidyQuota()
    {
        return SubsidyAllocation::where('status', 'active')
            ->sum('remaining_quota');
    }

    /**
     * Hitung total alokasi
     */
    private function calculateTotalAllocation()
    {
        return SubsidyAllocation::sum('maximum_quota');
    }

    /**
     * Hitung total penyaluran
     */
    private function calculateTotalDistribution($start, $end)
    {
        return TransactionDetail::whereHas('transaction', function($query) use ($start, $end) {
            $query->whereBetween('transaction_date', [$start, $end]);
        })->sum('quantity');
    }

    /**
     * Hitung sisa stok
     */
    private function calculateRemainingStock()
    {
        return FertilizerStock::sum('current_stock');
    }

    /**
     * Hitung arus kas koperasi
     */
    private function calculateCashFlow($start, $end)
    {
        return Transaction::whereBetween('transaction_date', [$start, $end])
            ->where('payment_status', 'paid')
            ->sum('total_amount');
    }

    /**
     * Data distribusi pupuk untuk grafik
     */
    private function getFertilizerDistributionData($start, $end)
    {
        $fertilizerTypes = FertilizerType::all();
        $distributionData = [];

        foreach ($fertilizerTypes as $type) {
            $distributed = TransactionDetail::where('fertilizer_type_id', $type->id)
                ->whereHas('transaction', function($query) use ($start, $end) {
                    $query->whereBetween('transaction_date', [$start, $end]);
                })->sum('quantity');

            $stock = FertilizerStock::where('fertilizer_type_id', $type->id)->first();
            $remaining = $stock ? $stock->current_stock : 0;

            $distributionData[] = [
                'name' => $type->fertilizer_name,
                'distributed' => $distributed,
                'remaining' => $remaining,
                'is_subsidized' => $type->is_subsidized
            ];
        }

        return $distributionData;
    }

    /**
     * Riwayat transaksi terbaru
     */
    private function getRecentTransactions($limit = 5)
    {
        $transactions = Transaction::with(['farmer', 'transactionDetails.fertilizerType'])
            ->latest()
            ->limit($limit)
            ->get();

        $formattedTransactions = [];
        foreach ($transactions as $index => $transaction) {
            $totalQuantity = $transaction->transactionDetails->sum('quantity');
            $fertilizerNames = $transaction->transactionDetails
                ->pluck('fertilizerType.fertilizer_name')
                ->unique()
                ->implode(', ');

            $formattedTransactions[] = [
                'id' => $transaction->id,
                'farmer_name' => $transaction->farmer->farmer_name ?? 'N/A',
                'quantity' => $totalQuantity,
                'fertilizer_names' => $fertilizerNames,
                'payment_status' => $this->getPaymentStatusText($transaction->payment_status),
                'total_amount' => $transaction->total_amount,
                'transaction_date' => $transaction->transaction_date
            ];
        }

        return $formattedTransactions;
    }

    /**
     * Data stok pupuk
     */
    private function getFertilizerStocks()
    {
        $stocks = FertilizerStock::with('fertilizerType')->get();
        $formattedStocks = [];

        foreach ($stocks as $stock) {
            // Untuk demo, kita buat data dummy untuk stok masuk dan keluar
            $in = $stock->current_stock + rand(50, 200);
            $out = rand(50, 150);
            $initial = $in - $out;
            
            $formattedStocks[] = [
                'id' => $stock->id,
                'fertilizer_name' => $stock->fertilizerType->fertilizer_name,
                'initial_stock' => $initial,
                'stock_in' => $in,
                'stock_out' => $out,
                'current_stock' => $stock->current_stock,
                'is_subsidized' => $stock->fertilizerType->is_subsidized
            ];
        }

        return $formattedStocks;
    }

    /**
     * Teks status pembayaran
     */
    private function getPaymentStatusText($status)
    {
        $statuses = [
            'paid' => 'Lunas',
            'pending' => 'Belum Bayar',
            'partial' => 'Dibayar Sebagian',
            'cancelled' => 'Dibatalkan'
        ];

        return $statuses[$status] ?? $status;
    }
}