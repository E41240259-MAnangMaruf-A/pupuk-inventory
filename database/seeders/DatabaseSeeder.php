<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([FarmerSeeder::class, FarmerSubmissionSeeder::class, SubsidyAllocationSeeder::class, FertilizerSeeder::class, CooperativeSeeder::class]);

        // Admin Desa
        User::factory()->create([
            'name' => 'Admin Desa',
            'email' => 'admin-desa@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_desa',
        ]);

        // Admin Koperasi
        User::factory()->create([
            'name' => 'Admin Koperasi',
            'email' => 'admin-koperasi@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin_koperasi',
        ]);

        // Kasir Koperasi
        User::factory()->create([
            'name' => 'Kasir Koperasi',
            'email' => 'kasir-koperasi@example.com',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir_koperasi',
        ]);

        // Kepala Desa
        User::factory()->create([
            'name' => 'Kepala Desa',
            'email' => 'kepala-desa@example.com',
            'password' => Hash::make('kepala123'),
            'role' => 'kepala_desa',
        ]);
    }
}
