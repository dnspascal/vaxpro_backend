<?php

namespace App\Http\Controllers;

use App\Models\HealthWorker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    public function userData()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user = array();
            $user = Auth::user();
            $user['role'] = Auth::user()->role;

            if (!is_null($user->district_id)) {
                $user['district'] = Auth::user()->district->region;

                return response()->json([$user]);
            }
            if (!is_null($user->region_id)) {
                $user['region'] = Auth::user()->region;
                return response()->json([$user]);
            }

            if (!is_null($user->facility_id)) {
                $user['facility'] = Auth::user()->facilities;
                return response()->json([$user]);
            }

            if (!is_null($user->health_workers)) {
                $health_worker = HealthWorker::where('user_id',$user->id);
                $user['health_worker'] = $health_worker;
                return response()->json([$user]);
            }



            return response()->json([$user]);
        }
        return response()->json(["message" => "user not authenticated"], 401);
    }

    public function allUsers(Request $request)
    {

        
        $loggedInUser = User::find($request->id);
        switch ($loggedInUser->role->account_type) {
            case "ministry":

                return $allUsers = User::whereHas('role', function ($query) {
                    $query->where('account_type', "ministry")
                     ->orWhere('account_type', 'regional')
                        ->where('role', "IT admin");
                })->with(['role', 'region'])->get();

            case "district":
                return $allUsers = User::where('district_id',$loggedInUser->district->id)->with(['role', 'district', 'ward'])->get();
            case 'regional':
                return $allUsers = User::where('region_id', $loggedInUser->region_id)->with(['role', 'district', 'ward'])->get();
        }
        $allUsers = User::with(['role', 'district', 'region'])->get();

        return  $allUsers;
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json('user deleted successfully', 204);
        }
        return response()->json(['message' => "user not found"], 404);
    }
}
