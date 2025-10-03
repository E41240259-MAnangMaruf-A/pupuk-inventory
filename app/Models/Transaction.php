<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date', // atau 'datetime' jika ada waktu
    ];

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
