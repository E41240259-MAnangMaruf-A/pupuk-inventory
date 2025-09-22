<?php

namespace App\Http\Controllers;

use App\Models\FertilizerType;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    public function index()
    {
        $fertilizers = FertilizerType::all();
        return view('fertilizers.index', compact('fertilizers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fertilizer_name'   => 'required|max:100',
            'unit'              => 'nullable|max:20',
            'subsidized_price'  => 'nullable|numeric|min:0',
            'retail_price'      => 'nullable|numeric|min:0',
            'description'       => 'nullable|string',
            'is_active'         => 'nullable|boolean',
        ]);

        FertilizerType::create($request->all());
        return redirect()->route('fertilizers.index')
                         ->with('success','Fertilizer type added successfully.');
    }

    public function update(Request $request, FertilizerType $fertilizer)
    {
        $request->validate([
            'fertilizer_name'   => 'required|max:100',
            'unit'              => 'nullable|max:20',
            'subsidized_price'  => 'nullable|numeric|min:0',
            'retail_price'      => 'nullable|numeric|min:0',
            'description'       => 'nullable|string',
            'is_active'         => 'nullable|boolean',
        ]);

        $fertilizer->update($request->all());
        return redirect()->route('fertilizers.index')
                         ->with('success','Fertilizer type updated successfully.');
    }

    public function destroy(FertilizerType $fertilizer)
    {
        $fertilizer->delete();
        return redirect()->route('fertilizers.index')
                         ->with('success','Fertilizer type deleted successfully.');
    }
}
