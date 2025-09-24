<?php

namespace App\Http\Controllers;

use App\Models\FarmerSubmission;
use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerSubmissionController extends Controller
{
    public function index()
    {
        $submissions = FarmerSubmission::orderBy('submitted_at', 'desc')->get();
        return view('farmer-submissions.index', compact('submissions'));
    }

    public function create()
    {
        return view('farmer-submissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:farmer_submissions,nik|unique:farmers,nik|max:16',
            'farmer_name' => 'required|max:100',
            'address' => 'required',
            'gender' => 'required|in:L,P',
            'land_location' => 'nullable|string',
            'land_status' => 'nullable|in:milik,sewa,garap',
            'main_commodity' => 'nullable|string',
            'average_harvest' => 'nullable|numeric|min:0',
        ]);

        FarmerSubmission::create($request->all());
        return redirect()->route('farmer-submissions.index')
                        ->with('success', 'Pengajuan data petani berhasil dikirim.');
    }

    public function show(FarmerSubmission $farmerSubmission)
    {
        return view('farmer-submissions.show', compact('farmerSubmission'));
    }

    public function edit(FarmerSubmission $farmerSubmission)
    {
        if ($farmerSubmission->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diedit.');
        }
        return view('farmer-submissions.edit', compact('farmerSubmission'));
    }

    public function update(Request $request, FarmerSubmission $farmerSubmission)
    {
        if ($farmerSubmission->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diedit.');
        }

        $request->validate([
            'nik' => 'required|max:16|unique:farmer_submissions,nik,' . $farmerSubmission->id . '|unique:farmers,nik',
            'farmer_name' => 'required|max:100',
            'address' => 'required',
            'gender' => 'required|in:L,P',
            'land_location' => 'nullable|string',
            'land_status' => 'nullable|in:milik,sewa,garap',
            'main_commodity' => 'nullable|string',
            'average_harvest' => 'nullable|numeric|min:0',
        ]);

        $farmerSubmission->update($request->all());
        return redirect()->route('farmer-submissions.index')
                        ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(FarmerSubmission $farmerSubmission)
    {
        if ($farmerSubmission->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat dihapus.');
        }
        
        $farmerSubmission->delete();
        return redirect()->route('farmer-submissions.index')
                        ->with('success', 'Pengajuan berhasil dihapus.');
    }

    // Method untuk validasi admin
    public function validate(Request $request, FarmerSubmission $farmerSubmission)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject'
        ]);

        if ($request->action === 'approve') {
            // Buat data farmer baru
            $farmer = Farmer::create([
                'nik' => $farmerSubmission->nik,
                'farmer_name' => $farmerSubmission->farmer_name,
                'address' => $farmerSubmission->address,
                'phone_number' => $farmerSubmission->phone_number,
                'birth_date' => $farmerSubmission->birth_date,
                'gender' => $farmerSubmission->gender,
                'land_area' => $farmerSubmission->land_area,
                'land_location' => $farmerSubmission->land_location,
                'land_status' => $farmerSubmission->land_status,
                'main_commodity' => $farmerSubmission->main_commodity,
                'average_harvest' => $farmerSubmission->average_harvest,
                'status' => 'active'
            ]);

            $farmerSubmission->update([
                'status' => 'approved',
                'validated_at' => now(),
                'validated_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Pengajuan disetujui dan data petani berhasil ditambahkan.');
        } else {
            $farmerSubmission->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'validated_at' => now(),
                'validated_by' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Pengajuan ditolak.');
        }
    }
}
