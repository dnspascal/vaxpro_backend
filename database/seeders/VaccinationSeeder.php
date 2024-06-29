<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\ChildVaccination;
use App\Models\ChildVaccinationSchedule;
use App\Models\Notification;
use App\Models\User;
use App\Models\Vaccination;
use Carbon\Carbon;
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

        // $children = Child::all();
        // $vaccine_count = Vaccination::all()->count();

        // foreach ($children as $child ) {
        //     for ($id = 1; $id <= $vaccine_count; $id++) {
        //         ChildVaccination::create([
        //             'child_id' => $child->card_no,
        //             'vaccination_id' => $id,
        //             'is_active' => true,
        //         ]);
        //     }
        // }


        $vaccinations = Vaccination::all();
        $children = Child::all();
        $health_worker = User::where('role_id',11)->first();
        
        
        foreach ($children as $child) {
            foreach ($vaccinations as $vaccination) {
                $childVaccination = ChildVaccination::create([
                    'child_id' => $child->card_no,
                    'vaccination_id' => $vaccination->id,
                    'is_active' => 1
                ]);

                $frequency = $vaccination->frequency;
                $intervals = [
                    $vaccination->first_dose_after,
                    $vaccination->second_dose_after ?? null,
                    $vaccination->third_dose_after ?? null,
                    $vaccination->fourth_dose_after ?? null,
                    $vaccination->fifth_dose_after ?? null
                ];

                for ($i = 0; $i < $frequency; $i++) {
                    $doseInterval = $intervals[$i];
                    $vaccinationDate = Carbon::now()->addDays($doseInterval);
                    $nextVaccinationDate = $i < $frequency - 1 ? Carbon::now()->addDays($intervals[$i + 1]) : null;
                    
                    ChildVaccinationSchedule::create([
                        'child_vaccination_id' => $childVaccination->id,
                        'child_id' => $child->card_no,
                        'health_worker_id' => $health_worker->health_workers->first()->staff_id, // Assuming a default health worker for simplicity
                        'facility_id' => $health_worker->facilities->facility_reg_no, // Assuming a default facility for simplicity
                        'frequency' => $i + 1,
                        'vaccination_date' => $vaccinationDate,
                        'next_vaccination_date' => $nextVaccinationDate,
                        'status' => ($i + 1 == $frequency) ? 0 : 1
                    ]);
                }
            }
        }

        

        

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
