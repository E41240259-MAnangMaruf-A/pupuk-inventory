<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerSubmission;

use Illuminate\Http\Request;

class KepalaDesaController extends Controller
{
    /**
     * Dashboard Kepala Desa
     */
    public function dashboard()
    {
        $totalFarmers = Farmer::count();
        $pendingSubmissions = FarmerSubmission::where('status', 'pending')->count();
        $rejectedSubmissions = FarmerSubmission::where('status', 'rejected')->count();
        $approvedSubmissions = FarmerSubmission::where('status', 'approved')->count();
        
        return view('kepala-desa.dashboard', compact(
            'totalFarmers', 
            'pendingSubmissions', 
            'rejectedSubmissions',
            'approvedSubmissions'
        ));
    }

    /**
     * Menampilkan data petani tervalidasi
     */
    public function petaniValidated()
    {
        $farmers = Farmer::orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => Farmer::count(),
            'active' => Farmer::where('status', 'active')->count(),
            'inactive' => Farmer::where('status', 'inactive')->count(),
        ];

        return view('kepala-desa.petani.validated', compact('farmers', 'stats'));
    }

    /**
     * Menampilkan data pengajuan yang menunggu validasi
     */
    public function petaniPending()
    {
        $submissions = FarmerSubmission::where('status', 'pending')
            ->orderBy('submitted_at', 'desc')
            ->get();

        $stats = [
            'total' => $submissions->count(),
            'recent' => $submissions->where('submitted_at', '>=', now()->subDays(7))->count(),
        ];

        return view('kepala-desa.petani.pending', compact('submissions', 'stats'));
    }

    /**
     * Menampilkan data pengajuan yang ditolak
     */
    public function petaniRejected()
    {
        $submissions = FarmerSubmission::where('status', 'rejected')
            ->orderBy('validated_at', 'desc')
            ->get();

        $stats = [
            'total' => $submissions->count(),
            'recent' => $submissions->where('validated_at', '>=', now()->subDays(30))->count(),
        ];

        return view('kepala-desa.petani.rejected', compact('submissions', 'stats'));
    }

    /**
     * Detail data petani tervalidasi
     */
    public function showPetani($id)
    {
        $farmer = Farmer::findOrFail($id);
        return view('kepala-desa.petani.show', compact('farmer'));
    }

    /**
     * Detail pengajuan petani
     */
    public function showSubmission($id)
    {
        $submission = FarmerSubmission::findOrFail($id);
        return view('kepala-desa.petani.show-submission', compact('submission'));
    }

    
}