<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FertilizerType extends Model
{
    use HasFactory;

    protected $table = 'fertilizer_types';

    protected $fillable = [
        'fertilizer_name',
        'unit',
        'subsidized_price',
        'retail_price',
        'description',
        'is_subsidized',
    ];

    public function stock()
    {
        return $this->hasOne(FertilizerStock::class, 'fertilizer_type_id');
    }

    public function allocations()
    {
        return $this->hasMany(SubsidyAllocation::class, 'fertilizer_type_id');
    }
}
