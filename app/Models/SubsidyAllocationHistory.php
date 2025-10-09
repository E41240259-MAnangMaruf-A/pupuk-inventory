<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsidyAllocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'subsidy_allocation_id',
        'fertilizer_type_id',
        'transaction_id',
        'quantity',
        'type',
        'note',
    ];

    /**
     * Relasi ke alokasi subsidi utama (per petani & pupuk).
     */
    public function allocation()
    {
        return $this->belongsTo(SubsidyAllocation::class, 'subsidy_allocation_id');
    }

    /**
     * Relasi ke jenis pupuk.
     */
    public function fertilizerType()
    {
        return $this->belongsTo(FertilizerType::class);
    }

    /**
     * Relasi ke transaksi (jika histori berasal dari transaksi tertentu).
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Helper untuk format kuantitas (+/-) dengan label.
     */
    public function getFormattedQuantityAttribute(): string
    {
        $sign = $this->type === 'use' ? '-' : '+';
        return "{$sign}{$this->quantity}";
    }

    /**
     * Helper untuk menampilkan tipe penggunaan dalam bahasa manusia.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'use' ? 'Pemakaian Subsidi' : 'Pengembalian Subsidi';
    }
}
