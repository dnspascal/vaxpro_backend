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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ChildController extends Controller
{
    public function parentChildData(Request $request)
    {
        $ward_id = $request->ward;

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
                'modified_by' => $request->modified_by,
                'gender' => $request->gender,

            ]);


            $password = GeneratePasswordHelper::generatePassword();

            $user_role = Role::where('account_type', 'parent')->value('id');

            $user = User::create([
                'role_id' => $user_role,
                'uid' =>  GenerateRoleIdHelper::generateRoleId("parent", null, null, $ward_id),
                'contacts' => $request->contact,
                'password' => Hash::make($password),
                'ward_id' => $ward_id,
            ]);

            $parent = ParentsGuardians::create([
                'firstname' => $request->par_first_name,
                'middlename' => $request->par_middle_name,
                'lastname' => $request->par_last_name,
                'user_id' => $user->id,
                'nida_id' => $request->nida_id,


            ]);


            $parent->children()->attach([$child->card_no => ["relationship_with_child" => $request->relation]]);

            $vaccine_count = Vaccination::all()->count();

            for ($id = 1; $id <= $vaccine_count; $id++) {
                ChildVaccination::create([
                    'child_id' => $child->card_no,
                    'vaccination_id' => $id,
                    'is_active' => true,
                ]);
            }

            return response()->json([
                'message' => 'Parent added successfully!',
                'password' => $password,
                'cardNo' => $child->card_no,
                'birthDate' => $child->date_of_birth
            ], 200);
        } else if (!$childExists && $parentExists) {
            $child = Child::create([
                'card_no' => $request->card_no,
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'surname' => $request->last_name,
                'date_of_birth' => $request->birth_date,
                'house_no' => $request->house_no,
                'ward_id' => $ward_id,
                'facility_id' => $request->facility_id,
                'modified_by' => $request->modified_by,
                'gender' => $request->gender,
            ]);

            $parentData->children()->attach([$child->card_no=>["relationship_with_child"=>$request->relation]]);


            $vaccine_count = Vaccination::all()->count();

            for ($id = 1; $id <= $vaccine_count; $id++) {
                ChildVaccination::create([
                    'child_id' => $child->card_no,
                    'vaccination_id' => $id,
                    'is_active' => true,
                ]);
            }

            return response()->json([
                'message' => 'Child added successfully!',
                'cardNo' => $child->card_no,
                "birthDate" => $child->date_of_birth
            ], 200);
        }
        return response()->json([
            'message' => 'Child not added!',
            'status' => 400,
        ], 400);
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
                    'certificates',
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

    public function children_data(Request $request){
        $children = Child::all();
        
        $regionId = 3;

        $validator = Validator::make($request->all(), [
            'region' => 'integer|nullable',
            'district' => 'integer|nullable',
            'year' => 'integer|nullable|min:1900|max:' . date('Y'),
            'gender' => 'string|nullable|in:Male,Female',
            'vaccine' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $regionId = $request->input('region');
        $districtId = $request->input('district');
        $year = $request->input('year');
        $gender = $request->input('gender');
        $vaccineId = $request->input('vaccine');

        $vaccinated_children = Child::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('ward.district.region', function ($query) use ($regionId) {
                    $query->where('id', $regionId);
                });
            })
            ->when($districtId, function ($query) use ($districtId) {
                $query->whereHas('ward.district', function ($query) use ($districtId) {
                    $query->where('id', $districtId);
                });
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->whereHas('vaccinations') 
            ->whereDoesntHave('vaccinations', function ($query) use ($vaccineId){
                $query->where('is_active','!=' ,0);
                if ($vaccineId) {
                    $query->where('vaccine_id', $vaccineId);
                }
            })
            ->get();

            $registered_children = Child::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('ward.district.region', function ($query) use ($regionId) {
                    $query->where('id', $regionId);
                });
            })
            ->when($districtId, function ($query) use ($districtId) {
                $query->whereHas('ward.district', function ($query) use ($districtId) {
                    $query->where('id', $districtId);
                });
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->get();


            $parents = User::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->whereHas('ward.district.region', function ($query) use ($regionId) {
                    $query->where('id', $regionId);
                });
            })
            ->when($districtId, function ($query) use ($districtId) {
                $query->whereHas('ward.district', function ($query) use ($districtId) {
                    $query->where('id', $districtId);
                });
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->whereHas('role', function ($query){
                $query->where('account_type',"parent");
                
            })
            ->get();

            $ministry_accounts = User::query()
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->whereHas('role', function ($query){
                $query->where('account_type',"ministry");
                
            })
            ->get();

            $regional_accounts = User::query()
            ->when($regionId, function ($query) use ($regionId) {
                $query->where('region_id', $regionId);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->whereHas('role', function ($query){
                $query->where('account_type',"regional");
            })
            ->get();

            $district_accounts = User::query()
            ->when($districtId, function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year); 
            })
            ->whereHas('role', function ($query){
                $query->where('account_type',"district");
            })
            ->get();

            $pieChartData = [['name'=>'ministry','value'=>count($ministry_accounts)],['name'=>'regional','value'=>count($regional_accounts)],['name'=>'district','value'=>count($district_accounts)],['name'=>'children','value'=>count($registered_children)],['name'=>'parents','value'=>count($parents)]];
        
            $success = count($registered_children) == 0 ? 0 : 100*((count($vaccinated_children))/count($registered_children) );

        $approx = number_format($success,2);


        return response()->json(['registered_children' => count($registered_children), 'vaccinated_children' => count($vaccinated_children), 'unvaccinated_children' => count($registered_children) - count($vaccinated_children), 'success' => $approx,'region_children'=>count($vaccinated_children),'chart_data'=>$pieChartData]);
    }

    public function updateChildParentInfo(Request $request)
    {
        $child = Child::where('card_no', $request->original_card_no)->first();
        $parent = ParentsGuardians::where('nida_id', $request->original_nida_no)->first();
        $child_card_number = $request->original_card_no;
        $parent_nida_number = $request->original_nida_number;

        if ($child && $parent) {
            $child->update([
                'firstname' => $request->child_parent_data['first_name'],
                'surname' => $request->child_parent_data['last_name'],
                'middlename' => $request->child_parent_data['middle_name'],
                'ward_id' => $request->child_parent_data['ward_id'],
                'gender' => $request->child_parent_data['gender'],
                'house_no' => $request->child_parent_data['house_no'],
                'date_of_birth' => $request->child_parent_data['birth_date'],
                'facility_id' => $request->facility_id,
                'modified_by' => $request->modified_by,
            ]);

            $parent->update([
                'firstname' => $request->child_parent_data['par_first_name'],
                'middlename' => $request->child_parent_data['par_middle_name'],
                'lastname' => $request->child_parent_data['par_last_name'],
            ]);

            $user_parent = User::where('id', $parent->user_id)->first();
            if ($user_parent) {
                $user_parent->update([
                    'contacts' => $request->child_parent_data['contact'],
                    'ward_id' => $request->child_parent_data['ward_id'],
                ]);
            }

            $relationship = ParentsGuardiansChild::where('nida_id', $parent->nida_id)->where('card_no', $child->card_no)->first();
            if ($relationship) {
                $relationship->update([
                    'relationship_with_child' => $request->child_parent_data['relation'],
                ]);
            }


            if ($request->original_card_no !== $request->child_parent_data['card_no']) {
                $child_card_number = $request->child_parent_data['card_no'];
                $this->updateCardNo($request->original_card_no, $request->child_parent_data['card_no']);
            }

            if ($request->original_nida_no !==  $request->child_parent_data['nida_id']) {
                $parent_nida_number = $request->child_parent_data['nida_id'];
                $this->updateNidaNo($request->original_nida_no, $request->child_parent_data['nida_id']);
            }




            return response()->json([
                'message' => 'Information Updated Successfully!',
                'child_card_number' => $child_card_number,
                'parent_nida_number' => $parent_nida_number,
            ]);
        } else {
            return response()->json([
                'error' => 'Either parent or child does not exist, please verify!',
            ]);
        }
    }

    public function updateCardNo($oldCardNo, $newCardNo)
    {
        DB::beginTransaction();

        try {
          
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $tables = [
                'children' => 'card_no',
                'parents_guardians_children' => 'card_no',
                'bookings' => 'child_id',
                'certificates' => 'child_id',
                'child_vaccinations' => 'child_id',
                'child_vaccination_schedules' => 'child_id',
            ];

            foreach ($tables as $table => $columnName) {
                DB::table($table)->where($columnName, $oldCardNo)->update([$columnName => $newCardNo]);
            }

           
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();
            return "Card number updated successfully!";
        } catch (\Exception $e) {
            DB::rollback();
           
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return "Error updating card number: " . $e->getMessage();
        }
    }


    public function updateNidaNo($oldNidaNo, $newNidaNo)
    {

        DB::beginTransaction();


        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $tables = [
                'parents_guardians' => 'nida_id',
                'parents_guardians_children' => 'nida_id',
            ];


            foreach ($tables as $table => $columnName) {
                DB::table($table)->where($columnName, $oldNidaNo)->update([$columnName => $newNidaNo]);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');


            DB::commit();
            return "Nida number updated successfully!";
        } catch (\Exception $e) {
            DB::rollback();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return "Error updating nida number: " . $e->getMessage();
        }
    }
}
