<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Farmer;
use App\Models\FertilizerType;

class SubsidyAllocationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $farmers = Farmer::pluck('id')->toArray();
        $fertilizers = FertilizerType::pluck('id')->toArray();

        if (empty($farmers) || empty($fertilizers)) {
            $this->command->warn('⚠️ Seeder skipped: farmers or fertilizer_types table is empty.');
            return;
        }

        for ($i = 1; $i <= 30; $i++) {
            $farmerId = $faker->randomElement($farmers);
            $fertId   = $faker->randomElement($fertilizers);
            $maxQuota = $faker->numberBetween(50, 500);
            $used     = $faker->numberBetween(0, $maxQuota);
            $remaining = $maxQuota - $used;

            $start = $faker->dateTimeBetween('-6 months', 'now');
            $end   = (clone $start)->modify('+6 months');

            DB::table('subsidy_allocations')->insert([
                'farmer_id'          => $farmerId,
                'fertilizer_type_id' => $fertId,
                'maximum_quota'      => $maxQuota,
                'used_quota'         => $used,
                'remaining_quota'    => $remaining,
                'period_start'       => $start->format('Y-m-d'),
                'period_end'         => $end->format('Y-m-d'),
                'status'             => $faker->randomElement(['active', 'inactive']),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}
