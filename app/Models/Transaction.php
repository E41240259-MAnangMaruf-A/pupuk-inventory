<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'transaction_date',
        'total_amount',
        'status'
        // tambahkan field lainnya
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
}