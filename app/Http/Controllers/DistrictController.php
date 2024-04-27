<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    
    public function showAll()
 {
     $districts = District::all();
     return response()->json($districts,200);
 }


 public function create(Request $request)
 {
     //
     $existing_district = District::where("district_name", $request->district_name)->where("region_id", $request->region_id)->first();
     if ($existing_district) {
     
         return response()->json(['message'=>'district already exists'],200);
     }
     $district = District::create($request->only(['district_name','region_id']));
    

     return response()->json(['message'=>'district created successfull'],200);
 }


 public function show(string $id)
 {
     $district = District::find($id);

     if($district){
         return response()->json($district,200);
     }
     return response()->json(['message'=>"district not found"],404);
 }

// show all district wards
 public function show_wards(string $id)
 {
     $district = District::find($id);

     if($district){
         return response()->json($district->ward,200);
     }
     return response()->json(['message'=>"district not found"],404);
 }

//  Show all facilities in the district 

public function show_facilities(string $id)
{
    $district = District::with('ward.facility')->find($id);

    if($district){

        $facilities = collect();

        foreach ($district->ward as $ward){
            $facilities = $facilities->merge($ward->facility);
        }

        return response()->json($facilities,200);
    }
    return response()->json(['message'=>"district not found"],404);
}

 public function update(string $id,Request $request)
 {
     $district = District::find($id);

     if($district){
         $district->district_name = $request->district_name;
         $district->save();
         return response()->json(['district'=>$district],200);
     }
     return response()->json(['message'=>"district not found"],404);
 }


 public function destroy(string $id)
 {
     $district = District::find($id);

     if($district){
         $district->delete();
         return response()->json(['district deleted'],204);
     }
     return response()->json(['message'=>"district not found"],404);
 
 }
}

