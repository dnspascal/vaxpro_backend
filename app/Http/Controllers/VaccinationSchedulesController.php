<?php

namespace App\Http\Controllers;

use App\Models\ChildVaccination;
use App\Models\ChildVaccinationSchedule;
use App\Models\HealthWorker;
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

        foreach ($vacccineData as $value) {
            $vaccineData = Vaccination::where('id', $value)->first();
            if ($vaccineData) {
                $dataArray[] = $vaccineData;
            }
        }

        $vaccinesNames = ["BCG", "MR2"];
        foreach ($dataArray as $key => $vaccineItem) {
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

            $dosesDates = array();
            $doses = ["first_dose", "second_dose", "third_dose", "fourth_dose", "fifth_dose"];
            foreach ($dates as $index => $value) {
                $dosesDates[] = [$doses[$index] => $value];
            }

            $result[$vaccineItem->name] = $dosesDates;
        }

        $dosesDates = array();
        $doses = ["first_dose", "second_dose", "third_dose", "fourth_dose", "fifth_dose"];
        foreach ($result as  $vaccinations) {
            foreach ($vaccinations as $index => $value) {
                $dosesDates[] = [$doses[$index] => $value];
            }
        }

        return response()->json([
            'vaccineSchedule' => $result,
            'vacItems' => $dataArray,
            'dosesArray' => $dosesDates,
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
        $vaccine = Vaccination::where('id', $request->vaccine_id)->first();
        $freq = 0;
        if ($vaccine) {
            $child_vaccination_id = ChildVaccination::where('vaccination_id', $vaccine->id)->value('id');
            if ($request->index == 0) {
                $freq = 1;
                $next_date = Carbon::createFromFormat('Y-m-d', $request->selected_date)
                    ->addDays($vaccine->second_dose_after)
                    ->format('Y-m-d');
            } elseif ($request->index == 1) {
                $freq = 2;
                $next_date = Carbon::createFromFormat('Y-m-d', $request->selected_date)
                    ->addDays($vaccine->third_dose_after)
                    ->format('Y-m-d');
            } elseif ($request->index == 2) {
                $freq = 3;
                $next_date = Carbon::createFromFormat('Y-m-d', $request->selected_date)
                    ->addDays($vaccine->fourth_dose_after)
                    ->format('Y-m-d');
            } elseif ($request->index == 3) {
                $freq = 4;
                $next_date = Carbon::createFromFormat('Y-m-d', $request->selected_date)
                    ->addDays($vaccine->fifth_dose_after)
                    ->format('Y-m-d');
            } elseif ($request->index == 4) {
                $freq = 5;
                $next_date = Carbon::createFromFormat('Y-m-d', $request->selected_date)
                    ->addDays($vaccine->sixth_dose_after)
                    ->format('Y-m-d');
            }

            $health_worker_id = HealthWorker::where('user_id', $request->health_worker_id)->value('staff_id');


            $child_schedule = ChildVaccinationSchedule::create([
                'child_vaccination_id' => $child_vaccination_id,
                'child_id' => $request->child_id,
                'health_worker_id' => $health_worker_id,
                'facility_id' => $request->facility_id,
                'frequency' => $freq,
                'vaccination_date' => $request->selected_date,
                'next_vaccination_date' => $next_date,
                'status' => true,

            ]);
        }
    }

    public function getSavedSchedules($child_id){
        $child_schedules = ChildVaccinationSchedule::where('child_id', $child_id)->get();
        if($child_schedules){
            foreach( $child_schedules as $child_schedule ){
                $child_schedule['vaccine_id'] = ChildVaccination::where('id', $child_schedule['child_vaccination_id'])->value('vaccination_id');
            }
            return $child_schedules;
        }
    }
}
