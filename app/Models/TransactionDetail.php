<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';

    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class, 'fertilizer_type_id');
    }
}


