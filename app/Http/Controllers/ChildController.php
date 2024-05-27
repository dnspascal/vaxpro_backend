<?php

namespace App\Http\Controllers;

use App\Helpers\GeneratePasswordHelper;
use App\Helpers\GenerateRoleIdHelper;
use App\Models\Child;
use App\Models\ChildVaccination;
use App\Models\Vaccination;
use App\Models\ParentsGuardians;
use App\Models\ParentsGuardiansChild;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class ChildController extends Controller
{
    public function parentChildData(Request $request)
    {  
        $request->all();
        
        $ward_id = explode('-', $request->ward_id);
        $ward_id = end($ward_id);
        $ward_id = (int) $ward_id;

        $childExists = Child::where('card_no', $request->card_no)->exists();
        $parentExists = ParentsGuardians::where('nida_id', $request->nida_id)->exists();
        $parentData = ParentsGuardians::where('nida_id', $request->nida_id)->first();


        if (!$childExists && !$parentExists) {
            
          
            $child = Child::create([
                'card_no' => $request->card_no,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'surname' => $request->last_name,
                'date_of_birth' => $request->birth_date,
                'house_no' => $request->house_no,
                'ward_id' => $ward_id,
                'facility_id' => $request->facility_id,
                'modified_by' => $request->modified_by

            ]);


            $password = GeneratePasswordHelper::generatePassword();

            $user_role = Role::where('account_type', 'parent')->value('id');

            $user = User::create([
                'role_id' => $user_role,
                'uid' =>  GenerateRoleIdHelper::generateRoleId("parent", null, null ,$ward_id),
                'contacts' => $request->contact,
                'password' => Hash::make($password)
            ]);

            $parent = ParentsGuardians::create([
                'firstname' => $request->par_first_name,
                'middlename' => $request->par_middle_name,
                'lastname' => $request->par_last_name,
                'user_id' => $user->id,
                'nida_id' => $request->nida_id,


            ]);


            $parent->children()->attach([$child->card_no=>["relationship_with_child"=>$request->relation]]);

            return response()->json([
                'message' => 'Parent added successfully!',
                'password' => $password,
                'cardNo' => $child->card_no,
                'birthDate' => $child->date_of_birth
            ],200);

        } else if (!$childExists && $parentExists) {
            $child = Child::create([
                'card_no' => $request->card_no,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'surname' => $request->last_name,
                'date_of_birth' => $request->birth_date,
                'house_no' => $request->house_no,
                'ward_id' => $ward_id,
                'facility_id' => '123705-21',
                'modified_by' => '12345'
            ]);

             $parent = ParentsGuardiansChild::create([
                'parents_guardians_id' => $parentData->nida_id,
                'child_id' => $child->card_no,
                'relationship_with_child' => 'parent',
            ]);

             $parent->children()->attach([$child->card_no=>["relationship_with_child"=>$request->relation]]);
            return response()->json([
                'message' => 'Child added successfully!',
                'status' => 200,
            ]);
        }
        return response()->json([
            'message' => 'Child not added!',
            'status' => 400,
        ],400);
    }

    public function children(Request $request)
    {
        $card_no = $request->cardNo;

        if (!empty($card_no)) {
            $children = Child::where('card_no', 'LIKE', '%' . $card_no . '%')->with('parents_guardians')->get();

            return response()->json($children, 200);
        }
    }

    public function getChildData($id)
    {
        if ($id) {
            $child_data = Child::where('card_no', $id)
                ->with([
                    'parents_guardians' => function ($query) {
                        $query->withPivot('relationship_with_child');
                    },
                    'parents_guardians.user',
                    'ward.district.region'
                ])
                ->get();
            return response()->json($child_data, 200);
        }
    }

    public function getChildVaccines($id)
    {
        $vaccines = Vaccination::all();
        $child_vaccination = ChildVaccination::where('child_id', $id)->first();
        if ($child_vaccination) {
            $vaccineArray = array();
            foreach ($vaccines as $vaccine) {
                if ($vaccine->id != $child_vaccination->vaccination_id) {
                    $vaccineArray[] = $vaccine;
                }
            }
            return response()->json([
                'vaccines' => $vaccineArray,
                'status' => 200
            ]);
        } else {
            return response()->json([
                'vaccines' => $vaccines,
                'status' => 200
            ]);
        }
    }
}
