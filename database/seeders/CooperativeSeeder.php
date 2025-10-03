<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CooperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cooperatives')->insert([
            'cooperative_name' => 'Koperasi Tani Sejahtera',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'chairman_name' => 'Budi Santoso',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
