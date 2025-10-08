<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FertilizerStock extends Model
{
    use HasFactory;

    protected $table = 'fertilizer_stocks';
    protected $guarded = [];

    /**
     * Relasi ke tabel fertilizer_types
     * Setiap stok pupuk pasti memiliki satu jenis pupuk tertentu.
     */
    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class, 'fertilizer_type_id');
    }
}
