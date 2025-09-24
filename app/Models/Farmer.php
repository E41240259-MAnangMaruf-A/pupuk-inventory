<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $table = 'farmers';

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
    ];

    protected $casts = [
        'birth_date' => 'date',
        'land_area' => 'float',
        'average_harvest' => 'float',
    ];
}
