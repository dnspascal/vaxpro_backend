<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    
    public function showAll(Request $request)
 {
    $searchQuery = $request->searchQuery;
    

    if(!empty($searchQuery)){
        
        $wards = Ward::where('ward_name','LIKE','%'.$searchQuery.'%')->with('district')->get();
        return response()->json($wards,200);
    }
 }


 public function create(Request $request)
 {
     //
     $existing_ward = Ward::where("ward_name", $request->ward_name)->where("district_id", $request->district_id)->first();
     if ($existing_ward) {
     
         return response()->json(['message'=>'ward already exists'],400);
     }
     $ward = Ward::create($request->only(['ward_name','district_id']));
    

     return response()->json(['message'=>'ward created successfull'],200);
 }


 public function show(string $id)
 {
     $ward = Ward::find($id);

     if($ward){
         return response()->json($ward,200);
     }
     return response()->json(['message'=>"ward not found"],404);
 }


 public function update(string $id,Request $request)
 {
     $ward = Ward::find($id);

     if($ward){
         $ward->ward_name = $request->ward_name;
         $ward->save();
         return response()->json(['ward'=>$ward],200);
     }
     return response()->json(['message'=>"ward not found"],404);
 }


 public function destroy(string $id)
 {
     $ward = Ward::find($id);

     if($ward){
         $ward->delete();
         return response()->json(['ward deleted'],204);
     }
     return response()->json(['message'=>"ward not found"],404);
 
 }
}
