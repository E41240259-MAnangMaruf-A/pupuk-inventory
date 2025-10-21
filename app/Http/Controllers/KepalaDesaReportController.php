<?php
// app/Http/Controllers/KepalaDesaReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use PDF;

class KepalaDesaReportController extends Controller
{
    /**
     * ==================================================================
     * LAPORAN PERGERAKAN PUPUK
     * ==================================================================
     */
    
    public function fertilizerMovement(Request $request)
    {
        try {
            $movements = collect();
            $summary = [
                'total_stock_in' => 0,
                'total_stock_out' => 0,
                'current_stock' => 0,
                'total_value' => 0
            ];

            $fertilizerTypes = [];

            if (Schema::hasTable('fertilizer_stock_histories')) {
                $query = DB::table('fertilizer_stock_histories as fsh')
                    ->leftJoin('fertilizer_types as ft', 'fsh.fertilizer_type_id', '=', 'ft.id')
                    ->leftJoin('users as u', 'fsh.user_id', '=', 'u.id')
                    ->select(
                        'fsh.*',
                        'ft.fertilizer_name',
                        'ft.retail_price',
                        'u.name as user_name'
                    );

                // Apply filter if provided
                if ($request->has('start_date') && $request->start_date && 
                    $request->has('end_date') && $request->end_date) {
                    $query->whereBetween('fsh.created_at', [
                        $request->start_date . ' 00:00:00',
                        $request->end_date . ' 23:59:59'
                    ]);
                }

                $movements = $query->orderBy('fsh.created_at', 'desc')->get();

                $summary['total_stock_in'] = $movements->where('type', 'in')->sum('stock_change');
                $summary['total_stock_out'] = abs($movements->where('type', 'out')->sum('stock_change'));

                if (Schema::hasTable('fertilizer_stocks')) {
                    $currentStocks = DB::table('fertilizer_stocks as fs')
                        ->join('fertilizer_types as ft', 'fs.fertilizer_type_id', '=', 'ft.id')
                        ->select('fs.current_stock', 'ft.retail_price')
                        ->get();

                    foreach ($currentStocks as $stock) {
                        $summary['current_stock'] += $stock->current_stock;
                        $summary['total_value'] += $stock->current_stock * ($stock->retail_price ?? 0);
                    }
                }

                if (Schema::hasTable('fertilizer_types')) {
                    $fertilizerTypes = DB::table('fertilizer_types')
                        ->select('id', 'fertilizer_name')
                        ->get();
                }
            }

            return view('kepala-desa.reports.fertilizer-movement', compact(
                'movements', 
                'summary', 
                'fertilizerTypes'
            ));

        } catch (\Exception $e) {
            \Log::error('Fertilizer Movement Report Error: '. $e->getMessage());
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    /**
     * Export PDF - Laporan Pergerakan Pupuk
     */
    public function exportFertilizerMovementPDF(Request $request)
{
    try {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        \Log::info('Export Fertilizer PDF - Start: '. $startDate . ', End: '. $endDate);

        $movements = collect();
        $summary = [
            'total_stock_in' => 0,
            'total_stock_out' => 0,
            'current_stock' => 0,
            'total_value' => 0
        ];

        if (Schema::hasTable('fertilizer_stock_histories')) {
            $query = DB::table('fertilizer_stock_histories as fsh')
                ->leftJoin('fertilizer_types as ft', 'fsh.fertilizer_type_id', '=', 'ft.id')
                ->leftJoin('users as u', 'fsh.user_id', '=', 'u.id')
                ->select(
                    'fsh.*',
                    'ft.fertilizer_name',
                    'ft.retail_price',
                    'u.name as user_name'
                );

            if ($startDate && $endDate) {
                $query->whereBetween('fsh.created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            }

            $movements = $query->orderBy('fsh.created_at', 'desc')->get();

            $summary['total_stock_in'] = $movements->where('type', 'in')->sum('stock_change');
            $summary['total_stock_out'] = abs($movements->where('type', 'out')->sum('stock_change'));

            if (Schema::hasTable('fertilizer_stocks')) {
                $currentStocks = DB::table('fertilizer_stocks as fs')
                    ->join('fertilizer_types as ft', 'fs.fertilizer_type_id', '=', 'ft.id')
                    ->select('fs.current_stock', 'ft.retail_price')
                    ->get();

                foreach ($currentStocks as $stock) {
                    $summary['current_stock'] += $stock->current_stock;
                    $summary['total_value'] += $stock->current_stock * ($stock->retail_price ?? 0);
                }
            }
        }

        $pdf = PDF::loadView('kepala-desa.reports.pdf.fertilizer-movement', [
            'movements' => $movements,
            'summary' => $summary,
            'exportDate' => now()->format('d/m/Y H:i'),
            'periodStart' => Carbon::parse($startDate)->format('d/m/Y'),
            'periodEnd' => Carbon::parse($endDate)->format('d/m/Y'),
            'title' => 'Laporan Pergerakan Pupuk'
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pergerakan-pupuk-' . $startDate . '-to-' . $endDate . '.pdf');

    } catch (\Exception $e) {
        \Log::error('Export Fertilizer PDF Error: '. $e->getMessage());
        \Log::error($e->getTraceAsString());
        return back()->with('error', 'Gagal mengekspor PDF: '. $e->getMessage());
    }
}

    /**
     * ==================================================================
     * LAPORAN ALOKASI SUBSIDI
     * ==================================================================
     */
    
    public function subsidyAllocation(Request $request)
    {
        try {
            $allocations = collect();
            $summary = [
                'total_allocated' => 0,
                'total_used' => 0,
                'total_remaining' => 0,
                'total_subsidy_value' => 0
            ];

            // Ambil filter tanggal dari request
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            if (Schema::hasTable('subsidy_allocations')) {
                $query = DB::table('subsidy_allocations as sa')
                    ->leftJoin('farmers as f', 'sa.farmer_id', '=', 'f.id')
                    ->leftJoin('fertilizer_types as ft', 'sa.fertilizer_type_id', '=', 'ft.id')
                    ->select(
                        'sa.*',
                        'f.farmer_name',
                        'f.nik',
                        'ft.fertilizer_name',
                        'ft.subsidized_price'
                    );

                // Apply date filter if provided
                if ($startDate && $endDate) {
                    $query->whereBetween('sa.created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }

                $allocations = $query->orderBy('sa.created_at', 'desc')->get();

                $summary['total_allocated'] = $allocations->sum('maximum_quota');
                $summary['total_used'] = $allocations->sum('used_quota');
                $summary['total_remaining'] = $allocations->sum('remaining_quota');
                $summary['total_subsidy_value'] = $allocations->sum(function($item) {
                    return $item->used_quota * ($item->subsidized_price ?? 0);
                });
            }

            return view('kepala-desa.reports.subsidy-allocation', compact('allocations', 'summary'));

        } catch (\Exception $e) {
            \Log::error('Subsidy Allocation Report Error: '. $e->getMessage());
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    /**
     * Export PDF - Laporan Alokasi Subsidi
     */
    public function exportSubsidyAllocationPDF(Request $request)
    {
        try {
            // Ambil parameter filter dari request
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            \Log::info('Export Subsidy PDF - Start: ' . $startDate . ', End: ' . $endDate);
            
            $allocations = collect();
            $summary = [
                'total_allocated' => 0,
                'total_used' => 0,
                'total_remaining' => 0,
                'total_subsidy_value' => 0
            ];

            if (Schema::hasTable('subsidy_allocations')) {
                $query = DB::table('subsidy_allocations as sa')
                    ->leftJoin('farmers as f', 'sa.farmer_id', '=', 'f.id')
                    ->leftJoin('fertilizer_types as ft', 'sa.fertilizer_type_id', '=', 'ft.id')
                    ->select(
                        'sa.*',
                        'f.farmer_name',
                        'f.nik',
                        'ft.fertilizer_name',
                        'ft.subsidized_price'
                    );

                // Apply date filter if provided
                if ($startDate && $endDate) {
                    $query->whereBetween('sa.created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }

                $allocations = $query->orderBy('f.farmer_name', 'asc')->get();

                $summary['total_allocated'] = $allocations->sum('maximum_quota');
                $summary['total_used'] = $allocations->sum('used_quota');
                $summary['total_remaining'] = $allocations->sum('remaining_quota');
                $summary['total_subsidy_value'] = $allocations->sum(function($item) {
                    return $item->used_quota * ($item->subsidized_price ?? 0);
                });
            }

            // Format period info
            $periodInfo = '';
            if ($startDate && $endDate) {
                $periodInfo = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
            } else {
                $periodInfo = 'Semua Data';
            }

            $pdf = PDF::loadView('kepala-desa.reports.pdf.subsidy-allocation-pdf', [
                'allocations' => $allocations,
                'summary' => $summary,
                'exportDate' => now()->format('d/m/Y H:i'),
                'periodInfo' => $periodInfo,
                'title' => 'Laporan Alokasi Subsidi Pupuk'
            ])->setPaper('a4', 'landscape');

            $filename = 'laporan-alokasi-subsidi-' . ($startDate ? $startDate . '-to-' . $endDate : 'all') . '.pdf';
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Export Subsidy PDF Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * ==================================================================
     * LAPORAN KEUANGAN - DIPERBAIKI
     * ==================================================================
     */
    
    public function financial(Request $request)
    {
        try {
            $transactions = collect();
            $summary = [
                'total_income' => 0,
                'total_expense' => 0,
                'net_income' => 0,
                'total_transactions' => 0
            ];

            // Gunakan filter tanggal seperti laporan lainnya
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

            if (Schema::hasTable('transactions')) {
                $query = DB::table('transactions as t')
                    ->leftJoin('farmers as f', 't.farmer_id', '=', 'f.id')
                    ->leftJoin('users as u', 't.user_id', '=', 'u.id')
                    ->select(
                        't.*',
                        'f.farmer_name',
                        'u.name as cashier_name'
                    );

                // Apply date filter seperti laporan lainnya
                if ($startDate && $endDate) {
                    $query->whereBetween('t.transaction_date', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }

                $transactions = $query->orderBy('t.transaction_date', 'desc')->get();

                $summary['total_income'] = $transactions->sum('total_amount');
                $summary['total_expense'] = 0; // Sesuaikan jika ada data pengeluaran
                $summary['net_income'] = $summary['total_income'] - $summary['total_expense'];
                $summary['total_transactions'] = $transactions->count();
            }

            return view('kepala-desa.reports.financial', compact(
                'transactions',
                'summary',
                'startDate',
                'endDate'
            ));

        } catch (\Exception $e) {
            \Log::error('Financial Report Error: '. $e->getMessage());
            return redirect()->route('kepala-desa.dashboard')
                ->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    /**
     * Export PDF - Laporan Keuangan - DIPERBAIKI
     */
    public function exportFinancialPDF(Request $request)
{
    try {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        \Log::info('Export Financial PDF - Start: '. $startDate . ', End: '. $endDate);

        $transactions = collect();
        $summary = [
            'total_income' => 0,
            'total_expense' => 0,
            'net_income' => 0,
            'total_transactions' => 0
        ];

        if (Schema::hasTable('transactions')) {
            $query = DB::table('transactions as t')
                ->leftJoin('farmers as f', 't.farmer_id', '=', 'f.id')
                ->leftJoin('users as u', 't.user_id', '=', 'u.id')
                ->select(
                    't.*',
                    'f.farmer_name',
                    'u.name as cashier_name'
                );

            if ($startDate && $endDate) {
                $query->whereBetween('t.transaction_date', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            }

            $transactions = $query->orderBy('t.transaction_date', 'desc')->get();

            $summary['total_income'] = $transactions->sum('total_amount');
            $summary['total_expense'] = 0;
            $summary['net_income'] = $summary['total_income'] - $summary['total_expense'];
            $summary['total_transactions'] = $transactions->count();
        }

        $periodInfo = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        $pdf = PDF::loadView('kepala-desa.reports.pdf.financial', [
            'transactions' => $transactions,
            'summary' => $summary,
            'periodInfo' => $periodInfo,
            'exportDate' => now()->format('d/m/Y H:i'),
            'title' => 'Laporan Keuangan Koperasi'
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan-keuangan-' . $startDate . '-to-' . $endDate . '.pdf';
        return $pdf->download($filename);

    } catch (\Exception $e) {
        \Log::error('Export Financial PDF Error: '. $e->getMessage());
        \Log::error($e->getTraceAsString());
        return back()->with('error', 'Gagal mengekspor PDF: '. $e->getMessage());
    }
}
    /**
     * Export Semua Laporan (jika diperlukan)
     */
    public function exportAllReportsPDF(Request $request)
    {
        // Method untuk export semua laporan sekaligus
        // Bisa diimplementasikan jika diperlukan
    }
}