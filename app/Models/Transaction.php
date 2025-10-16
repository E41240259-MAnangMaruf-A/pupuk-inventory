<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'farmer_id',
        'cooperative_id',
        'user_id',
        'transaction_date',
        'total_amount',
        'total_payment',
        'total_change',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * Relasi ke TransactionDetail
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    /**
     * Relasi ke Customer (jika ada)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relasi ke petani
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    // Relasi ke koperasi
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    // Relasi ke user yang membuat transaksi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Optional: total quantity atau total amount helper
    public function getTotalAmountAttribute()
    {
        return $this->details()->sum('subtotal');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->details()->sum('quantity');
    }
}
