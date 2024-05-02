<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Helpers\GenerateRoleIdHelper;
use App\Helpers\GeneratePasswordHelper;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $roleId = GenerateRoleIdHelper::generateRoleId($request->account_type);

        $role =  Role::create([
            'role_id' => $roleId,
            'role' => $request->role,
            'account_type' => $request->account_type,
        ]);
        if ($role) {
            $password = GeneratePasswordHelper::generatePassword();
            $user = User::create([
                'role_id' => $role->role_id,
                'password' => Hash::make($password),
                'ward_id' => $request->ward_id,
                'district_id' => $request->district_id,
                'region_id' => $request->region_id,
                'facility_id' => $request->facility_id,
                'contacts' => $request->contacts,
            ]);
            return response()->json(['message' => "User successfully added", $password, "status"=>200]);
        } else {
            return response()->json(["message" => "Error occured, Please try again", "status"=>401]);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only(["role_id", "password"]);

        if (Auth::attempt($credentials)) {

            $token  = $request->user()->createToken("API value")->plainTextToken;

            return response()->json([
                "token" => $token,
                "message" => "logged in",
                "status" => 200,
            ]);
        } else
            return response()->json([
                "token" => "2345678",
                "message" => "user not found",
                "status" => 404,
            ]);
    }
   
}
