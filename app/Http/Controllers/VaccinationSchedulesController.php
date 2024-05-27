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

        foreach ($vacccineData as $value) {
            $vaccineData = Vaccination::where('id', $value)->first();
            if ($vaccineData) {
                $dataArray[] = $vaccineData;
            }
        }

        $vaccinesNames = ["BCG","MR2"];
        foreach ($dataArray as $key=>$vaccineItem) {
            $dates = array(); // Clear $dates array for each vaccine

            $dates[] =Carbon::createFromFormat('Y-m-d', $request->date)
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
            $doses = ["first_dose","second_dose","third_dose","fourth_dose","fifth_dose"];
            foreach($dates as $index=>$value){
                $dosesDates[] = [$doses[$index] =>$value];
            }

            $result[$vaccineItem->name] = $dosesDates;
        }
        
        $dosesDates = array();
        $doses = ["first_dose","second_dose","third_dose","fourth_dose","fifth_dose"];
       foreach ($result as  $vaccinations) {
        foreach($vaccinations as $index=>$value){
            $dosesDates[] = [$doses[$index] =>$value];
        }
       }
       
        return response()->json([
            'vaccineSchedule' => $result,
            'vacItems' => $dataArray,
            'dosesArray'=>$dosesDates,
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
