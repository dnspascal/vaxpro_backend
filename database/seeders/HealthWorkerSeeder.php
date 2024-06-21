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

        $json_co_workers = file_get_contents(database_path('json/community_workers.json'));
        $workers = json_decode($json_co_workers, true);

        $workers_data = [];
        foreach ($workers as $worker) {
            $workers_data[] = [
                'role_id' => $worker["role"],
                'uid' => $worker["uid"],
                'password' => $worker["password"],
                'ward_id' =>$worker['ward_id'],
                'contacts' => $worker['contacts'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // dd($workers);
        User::insert($workers_data);
    }
}
