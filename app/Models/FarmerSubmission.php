<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerSubmission extends Model
{
    use HasFactory;

    protected $table = 'farmer_submissions';
    
    protected $fillable = [
        'nik',
        'farmer_name',
        'address',
        'phone_number',
        'birth_date',
        'gender',
        'land_area',
        'land_location',
        'land_status',
        'main_commodity',
        'average_harvest',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'land_area' => 'decimal:2',
        'average_harvest' => 'decimal:2',
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
