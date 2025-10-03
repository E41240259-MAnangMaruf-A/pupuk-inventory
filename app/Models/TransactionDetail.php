<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
<<<<<<< HEAD

    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class, 'fertilizer_type_id');
=======
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
        return $this->belongsTo(FertilizerType::class);
>>>>>>> eb65cedaa66c1571fbb3a37ff7e15b84c699fef8
    }
}


