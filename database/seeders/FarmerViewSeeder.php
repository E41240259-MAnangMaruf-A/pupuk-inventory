<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farmer;
use App\Models\FarmerSubmission;
use Carbon\Carbon;

class FarmerViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Jalankan: php artisan db:seed --class=FarmerViewSeeder
     */
    public function run()
    {
        // Tambahan data petani tervalidasi
        $validatedFarmers = [
            [
                'nik' => '3510120101900001',
                'farmer_name' => 'Budi Santoso',
                'address' => 'Jl. Raya Desa No. 10, Kec. Sukowono',
                'phone_number' => '081234567890',
                'birth_date' => '1990-01-01',
                'gender' => 'L',
                'land_area' => 2.5,
                'land_location' => 'Desa Sukowono',
                'land_status' => 'milik',
                'main_commodity' => 'Padi',
                'average_harvest' => 5.5,
                'status' => 'active',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(30),
            ],
            [
                'nik' => '3510120202910002',
                'farmer_name' => 'Siti Aminah',
                'address' => 'Dsn. Krajan RT 02 RW 01, Kec. Tanggul',
                'phone_number' => '082345678901',
                'birth_date' => '1991-02-02',
                'gender' => 'P',
                'land_area' => 1.8,
                'land_location' => 'Desa Tanggul Wetan',
                'land_status' => 'sewa',
                'main_commodity' => 'Jagung',
                'average_harvest' => 3.2,
                'status' => 'active',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(25),
            ],
        ];

        foreach ($validatedFarmers as $farmer) {
            Farmer::create($farmer);
        }

        // Tambahan data pengajuan yang ditolak
        $rejectedSubmissions = [
            [
                'nik' => '3510120303920003',
                'farmer_name' => 'Ahmad Fauzi',
                'address' => 'Jl. Melati No. 15, Kec. Kaliwates',
                'phone_number' => '083456789012',
                'birth_date' => '1992-03-03',
                'gender' => 'L',
                'land_area' => 0.5,
                'land_location' => 'Desa Kaliwates',
                'land_status' => 'garap',
                'main_commodity' => 'Cabai',
                'average_harvest' => 1.0,
                'status' => 'rejected',
                'rejection_reason' => 'Luas lahan tidak memenuhi persyaratan minimum (minimal 1 Ha)',
                'submitted_at' => Carbon::now()->subDays(20),
                'validated_at' => Carbon::now()->subDays(18),
                'validated_by' => 1, // Admin Desa
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(18),
            ],
            [
                'nik' => '3510120404930004',
                'farmer_name' => 'Dewi Lestari',
                'address' => 'Dsn. Makmur RT 03 RW 02, Kec. Sumbersari',
                'phone_number' => '084567890123',
                'birth_date' => '1993-04-04',
                'gender' => 'P',
                'land_area' => 2.0,
                'land_location' => 'Desa Sumbersari',
                'land_status' => 'milik',
                'main_commodity' => 'Kedelai',
                'average_harvest' => 2.5,
                'status' => 'rejected',
                'rejection_reason' => 'Dokumen kepemilikan lahan tidak lengkap',
                'submitted_at' => Carbon::now()->subDays(15),
                'validated_at' => Carbon::now()->subDays(12),
                'validated_by' => 1,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'nik' => '3510120505940005',
                'farmer_name' => 'Eko Prasetyo',
                'address' => 'Jl. Kenanga No. 20, Kec. Ajung',
                'phone_number' => '085678901234',
                'birth_date' => '1994-05-05',
                'gender' => 'L',
                'land_area' => 3.0,
                'land_location' => 'Desa Ajung',
                'land_status' => 'sewa',
                'main_commodity' => 'Tebu',
                'average_harvest' => 8.0,
                'status' => 'rejected',
                'rejection_reason' => 'NIK tidak sesuai dengan KTP yang dilampirkan',
                'submitted_at' => Carbon::now()->subDays(10),
                'validated_at' => Carbon::now()->subDays(8),
                'validated_by' => 1,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
        ];

        foreach ($rejectedSubmissions as $submission) {
            FarmerSubmission::create($submission);
        }

        $this->command->info('✓ Seeder FarmerView berhasil dijalankan!');
        $this->command->info('  - ' . count($validatedFarmers) . ' petani tervalidasi ditambahkan');
        $this->command->info('  - ' . count($rejectedSubmissions) . ' pengajuan ditolak ditambahkan');
    }
}