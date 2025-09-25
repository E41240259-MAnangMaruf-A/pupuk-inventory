<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FarmerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // pakai lokal Indonesia
        $genders = ['L', 'P'];
        $landStatuses = ['milik', 'sewa', 'garap'];
        $statuses = ['active', 'inactive'];
        $commodities = ['Padi', 'Jagung', 'Kedelai', 'Cabai', 'Bawang Merah', 'Tebu'];

        for ($i = 1; $i <= 4; $i++) {
            DB::table('farmers')->insert([
                'nik'             => $faker->unique()->nik(),
                'farmer_name'     => $faker->name(),
                'address'         => $faker->address(),
                'phone_number'    => $faker->phoneNumber(),
                'birth_date'      => $faker->date('Y-m-d', '2000-01-01'),
                'gender'          => $faker->randomElement($genders),
                'land_area'       => $faker->randomFloat(2, 0.5, 10), // 0.5 - 10 hektar
                'land_location'   => $faker->city . ', ' . $faker->citySuffix,
                'land_status'     => $faker->randomElement($landStatuses),
                'main_commodity'  => $faker->randomElement($commodities),
                'average_harvest' => $faker->randomFloat(2, 1, 20), // ton
                'status'          => $faker->randomElement($statuses),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
