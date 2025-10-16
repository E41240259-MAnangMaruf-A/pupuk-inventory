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
        $fertilizers = FertilizerType::with(['stock', 'subsidyAllocations'])->get()->map(function ($fertilizer) {
            // current stock (single related model)
            $currentStock = $fertilizer->stock->current_stock ?? 0;

            // total subsidized stock (sum of remaining_quota across allocations)
            $subsidizedStock = $fertilizer->subsidyAllocations->sum('remaining_quota') ?? 0;

            // attach computed properties to the model (or return array if preferred)
            $fertilizer->current_stock = (int) $currentStock;
            $fertilizer->subsidized_stock = (int) $subsidizedStock;

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

        foreach ($fertilizers as $index => $fertilizerId) {
            $added = $addedStock[$index] ?? 0;
            $final = $finalStock[$index] ?? 0;

            // Find existing stock
            $stock = FertilizerStock::firstOrCreate(
                ['fertilizer_type_id' => $fertilizerId],
                ['current_stock' => 0]
            );

            // Update current stock
            $stock->current_stock = $final;
            $stock->save();

            // Optional: Log stock history
            FertilizerStockHistory::create([
                'fertilizer_type_id' => $fertilizerId,
                'current_stock' => $final,
                'stock_change' => +$added,
                'final_stock' => $final,
                'type' => 'in',
                'note' => 'Stok ditambahkan melalui stok masuk',
                'user_id' => auth()->id(), // if using auth
            ]);
        }

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    // Menampilkan halaman stok pupuk subsidi
    public function stockSubsidies()
    {
        $farmers = Farmer::all();
        $fertilizers = FertilizerType::with(['allocations.farmer'])->get();
        $allocations = SubsidyAllocation::with(['farmer', 'fertilizerType'])->get();

        return view('fertilizers.stock-subsidies', compact('farmers', 'fertilizers', 'allocations'));
    }

    public function updateStockSubsidy(Request $request)
    {
        // Validate array inputs
        $request->validate([
            'fertilizers' => 'required|array',
            'farmer_id' => 'required|array',
            'quantity' => 'required|array',
            'used_quota' => 'required|array',
            'remaining_quota' => 'required|array',
            'maximum_quota' => 'required|array',
        ]);

        $fertilizers = $request->input('fertilizers');
        $farmers = $request->input('farmer_id');
        $quantities = $request->input('quantity');
        $usedQuotas = $request->input('used_quota');
        $remainingQuotas = $request->input('remaining_quota');
        $maximumQuotas = $request->input('maximum_quota');

        DB::transaction(function () use ($fertilizers, $farmers, $quantities, $usedQuotas, $remainingQuotas, $maximumQuotas) {
            foreach ($fertilizers as $index => $fertilizerId) {
                $farmerId = $farmers[$index];
                $quantity = $quantities[$index];
                $usedQuota = $usedQuotas[$index];
                $remainingQuota = $remainingQuotas[$index];
                $maximumQuota = $maximumQuotas[$index];

                // Update or create subsidy allocation
                SubsidyAllocation::updateOrCreate(
                    [
                        'farmer_id' => $farmerId,
                        'fertilizer_type_id' => $fertilizerId,
                    ],
                    [
                        'used_quota' => $usedQuota,
                        'remaining_quota' => $remainingQuota,
                        'maximum_quota' => $maximumQuota,
                        'status' => 'active',
                    ]
                );

                // update status subsidi
                FertilizerType::find($fertilizerId)->update(['is_subsidized' => true]);

                // Handle FertilizerStock
                // $stock = FertilizerStock::firstOrCreate(
                //     ['fertilizer_type_id' => $fertilizerId],
                //     ['current_stock' => 0]
                // );

                // $oldStock = $stock->current_stock;
                // $final = $stock->current_stock + $quantity;
                // $stock->current_stock = $final;
                // $stock->save();

                // Log stock history
                // FertilizerStockHistory::create([
                //     'fertilizer_type_id' => $fertilizerId,
                //     'current_stock' => $oldStock,
                //     'stock_change' => +$quantity,
                //     'final_stock' => $final,
                //     'type' => 'in',
                //     'note' => 'Stok ditambahkan melalui subsidi stok',
                //     'user_id' => auth()->id(),
                // ]);
            }
        });

        return redirect()->route('fertilizers.stock-subsidies')
            ->with('success', 'Stok subsidi berhasil diperbarui.');
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

    public function fertilizersAjaxSearch(Request $request)
    {
        $query = $request->get('q', default: '');
        $selected_farmer_id = $request->get('farmer_id');

        // 1️⃣ Get fertilizer types
        $fertilizers = FertilizerType::where(function ($q2) use ($query) {
            $q2->where('fertilizer_name', 'like', "%{$query}%")
                ->orWhere('fertilizer_code', 'like', "%{$query}%");
        })
            ->get([
                'id',
                'fertilizer_name as text',
                'unit',
                'subsidized_price',
                'retail_price',
                'description',
                'is_subsidized'
            ])
            ->map(function ($fertilizer) use ($selected_farmer_id) {

                // 2️⃣ Get non-subsidized stock from fertilizer_stocks
                $nonSubsidizedStock = FertilizerStock::where('fertilizer_type_id', $fertilizer->id)
                    ->sum('current_stock');

                // if farmer selected, filter by farmer_id
                $query = SubsidyAllocation::where('fertilizer_type_id', $fertilizer->id);
                if (!empty($selected_farmer_id)) {
                    $query->where('farmer_id', $selected_farmer_id);
                }

                $subsidizedStock = $query->sum('remaining_quota') ?? 0;

                // 4️⃣ Return combined data
                return [
                    'id' => $fertilizer->id,
                    'farmer_id' => $selected_farmer_id,
                    'fertilizer' => $fertilizer,
                    'text' => $fertilizer->text,
                    'unit' => $fertilizer->unit,
                    'subsidized_price' => $fertilizer->subsidized_price,
                    'retail_price' => $fertilizer->retail_price,
                    'description' => $fertilizer->description,
                    'is_subsidized' => $fertilizer->is_subsidized,
                    'stock_subsidized' => $subsidizedStock,
                    'stock_non_subsidized' => $nonSubsidizedStock,
                ];
            });

        return response()->json(['results' => $fertilizers]);
    }

    public function allocationsAjaxSearch(Request $request)
    {
        $farmerId = $request->get('farmer_id');
        $fertilizerId = $request->get('fertilizer_id');

        $allocation = SubsidyAllocation::query()
            ->select([
                'subsidy_allocations.*',
                'farmers.farmer_name',
                'fertilizer_types.fertilizer_name',
                'fertilizer_types.unit',
                'fertilizer_types.subsidized_price',
                'fertilizer_types.retail_price',
            ])
            ->leftJoin('farmers', 'subsidy_allocations.farmer_id', '=', 'farmers.id')
            ->leftJoin('fertilizer_types', 'subsidy_allocations.fertilizer_type_id', '=', 'fertilizer_types.id')
            ->where('farmers.id', $farmerId)
            ->where('fertilizer_types.id', $fertilizerId)
            ->first();

        // If no record exists, still return fertilizer info (so user can add new allocation)
        if (!$allocation) {
            $allocation = FertilizerType::query()
                ->select([
                    'fertilizer_types.id as fertilizer_type_id',
                    'fertilizer_types.fertilizer_name',
                    'fertilizer_types.unit',
                    'fertilizer_types.subsidized_price',
                    'fertilizer_types.retail_price',
                ])
                ->where('fertilizer_types.id', $fertilizerId)
                ->first();

            return response()->json([
                'farmer_id' => $farmerId,
                'fertilizer_type_id' => $allocation->fertilizer_type_id,
                'farmer_name' => null,
                'fertilizer_name' => $allocation->fertilizer_name,
                'maximum_quota' => 0,
                'used_quota' => 0,
                'remaining_quota' => 0,
                'unit' => $allocation->unit,
                'price' => $allocation->subsidized_price ?? $allocation->retail_price,
                'status' => 'active',
            ]);
        }

        return response()->json([
            'farmer_id' => $allocation->farmer_id,
            'fertilizer_type_id' => $allocation->fertilizer_type_id,
            'farmer_name' => $allocation->farmer_name,
            'fertilizer_name' => $allocation->fertilizer_name,
            'maximum_quota' => $allocation->maximum_quota ?? 0,
            'used_quota' => $allocation->used_quota ?? 0,
            'remaining_quota' => $allocation->remaining_quota ?? 0,
            'unit' => $allocation->unit,
            'price' => $allocation->subsidized_price ?? $allocation->retail_price,
            'status' => $allocation->status ?? 'active',
        ]);
    }
}
