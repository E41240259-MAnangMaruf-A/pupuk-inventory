<?php
// app/Http/Controllers/FarmerViewController.php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FarmerViewController extends Controller
{
    public function index()
    {
        try {
            // Data petani yang sudah tervalidasi (aktif)
            $validatedFarmers = Farmer::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Data pengajuan dengan status berbeda
            $pendingSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'pending')
                ->orderBy('submitted_at', 'desc')
                ->get();
                
            $approvedSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'approved')
                ->orderBy('validated_at', 'desc')
                ->get();
                
            $rejectedSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'rejected')
                ->whereNotNull('rejection_reason')
                ->orderBy('validated_at', 'desc')
                ->get();

            // Statistik lengkap
            $stats = [
                'total_validated' => $validatedFarmers->count(),
                'total_pending' => $pendingSubmissions->count(),
                'total_approved' => $approvedSubmissions->count(),
                'total_rejected' => $rejectedSubmissions->count(),
                'male_farmers' => $validatedFarmers->where('gender', 'L')->count(),
                'female_farmers' => $validatedFarmers->where('gender', 'P')->count(),
                'total_land_area' => $validatedFarmers->sum('land_area'),
                'average_land_area' => $validatedFarmers->avg('land_area') ?? 0,
            ];

            return view('farmer-view.index', compact(
                'validatedFarmers',
                'pendingSubmissions',
                'approvedSubmissions',
                'rejectedSubmissions',
                'stats'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in FarmerViewController: ' . $e->getMessage());
            
            return view('farmer-view.index', [
                'validatedFarmers' => collect(),
                'pendingSubmissions' => collect(),
                'approvedSubmissions' => collect(),
                'rejectedSubmissions' => collect(),
                'stats' => [
                    'total_validated' => 0,
                    'total_pending' => 0,
                    'total_approved' => 0,
                    'total_rejected' => 0,
                    'male_farmers' => 0,
                    'female_farmers' => 0,
                    'total_land_area' => 0,
                    'average_land_area' => 0,
                ]
            ])->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function showValidatedFarmers()
    {
        try {
            $validatedFarmers = Farmer::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $stats = [
                'total' => $validatedFarmers->total(),
                'male_count' => Farmer::where('status', 'active')->where('gender', 'L')->count(),
                'female_count' => Farmer::where('status', 'active')->where('gender', 'P')->count(),
                'average_land' => Farmer::where('status', 'active')->avg('land_area') ?? 0,
                'total_land' => Farmer::where('status', 'active')->sum('land_area') ?? 0,
            ];

            return view('farmer-view.validated-farmers', compact('validatedFarmers', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error in showValidatedFarmers: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Gagal memuat data petani tervalidasi.');
        }
    }

    public function showRejectedSubmissions()
    {
        try {
            $rejectedSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'rejected')
                ->orderBy('validated_at', 'desc')
                ->paginate(20);

            $stats = [
                'total' => $rejectedSubmissions->total(),
                'with_reason' => FarmerSubmission::where('status', 'rejected')->whereNotNull('rejection_reason')->count(),
                'without_reason' => FarmerSubmission::where('status', 'rejected')->whereNull('rejection_reason')->count(),
            ];

            return view('farmer-view.rejected-submissions', compact('rejectedSubmissions', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error in showRejectedSubmissions: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Gagal memuat data pengajuan ditolak.');
        }
    }

    public function showPendingSubmissions()
    {
        try {
            $pendingSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'pending')
                ->orderBy('submitted_at', 'desc')
                ->paginate(20);

            $stats = [
                'total' => $pendingSubmissions->total(),
                'today' => FarmerSubmission::where('status', 'pending')
                            ->whereDate('submitted_at', today())
                            ->count(),
                'this_week' => FarmerSubmission::where('status', 'pending')
                            ->whereBetween('submitted_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count(),
            ];

            return view('farmer-view.pending-submissions', compact('pendingSubmissions', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error in showPendingSubmissions: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Gagal memuat data pengajuan menunggu.');
        }
    }

    public function showApprovedSubmissions()
    {
        try {
            $approvedSubmissions = FarmerSubmission::with('validator')
                ->where('status', 'approved')
                ->orderBy('validated_at', 'desc')
                ->paginate(20);

            $stats = [
                'total' => $approvedSubmissions->total(),
                'this_month' => FarmerSubmission::where('status', 'approved')
                            ->whereMonth('validated_at', now()->month)
                            ->count(),
                'last_month' => FarmerSubmission::where('status', 'approved')
                            ->whereMonth('validated_at', now()->subMonth()->month)
                            ->count(),
            ];

            return view('farmer-view.approved-submissions', compact('approvedSubmissions', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error in showApprovedSubmissions: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Gagal memuat data pengajuan disetujui.');
        }
    }

    public function showFarmerDetail($id)
    {
        try {
            $farmer = Farmer::findOrFail($id);
            
            // Get related submissions if any
            $submissions = FarmerSubmission::where('nik', $farmer->nik)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('farmer-view.farmer-detail', compact('farmer', 'submissions'));
        } catch (\Exception $e) {
            \Log::error('Error in showFarmerDetail: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Data petani tidak ditemukan.');
        }
    }

    public function showSubmissionDetail($id)
    {
        try {
            $submission = FarmerSubmission::with('validator')->findOrFail($id);
            
            // Check if this submission has been converted to farmer
            $farmer = Farmer::where('nik', $submission->nik)->first();

            return view('farmer-view.submission-detail', compact('submission', 'farmer'));
        } catch (\Exception $e) {
            \Log::error('Error in showSubmissionDetail: ' . $e->getMessage());
            return redirect()->route('farmer-view.index')
                ->with('error', 'Data pengajuan tidak ditemukan.');
        }
    }

    public function searchFarmers(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $validatedFarmers = Farmer::where('status', 'active')
                ->where(function($query) use ($search) {
                    $query->where('farmer_name', 'like', "%{$search}%")
                          ->orWhere('nik', 'like', "%{$search}%")
                          ->orWhere('address', 'like', "%{$search}%")
                          ->orWhere('main_commodity', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('farmer-view.validated-farmers', compact('validatedFarmers'))
                ->with('search', $search);
        } catch (\Exception $e) {
            \Log::error('Error in searchFarmers: ' . $e->getMessage());
            return redirect()->route('farmer-view.validated-farmers')
                ->with('error', 'Gagal melakukan pencarian.');
        }
    }

    public function exportValidatedFarmers()
    {
        try {
            $farmers = Farmer::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Simple CSV export (bisa dikembangkan lebih lanjut)
            $filename = "petani_tervalidasi_" . date('Y-m-d') . ".csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($farmers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, [
                    'NIK', 'Nama Petani', 'Alamat', 'Telepon', 'Gender', 
                    'Luas Lahan (Ha)', 'Lokasi Lahan', 'Status Lahan', 
                    'Komoditas Utama', 'Rata-rata Panen (Ton)', 'Tanggal Registrasi'
                ]);

                foreach ($farmers as $farmer) {
                    fputcsv($file, [
                        $farmer->nik,
                        $farmer->farmer_name,
                        $farmer->address,
                        $farmer->phone_number ?? '-',
                        $farmer->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                        $farmer->land_area ?? '0',
                        $farmer->land_location ?? '-',
                        $farmer->land_status ? ucfirst($farmer->land_status) : '-',
                        $farmer->main_commodity ?? '-',
                        $farmer->average_harvest ?? '0',
                        $farmer->created_at->format('d/m/Y')
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Error in exportValidatedFarmers: ' . $e->getMessage());
            return redirect()->route('farmer-view.validated-farmers')
                ->with('error', 'Gagal mengekspor data.');
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'validated_farmers' => Farmer::where('status', 'active')->count(),
                'pending_submissions' => FarmerSubmission::where('status', 'pending')->count(),
                'approved_submissions' => FarmerSubmission::where('status', 'approved')->count(),
                'rejected_submissions' => FarmerSubmission::where('status', 'rejected')->count(),
                'male_farmers' => Farmer::where('status', 'active')->where('gender', 'L')->count(),
                'female_farmers' => Farmer::where('status', 'active')->where('gender', 'P')->count(),
                'total_land_area' => Farmer::where('status', 'active')->sum('land_area'),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error in getStats: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat statistik'], 500);
        }
    }

    // Method untuk filter data
    public function filterFarmers(Request $request)
    {
        try {
            $query = Farmer::where('status', 'active');
            
            // Filter by gender
            if ($request->has('gender') && $request->gender != 'all') {
                $query->where('gender', $request->gender);
            }
            
            // Filter by land status
            if ($request->has('land_status') && $request->land_status != 'all') {
                $query->where('land_status', $request->land_status);
            }
            
            // Filter by commodity
            if ($request->has('commodity') && $request->commodity != 'all') {
                $query->where('main_commodity', 'like', "%{$request->commodity}%");
            }

            $validatedFarmers = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('farmer-view.validated-farmers', compact('validatedFarmers'))
                ->with('filters', $request->all());
        } catch (\Exception $e) {
            \Log::error('Error in filterFarmers: ' . $e->getMessage());
            return redirect()->route('farmer-view.validated-farmers')
                ->with('error', 'Gagal melakukan filter data.');
        }
    }
}