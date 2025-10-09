<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke FertilizerType
     */
    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class, 'fertilizer_type_id');
    }
}


