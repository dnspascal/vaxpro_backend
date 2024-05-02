<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class RegionController extends Controller
{
    
       public function showAll()
    {
        $regions = Region::all();
        return response()->json($regions,200);
    }

  
    public function create(Request $request)
    {
        //
        $existing_region = Region::where("region_name", $request->region_name)->first();
        if ($existing_region) {
        
            return response()->json(['message'=>'region already exists'],200);
        }
        $region = Region::create($request->only(['region_name']));
       

        return response()->json(['message'=>'region created successfull'],200);
    }

   
    public function show(string $id)
    {
        $region = Region::find($id);

        if($region){
            return response()->json(['region'=>$region],200);
        }
        return response()->json(['message'=>"Region not found"],404);
    }

   
    public function update(string $id,Request $request)
    {
        $region = Region::find($id);

        if($region){
            $region->region_name = $request->region_name;
            $region->save();
            return response()->json(['region'=>$region],200);
        }
        return response()->json(['message'=>"Region not found"],404);
    }

   
    public function destroy(string $id)
    {
        $region = Region::find($id);

        if($region){
            $region->delete();
            return response()->json(['region deleted'],204);
        }
        return response()->json(['message'=>"Region not found"],404);
    
    }
}
