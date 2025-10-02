<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FertilizerStock;
use App\Models\FertilizerStockHistory;
use App\Models\FertilizerType;
use App\Models\SubsidyAllocation;
use DB;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    public function index()
    {
        $fertilizers = FertilizerType::with('stock')->get()->map(function ($fertilizer) {
            $fertilizer->current_stock = $fertilizer->stock->current_stock ?? 0;
            return $fertilizer;
        });

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

    // Menampilkan halaman stok pupuk subsidi
    public function stockSubsidies()
    {
        $farmers = Farmer::all();
        $fertilizers = FertilizerType::where('is_subsidized', true)->get();
        $allocations = SubsidyAllocation::with(['farmer', 'fertilizerType'])->get();

        return view('fertilizers.stock-subsidies', compact('farmers', 'fertilizers', 'allocations'));
    }

    // Simpan atau update alokasi stok subsidi
    public function updateStockSubsidy(Request $request)
    {
        $data = $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'fertilizer_type_id' => 'required|exists:fertilizer_types,id',
            'maximum_quota' => 'required|integer|min:1',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data['used_quota'] = 0;
        $data['remaining_quota'] = $data['maximum_quota'];
        $data['status'] = 'active';

        SubsidyAllocation::updateOrCreate(
            [
                'farmer_id' => $data['farmer_id'],
                'fertilizer_type_id' => $data['fertilizer_type_id'],
            ],
            $data
        );

        return redirect()->route('fertilizers.stock-subsidy')
            ->with('success', 'Subsidy allocation saved successfully.');
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
            'initial_stock' => 'numeric',
        ]);

        $fertilizerType = FertilizerType::create($data);

        // Buat stok awal (default 0)
        $stock = FertilizerStock::firstOrCreate(
            ['fertilizer_type_id' => $fertilizerType->id],
            ['current_stock' => $data['initial_stock']]
        );

        // Catat histori stok (awal 0)
        FertilizerStockHistory::create([
            'fertilizer_type_id' => $fertilizerType->id,
            'current_stock' => $data['initial_stock'],
            'stock_change' => $data['initial_stock'],
            'type' => 'in',
            'note' => 'Stok awal',
            'user_id' => auth()->id(),
        ]);

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

    public function ajaxSearch(Request $request)
    {
        $query = $request->get('q', '');
        $fertilizers = FertilizerType::where('name', 'like', "%$query%")
            ->select('id', 'name as value') // jQuery UI autocomplete pakai "value"
            ->get();

        return response()->json(['results' => $fertilizers]);
    }
}
