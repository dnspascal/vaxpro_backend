<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function store(Request $request){
        $roleExists = Role::where('role', 'LIKE', "%{$request->role}%")
                            ->where('account_type', $request->account_type)
                            ->exists();
        if($roleExists){
            return response()->json( ["message"=>"Role already exists", "status"=>409]);
        }else{
            $role = new Role();
            $role->role = $request->role;
            $role->account_type = $request->account_type;
            $role->save();
            if($role->save()){
                return response()->json( "Role created successfully", 201);
            }
            return response()->json( "Error occurred", 401);
        }
    }
    public function index(Request $request)
    {
        return Role::orderBy("created_at", "desc")->get();
    }

    public function destroy(string $id){
        $role = Role::where("id", $id)->delete();

        if($role){
            return response()->json( "Role deleted successfully", 200);
        }
        return response()->json( "Error occurred", 401);
    }

}
