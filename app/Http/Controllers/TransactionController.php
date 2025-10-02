<?php

namespace App\Http\Controllers;

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
        $transactions = Transaction::with(['farmer', 'fertilizer'])
            ->orderBy('created_at', 'desc')
            ->get();

        $customers = Farmer::all();

        return view('transactions.index', compact('transactions', 'customers'));
    }

    // Menampilkan form transaksi baru
    public function create()
    {
        $farmers = Farmer::all();
        $fertilizers = Fertilizer::all();

        return view('transactions.create', compact('farmers', 'fertilizers'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|exists:farmers,id',
            'fertilizer_id' => 'required|exists:fertilizers,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $fertilizer = Fertilizer::findOrFail($request->fertilizer_id);

        // Optional: Cek kuota subsidi jika diperlukan
        if ($fertilizer->is_subsidy && $request->quantity > $fertilizer->subsidy_quota) {
            return back()->withErrors(['quantity' => 'Kuota subsidi tidak mencukupi']);
        }

        $transaction = Transaction::create([
            'farmer_id' => $request->farmer_id,
            'fertilizer_id' => $request->fertilizer_id,
            'quantity' => $request->quantity,
            'total_price' => $fertilizer->price * $request->quantity,
            'created_by' => Auth::id(),
        ]);

        // Optional: kurangi stok pupuk
        $fertilizer->decrement('stock', $request->quantity);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    // Tampilkan detail transaksi
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    // Form edit transaksi
    public function edit(Transaction $transaction)
    {
        $farmers = Farmer::all();
        $fertilizers = Fertilizer::all();

        return view('transactions.edit', compact('transaction', 'farmers', 'fertilizers'));
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
