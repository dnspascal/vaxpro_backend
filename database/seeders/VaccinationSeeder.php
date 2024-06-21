<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Vaccination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json_vaccines = file_get_contents(database_path('json/vaccines.json'));
        $vaccines = json_decode($json_vaccines, true);



        $vaccinations = [];
        foreach ($vaccines as $value) {
            $vaccinations[] = [
                "name" => $value["name"],
                "abbrev" => $value["abbrev"],
                "frequency" => $value["frequency"],
                "first_dose_after" => $value["first_dose"],
                "second_dose_after" => $value["second_dose"] ?? null,
                "third_dose_after" => $value["third_dose"] ?? null,
                "fourth_dose_after" => $value["fourth_dose"] ?? null,
                "fifth_dose_after" => $value["fifth_dose"] ?? null,
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }


        Vaccination::insert($vaccinations);


        $json_promotion = file_get_contents(database_path('json/promotion.json'));
        $promotions = json_decode($json_promotion, true);

        $notifications = [];
        foreach ($promotions as $promotion) {
            $notifications[] = [
                'notification_type' => 'promotion',
                'message' => $promotion,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        Notification::insert($notifications);
    }
}
