<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ParentsGuardiansChild;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function parentChildData(Request $request)
    {
        $child = Child::create([
            'card_no' => $request->card_no,
            'firstname' => $request->first_name,
            'middlename' => $request->middle_name,
            'lastname' => $request->last_name,
            'date_of_birth' => $request->birth_date,
            'house_no' => $request->house_no,
            'ward_id' => $request->ward_id
        ]);

        //parent info here......

       

        ParentsGuardiansChild::create([
            'parents_guardians_id' => '',
            'child_id'=>$child->id,
            'relationship_with_child'=>$request->relation,

        ]);


    }
}
