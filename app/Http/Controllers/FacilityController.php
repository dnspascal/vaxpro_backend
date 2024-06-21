<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
   
       public function create(Request $request)
    {
        $request->validate([
           "contacts" => [
                "required",
                "min:13",
                "max:13",
                "regex:/^\+255/",
            ],
        ]);
        
       
        $facility =  Facility::where('facility_reg_no',$request->facility_reg_no)->first();
        if ($facility) {
            return response()->json(["message"=>"Hospital facility already exists",$facility],400);
        }
        
        $facilityRes = Facility::create($request->only(['facility_reg_no','facility_name','ward_id','contacts']));

        return response()->json(['message'=> 'hospital facility added successfully','facility'=>$facilityRes],201);
    }

    public function showAll()
    {
        //
        $facilities = Facility::all();

        return response()->json($facilities,200);
    }

   
    public function show(string $id)
    {
        $facility = Facility::where('facility_reg_no',$id)->first();

        if($facility){
            return response()->json([$facility->ward->district],200);
        }
        return response()->json(['message'=> 'Hospital not found'],404);
    }

   
    public function update(Request $request, string $id)
    {
        $facility = Facility::where('facility_reg_no',$id)->first();

        if($facility){
            $facility->facility_reg_no = $request->facility_reg_no;
            $facility->facility_name = $request->facility_name;
            $facility->contacts = $request->contacts;
            $facility->ward = $request->ward;
            $facility->save();
            return response()->json(["message"=>"Hospital facility updated succesfully","facility"=>$facility],200);
        }
        return response()->json(['message'=> 'Hospital not found'],404);
    }

    
    public function destroy(string $id)
    {
        $facility = Facility::where('facility_reg_no',$id)->first();

        if($facility){
            $facility->delete();
            return response()->json("Facility deleted permanently",204);
        }
        return response()->json(['message'=> 'Hospital not found'],404);
    }
}
