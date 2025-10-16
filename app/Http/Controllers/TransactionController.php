<?php

namespace App\Http\Controllers;

use App\Models\Cooperative;
use App\Models\FertilizerStock;
use App\Models\FertilizerStockHistory;
use App\Models\FertilizerType;
use App\Models\SubsidyAllocation;
use App\Models\SubsidyAllocationHistory;
use App\Models\TransactionDetail;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Farmer;
use App\Models\Fertilizer;
use PDF; // jika ingin export PDF
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        $transactions = Transaction::with(['farmer', 'user', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();

        $customers = Farmer::all();

        return view('transactions.index', compact('transactions', 'customers'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'fertilizer_id' => 'required|array',
            'fertilizer_id.*' => 'exists:fertilizer_types,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'retail_price' => 'required|array',
            'retail_price.*' => 'numeric|min:0',
        ], [], [
            'farmer_id' => 'Nama Petani',
            'cooperative_id' => 'Koperasi',
            'transaction_date' => 'Tanggal Transaksi',
            'fertilizer_id' => 'Jenis Pupuk',
            'quantity' => 'Jumlah',
            'retail_price' => 'Harga Satuan',
        ]);

        DB::beginTransaction();

        try {
            $lastTransaction = Transaction::latest('id')->first();

            $newNumber = $lastTransaction
                ? str_pad((int) substr($lastTransaction->transaction_number, 4) + 1, 5, '0', STR_PAD_LEFT)
                : '00001';

            $transactionNumber = 'TRX-' . $newNumber;

            $transaction = Transaction::create([
                'transaction_number' => $transactionNumber,
                'farmer_id' => $request->farmer_id,
                'cooperative_id' => Cooperative::first()->id ?? null,
                'user_id' => auth()->id(),
                'transaction_date' => now(),
                'total_amount' => 0,
                'payment_status' => 'paid',
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->fertilizer_id as $index => $fertilizerId) {
                $quantity = $request->quantity[$index];

                // Ambil data subsidi & stok
                $allocation = SubsidyAllocation::where('farmer_id', $request->farmer_id)
                    ->where('fertilizer_type_id', $fertilizerId)
                    ->first();

                $stock = FertilizerStock::where('fertilizer_type_id', $fertilizerId)->first();
                $fertilizer = FertilizerType::find($fertilizerId);

                if (!$stock) {
                    throw new Exception("Stok untuk pupuk " . $fertilizer->fertilizer_name . " belum terdaftar!");
                }

                $usedFromSubsidy = 0;
                $usedFromStock = 0;
                $isSubsidized = false;
                $unitPrice = $request->retail_price[$index];

                // ==============================
                // 1️⃣ Tentukan sumber pupuk
                // ==============================
                if ($allocation && $allocation->remaining_quota >= $quantity) {
                    // Penuhi dari subsidi sepenuhnya
                    $usedFromSubsidy = $quantity;
                    $isSubsidized = true;
                    $unitPrice = $request->subsidized_price[$index];

                    // Update alokasi subsidi
                    $allocation->used_quota += $quantity;
                    $allocation->remaining_quota -= $quantity;
                    $allocation->save();

                    if ($allocation->remaining_quota <= 0) {
                        $allocation->remaining_quota = 0;
                        $allocation->save();

                        // Update status pupuk di FertilizerType
                        $fertilizer = FertilizerType::find($fertilizerId);
                        if ($fertilizer) {
                            $fertilizer->is_subsidized = false;
                            $fertilizer->save();
                        }
                    }

                    // Catat histori subsidi
                    SubsidyAllocationHistory::create([
                        'subsidy_allocation_id' => $allocation->id,
                        'fertilizer_type_id' => $fertilizerId,
                        'transaction_id' => $transaction->id,
                        'quantity' => $usedFromSubsidy,
                        'type' => 'use',
                        'note' => 'Penggunaan subsidi pada transaksi #' . $transaction->transaction_number,
                    ]);
                } else {
                    // Tidak cukup atau tidak punya subsidi → ambil semua dari stok umum
                    $usedFromStock = $quantity;
                    $isSubsidized = false;
                    $unitPrice = $request->retail_price[$index];

                    // ==============================
                    // 2️⃣ Kurangi stok fisik
                    // ==============================
                    if ($stock->current_stock < $quantity) {
                        throw new Exception("Stok pupuk tidak mencukupi untuk transaksi ini!");
                    }

                    $current_stock = $stock->current_stock;
                    $final_stock = $stock->current_stock -= $quantity;
                    $stock->save();

                    FertilizerStockHistory::create([
                        'fertilizer_type_id' => $fertilizerId,
                        'current_stock' => $current_stock,
                        'stock_change' => -$quantity,
                        'final_stock' => $final_stock,
                        'type' => 'out',
                        'note' => "Transaksi #" . $transaction->transaction_number,
                        'user_id' => auth()->id(),
                    ]);
                }

                // ==============================
                // 3️⃣ Simpan detail transaksi
                // ==============================
                $subtotal = $quantity * $unitPrice;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'fertilizer_type_id' => $fertilizerId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'is_subsidized' => $isSubsidized,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            $totalPayment = (int) str_replace('.', '', $request->total_payment);
            $totalChange = (int) str_replace(['Rp.', 'Rp', '.', ' '], '', $request->total_change);

            if ($totalPayment < $totalAmount) {
                return back()
                    ->withErrors(['pembayaran' => 'Jumlah pembayaran tidak boleh kurang dari total biaya (Rp ' . number_format($totalAmount, 0, ',', '.') . ').'])
                    ->withInput();
            }

            $transaction->update(['total_amount' => $totalAmount, 'total_payment' => $totalPayment, 'total_change' => $totalChange]);

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Tampilkan detail transaksi
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // Update transaksi
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'fertilizer_id' => 'required|exists:fertilizers,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $transaction->update([
            'farmer_id' => $request->farmer_id,
            'fertilizer_id' => $request->fertilizer_id,
            'quantity' => $request->quantity,
            'total_price' => $transaction->fertilizer->price * $request->quantity,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    // Hapus transaksi
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    // Cetak struk transaksi
    public function printReceipt(Transaction $transaction)
    {
        $pdf = PDF::loadView('transactions.receipt', compact('transaction'));
        return $pdf->download('struk_transaksi_' . $transaction->id . '.pdf');
    }
}
