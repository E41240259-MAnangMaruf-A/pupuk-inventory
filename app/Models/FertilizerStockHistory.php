<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizerStockHistory extends Model
{
    protected $guarded = ['id'];

    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class);
    }
}
