<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildVaccination;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function createVaccine(Request $request)
    {

        $vaccine = Vaccination::create([
            'name' => $request->vaccine_name,
            'frequency' => $request->frequency,
            'vaccine_against' => $request->vaccine_against,
            'first_dose_after' => $request->first_dose_after,
            'second_dose_after' => $request->second_dose_after,
            'third_dose_after' => $request->third_dose_after,
            'fourth_dose_after' => $request->fourth_dose_after,
            'fifth_dose_after' => $request->fifth_dose_after,
            'administered_via' => $request->admin_via,
            'side_effects' => $request->side_effects
        ]);

        return response()->json([
            'vaccine' => $vaccine,
            'status' => 200
        ]);
    }

    public function getVaccines()
    {
        $vaccines = Vaccination::all();
        return response()->json([
            'vaccines' => $vaccines,
            'status' => 200
        ]);
    }

    public function getChildVaccines($id){
        $vaccines = Vaccination::all();
        $child = Child::where('card_no', $id)->first();
        if($child->gender == 'female'){
            return response()->json([
                'vaccines' => $vaccines,
                'status' => 200
            ]);
        }else{
            $vaccines = Vaccination::where('abbrev','!=','HPV')->get();
            return response()->json([
                'vaccines' => $vaccines,
                'status' => 200
            ]);
        }
    }

    public function getVaccine($id)
    {
        $vaccine = Vaccination::where('id', $id)->first();
        if ($vaccine) {
            $vaccine->first_dose_after = (string) $vaccine->first_dose_after;
            return response()->json([
                'vaccine' => $vaccine,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'vaccine' => 'No Product Found!',
                'status' => 400
            ]);
        }
    }

    public function updateVaccine(Request $request, $id)
    {
        $vaccine = Vaccination::find($id);
        if ($vaccine) {
            $vaccine->name = $request->name;
            $vaccine->frequency = $request->frequency;
            $vaccine->vaccine_against = $request->vaccine_against;
            $vaccine->first_dose_after = $request->first_dose_after;
            $vaccine->second_dose_after = $request->second_dose_after;
            $vaccine->third_dose_after = $request->third_dose_after;
            $vaccine->fourth_dose_after = $request->fourth_dose_after;
            $vaccine->fifth_dose_after = $request->fifth_dose_after;
            $vaccine->administered_via = $request->administered_via;
            $vaccine->side_effects = $request->side_effects;
            $vaccine->save();

            return response()->json([
                'status' => 200,
                'message' => 'Vaccine Updated Successfully',
                'vaccine' => $vaccine
            ]);
        }
    }

    public function deleteVaccine($id)
    {
        $vaccine = Vaccination::find($id);
        if ($vaccine) {
            $vaccine->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Vaccine Deleted Successfully',
            ]);
        }
    }
    public function fetchVaccineIds(Request $request)
    {
        $vaccines = Vaccination::all();
        $vaccine_id_array = array();
        foreach ($vaccines as $vaccine) {
            $check_vaccine = ChildVaccination::where('vaccination_id', $vaccine->id)->first();
            if (!$check_vaccine) {
                ChildVaccination::create([
                    'child_id' => $request->child_id,
                    'vaccination_id' =>  $vaccine->id,
                    'is_active' => true,
                ]);
            }

            $vaccine_id_array[] =  $vaccine->id;
        }
        return response()->json([
            'status' => 200,
            'vaccineIds' => $vaccine_id_array,
        ]);
    }

   
}
