<?php

namespace App\Http\Controllers;

use App\Models\ChildVaccination;
use App\Models\ChildVaccinationSchedule;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VaccinationSchedulesController extends Controller
{
    public function vaccine(Request $request)
    {
        $dataArray = array();
        $result = array();
    
        $vacccineData =  $request->vaccines;
    
        foreach ($vacccineData as $key => $value) {
            $vaccineData = Vaccination::where('id', $value)->first();
            if ($vaccineData) {
                $dataArray[] = $vaccineData;
            }
        }
    
        foreach ($dataArray as $vaccineItem) {
            $dates = array(); // Clear $dates array for each vaccine
            
            $dates[] = Carbon::createFromFormat('Y-m-d', $request->date)
                ->addDays($vaccineItem->first_dose_after)
                ->format('Y-m-d');
    
            $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                ->addDays($vaccineItem->second_dose_after)
                ->format('Y-m-d');
    
            if ($vaccineItem->frequency >= 3) {
                $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                    ->addDays($vaccineItem->third_dose_after)
                    ->format('Y-m-d');
            }
    
            if ($vaccineItem->frequency >= 4) {
                $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                    ->addDays($vaccineItem->fourth_dose_after)
                    ->format('Y-m-d');
            }
    
            if ($vaccineItem->frequency >= 5) {
                $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                    ->addDays($vaccineItem->fifth_dose_after)
                    ->format('Y-m-d');
            }
    
            $result[$vaccineItem->id] = $dates;
        }
    
        foreach ($dataArray as $vac) {
    
            $child_vaccination = ChildVaccination::create([
                'child_id' => $request->card_no,
                'vaccination_id' => $vac->id,
                'is_active' => true
            ]);
    
            $currentVaccineDates = $result[$vac->id];
    
            ChildVaccinationSchedule::create([
                'child_vaccination_id' => $child_vaccination->id,
                'health_worker_id' => '12345',
                'facility_id' => '123705-21',
                'frequency' => $vac->frequency,
                'child_id' =>'12345622',
                'vaccination_date' => '2024-05-08',
                'next_vaccination_date' => $currentVaccineDates[0],
                'status' => false,
            ]);
        }
    
        return response()->json([
            'vaccineSchedule' => $result,
            'vacItems' => $dataArray,
            'status' => 200
        ]);
    }
    

    public function getVacSchedules($id)
    {
        $child_vaccines = ChildVaccination::where('child_id', $id)->get();
        if ($child_vaccines != null) {
            $vacSchedDetails = [];
            $vacDetails = [];
            foreach ($child_vaccines as $vaccine) {
                $schedules = ChildVaccinationSchedule::where('child_vaccination_id', $vaccine->id)->where('status', false)->first();
                $schedules['vacName'] = Vaccination::where('id', $vaccine->vaccination_id)->value('name');
                $vacSchedDetails[] = $schedules;
            }

            return response()->json([
                'vacScheds' => $vacSchedDetails,
                'status' => 200
            ]);
        }
    }

    public function updateChildVacSchedule(Request $request)
    {
        $dates = array();
        $vaccineId = ChildVaccination::where('id', $request->vac_id)->value('vaccination_id');
        $vaccine = Vaccination::where('id', $vaccineId)->first();
        if ($vaccine) {
            $dates[] = Carbon::createFromFormat('Y-m-d', $request->curr_date)
            ->addDays($vaccine->first_dose_after)
            ->format('Y-m-d');
            $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                ->addDays($vaccine->second_dose_after)
                ->format('Y-m-d');

            $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                ->addDays($vaccine->third_dose_after)
                ->format('Y-m-d');

            $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                ->addDays($vaccine->fourth_dose_after)
                ->format('Y-m-d');

            $dates[] = Carbon::createFromFormat('Y-m-d', end($dates))
                ->addDays($vaccine->fifth_dose_after)
                ->format('Y-m-d');
        }


        $child_vac_schedule = ChildVaccinationSchedule::where('child_id', $request->child_id)
            ->where('child_vaccination_id', $request->vac_id)
            ->first();
        if ($child_vac_schedule) {

            $child_vac_schedule->vaccination_date = $child_vac_schedule->next_vaccination_date;

            foreach ($dates as $date) {
                $dateCarbon = Carbon::createFromFormat('Y-m-d', $date);
                if ($dateCarbon > $child_vac_schedule->next_vaccination_date) {
                    $child_vac_schedule->next_vaccination_date = $dateCarbon;
                    break;
                }
            }
            

            $child_vac_schedule->save();
        }

        return response()->json([
            'Dates' => $dates,
            'status' => 200
        ]);
    }
}
