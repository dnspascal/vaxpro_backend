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
            // remove the relationship with child because it aint needed init
            $parents = ParentsGuardians::where('nida_id', 'LIKE', '%' . $nida_no . '%')
                           ->with(['children' => function ($query) {
                               $query->withPivot('relationship_with_child');
                           }, 'user'])
                           ->get();

           
            return response()->json($parents, 200);
        }


    }
}
