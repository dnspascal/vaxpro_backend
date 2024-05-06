<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentsGuardians;

class ParentController extends Controller
{
    public function parents(Request $request){
        $nida_no = $request->nidaNo;

        if (!empty($nida_no)) {

            $parents = ParentsGuardians::where('nida_id', 'LIKE', '%' . $nida_no . '%')
                           ->with(['child' => function ($query) {
                               $query->withPivot('relationship_with_child');
                           }, 'user'])
                           ->get();

           
            return response()->json($parents, 200);
        }


    }
}
