<?php

namespace App\Http\Controllers;

use App\Models\FertilizerStock;
use App\Models\FertilizerStockHistory;
use App\Models\FertilizerType;
use DB;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    public function index()
    {
        $fertilizers = FertilizerType::all();
        return view('fertilizers.index', compact('fertilizers'));
    }

    public function stock()
    {
        $fertilizerStocks = FertilizerType::with('stock')->get()->map(function ($fertilizer) {
            $fertilizer->current_stock = $fertilizer->stock->current_stock ?? 0;
            return $fertilizer;
        });

        $fertilizers = FertilizerType::all();

        $stockHistories = FertilizerStockHistory::with('fertilizerType')->latest()->get();

        return view('fertilizers.stocks', compact('fertilizers', 'fertilizerStocks', 'stockHistories'));
    }

    public function updateStockIn(Request $request)
    {
        $request->validate([
            'fertilizers' => 'required|array',
            'added_stock' => 'required|array',
            'final_stock' => 'required|array',
            'price' => 'nullable|array',
            'subtotal' => 'nullable|array',
        ]);

        $fertilizers = $request->input('fertilizers');      // array of fertilizer IDs
        $addedStock = $request->input('added_stock');      // array of added stock
        $finalStock = $request->input('final_stock');      // array of final stock
        $prices = $request->input('price');            // optional
        $subtotals = $request->input('subtotal');         // optional

        foreach ($fertilizers as $index => $fertId) {
            $added = $addedStock[$index] ?? 0;
            $final = $finalStock[$index] ?? 0;

            // Find existing stock
            $stock = FertilizerStock::firstOrCreate(
                ['fertilizer_type_id' => $fertId],
                ['current_stock' => 0]
            );

            // Update current stock
            $stock->current_stock = $final;
            $stock->save();

            // Optional: Log stock history
            FertilizerStockHistory::create([
                'fertilizer_type_id' => $fertId,
                'current_stock' => $final,
                'stock_change' => $added,
                'type' => 'in',
                'note' => 'Stock added via form',
                'user_id' => auth()->id(), // if using auth
            ]);
        }

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fertilizer_name' => 'required|max:100',
            'unit' => 'nullable|max:20',
            'subsidized_price' => 'nullable|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_subsidized' => 'nullable|boolean',
        ]);

        FertilizerType::create($data);

        return redirect()->route('fertilizers.index')
            ->with('success', 'Fertilizer type added successfully.');
    }

    public function update(Request $request, FertilizerType $fertilizer)
    {
        $data = $request->validate([
            'fertilizer_name' => 'required|max:100',
            'unit' => 'nullable|max:20',
            'subsidized_price' => 'nullable|numeric|min:0',
            'retail_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_subsidized' => 'nullable|boolean',
        ]);

        $fertilizer->update($data);
        return redirect()->route('fertilizers.index')
            ->with('success', 'Fertilizer type updated successfully.');
    }

    public function destroy(FertilizerType $fertilizer)
    {
        $fertilizer->delete();
        return redirect()->route('fertilizers.index')
            ->with('success', 'Fertilizer type deleted successfully.');
    }
}
