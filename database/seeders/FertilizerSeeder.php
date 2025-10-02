<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FertilizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fertilizer_types')->insert([
            [
                'fertilizer_name'   => 'Urea',
                'fertilizer_code'   => 'UREA01',
                'unit'              => 'kg',
                'subsidized_price'  => 2000.00,
                'retail_price'      => 2500.00,
                'description'       => 'Pupuk urea untuk tanaman pangan',
                'is_subsidized'     => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'fertilizer_name'   => 'ZA',
                'fertilizer_code'   => 'ZA01',
                'unit'              => 'kg',
                'subsidized_price'  => 1800.00,
                'retail_price'      => 2200.00,
                'description'       => 'Pupuk ZA untuk padi dan palawija',
                'is_subsidized'     => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'fertilizer_name'   => 'NPK',
                'fertilizer_code'   => 'NPK01',
                'unit'              => 'kg',
                'subsidized_price'  => 2500.00,
                'retail_price'      => 3000.00,
                'description'       => 'Pupuk majemuk NPK',
                'is_subsidized'     => false,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
