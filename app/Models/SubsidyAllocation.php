<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsidyAllocation extends Model
{
    use HasFactory;

    protected $table = 'subsidy_allocations';
    protected $guarded = ['id'];

    protected $casts = [
    'period_start' => 'date',
    'period_end' => 'date',
];

    // Relasi ke Farmer
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    // Relasi ke FertilizerType
    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class, 'fertilizer_type_id');
    }

}
