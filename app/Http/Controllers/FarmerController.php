<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerSubmission;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = Farmer::all();
        $pendingSubmissions = FarmerSubmission::pending()->count();
        return view('farmers.index', compact('farmers', 'pendingSubmissions'));
    }

    public function show(Farmer $farmer)
    {
        return view('farmers.show', compact('farmer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:farmers,nik|unique:farmer_submissions,nik|max:16',
            'farmer_name' => 'required|max:100',
            'gender' => 'required|in:L,P',
        ]);

        Farmer::create($request->all());
        return redirect()->route('farmers.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function update(Request $request, Farmer $farmer)
    {
        $request->validate([
            'nik' => 'required|max:16|unique:farmers,nik,' . $farmer->id . '|unique:farmer_submissions,nik',
            'farmer_name' => 'required|max:100',
            'gender' => 'required|in:L,P',
        ]);

        $farmer->update($request->all());
        return redirect()->route('farmers.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return redirect()->route('farmers.index')->with('success', 'Data petani berhasil dihapus.');
    }

    // Method untuk menampilkan pengajuan yang perlu divalidasi
    public function submissions()
    {
        $submissions = FarmerSubmission::pending()->orderBy('submitted_at', 'desc')->get();
        return view('farmers.submissions', compact('submissions'));
    }

    public function ajaxSearch(Request $request)
    {
        $term = $request->get('q', '');

        $farmers = Farmer::where('farmer_name', 'LIKE', "%{$term}%")
            ->orWhere('nik', 'LIKE', "%{$term}%")
            ->limit(20)
            ->get();

        $results = [];

        foreach ($farmers as $farmer) {
            $results[] = [
                'id' => $farmer->id,
                'text' => $farmer->farmer_name . ' (' . $farmer->nik . ')'
            ];
        }

        return response()->json(['results' => $results]);
    }
}