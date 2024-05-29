<?php

namespace Database\Seeders;

use App\Models\HealthWorker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class HealthWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'uid' => "5000-1-1",
            "role_id" => 11,
            'contacts' => '+255745884009',
            'password' => '12345',
            "facility_id" => "123705-4",
        ]);

        HealthWorker::create([
            "staff_id" => "987534235",
            "first_name" => "Grace",
            "last_name" => "Hill",
            "user_id"=> $user->id

        ]);
    }
}
