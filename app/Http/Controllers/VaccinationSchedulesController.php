<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VaccinationSchedulesController extends Controller
{
    public function vaccine(Request $request)
    {
        $dataArray = array();
        $data = $request->all();

        foreach ($data as $key => $value) {
            $vaccineData = Vaccination::where('id', $value)->first();
            if ($vaccineData) {
                $dataArray[] = $vaccineData;
            }
        }

        $result = array();

        foreach ($dataArray as $vaccineItem) {
            $dateArray = array();

            $first_date = Carbon::createFromFormat('m/d/Y', $request->date)
                ->addDays($vaccineItem->first_dose_after)
                ->format('m/d/Y');
            $second_date = Carbon::createFromFormat('m/d/Y', $first_date)
                ->addDays($vaccineItem->second_dose_after)->format('m/d/Y');
            $third_date = Carbon::createFromFormat('m/d/Y', $second_date)
                ->addDays($vaccineItem->third_dose_after)->format('m/d/Y');
            $fourth_date = Carbon::createFromFormat('m/d/Y', $third_date)
                ->addDays($vaccineItem->fourth_dose_after)->format('m/d/Y');
            $fifth_date = Carbon::createFromFormat('m/d/Y', $fourth_date)
                ->addDays($vaccineItem->fifth_dose_after)->format('m/d/Y');

            $dateArray[] = [$first_date, $second_date, $third_date, $fourth_date, $fifth_date];

            $result[$vaccineItem->id] = $dateArray;
        }

        return $result;
    }
}
