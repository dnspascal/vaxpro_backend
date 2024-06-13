<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentsGuardians;
use Illuminate\Support\Facades\Log;

class ParentController extends Controller
{
    public function parents(Request $request){
        $nida_no = $request->nidaNo;
       
        if (!empty($nida_no)) {
            
            $parents = ParentsGuardians::where('nida_id', 'LIKE', '%' . $nida_no . '%')->get();

           
            return response()->json($parents, 200);
        }


    }
}

