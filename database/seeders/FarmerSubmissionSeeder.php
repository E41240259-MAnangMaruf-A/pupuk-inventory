<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FarmerSubmissionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $genders = ['L', 'P'];
        $landStatuses = ['milik', 'sewa', 'garap'];
        $statuses = ['pending', 'approved', 'rejected'];
        $commodities = ['Padi', 'Jagung', 'Kedelai', 'Cabai', 'Bawang Merah', 'Tebu'];

        for ($i = 1; $i <= 4; $i++) {
            $status = $faker->randomElement($statuses);

            DB::table('farmer_submissions')->insert([
                'nik'              => $faker->unique()->nik(),
                'farmer_name'      => $faker->name(),
                'address'          => $faker->address(),
                'phone_number'     => $faker->phoneNumber(),
                'birth_date'       => $faker->date('Y-m-d', '2000-01-01'),
                'gender'           => $faker->randomElement($genders),
                'land_area'        => $faker->randomFloat(2, 0.5, 10),
                'land_location'    => $faker->city . ', ' . $faker->citySuffix,
                'land_status'      => $faker->randomElement($landStatuses),
                'main_commodity'   => $faker->randomElement($commodities),
                'average_harvest'  => $faker->randomFloat(2, 1, 20),
                'status'           => $status,
                'rejection_reason' => $status === 'rejected' ? $faker->sentence(6) : null,
                'submitted_at'     => $faker->dateTimeBetween('-2 months', 'now'),
                'validated_at'     => in_array($status, ['approved', 'rejected']) ? $faker->dateTimeBetween('-1 months', 'now') : null,
                // 'validated_by'     => in_array($status, ['approved', 'rejected']) ? $faker->numberBetween(1, 5) : null, // asumsi ada 5 user validator
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
