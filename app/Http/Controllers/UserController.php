<?php

namespace App\Http\Controllers;

use App\Helpers\VerificationCode;
use App\Models\Child;
use App\Models\ChildVaccination;
use App\Models\ParentsGuardians;
use App\Models\User;
use App\Models\Ward;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function userData()
    {
        if (Auth::check()) {

            $user = Auth::user();
            $parent = ParentsGuardians::where('user_id', $user->id)->first();
            $user['role'] = Auth::user()->role;

            if ($user->role->account_type == "ministry") {
                return response()->json($user, 200);
            }

            if (!is_null($user->district_id)) {
                $user['district'] = Auth::user()->district->region;

                return response()->json($user, 200);
            }
            if (!is_null($user->region_id)) {
                $user['region'] = Auth::user()->region;
                return response()->json($user, 200);
            }

            if (!is_null($user->facility_id)) {
                $user['facilities'] = Auth::user()->facilities;
                Auth::user()->health_workers;


                return response()->json($user, 200);
            }


            if (!is_null($parent)) {
                $child = $parent->children()->get();
                $user['children'] = $child;
                $ward = Ward::where('id', $user->ward_id)->first();
                $user['parent'] = $parent;
                $user['ward'] = $ward->ward_name . ", " . $ward->district->district_name;

                function calculateAge($dateString)
                {
                    $birthDate = Carbon::parse($dateString);
                    $currentDate = Carbon::now();
                    $age = $currentDate->diffInYears($birthDate);

                    return $age;
                }

                $user['age'] = calculateAge($user->date_of_birth);
                return response()->json($user, 200);
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
                    $query->where('account_type', "ministry")->orWhere('account_type', 'regional')->where('role', "IT admin");
                })->with(['role', 'region'])->get();

            case "district":
                return $allUsers = User::where('district_id', $loggedInUser->district->id)->with(['role', 'district', 'ward'])->get();
            case 'regional':
                return $allUsers = User::where('region_id', $loggedInUser->region_id)->with(['role', 'district', 'ward'])->get();
        }
        $allUsers = User::with(['role', 'district', 'region'])->get();

        return $allUsers;
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



    //PASSWORD RECOVERY
    public function passwordRecovery(Request $request): JsonResponse
    {
        $user = User::where('contacts', $request['contacts'])->first();

        if ($user) {
            $verificationCode = VerificationCode::generateCode();
            $phone_number = explode('+', $user->contacts)[1];
            $post_data = ['message' => "Your verification code is $verificationCode", 'recipient' => $phone_number];
            $sendingMessage = new SmsService();
            $sendingMessage->sms_oasis($post_data);
            return response()->json(['contacts' => $user->contacts, 'code' => $verificationCode], 201);
        }
        return response()->json(['message' => "User not found", 'status' => 401]);
    }

    public  function resendCode(Request $request): JsonResponse
    {
        if($request['contacts']) {
            $verificationCode = VerificationCode::generateCode();
            Log::info($request['contacts'],["LONG AS WE GOTTA"]);
            $post_data = ['message' => "Your verification code is $verificationCode", 'recipient' => $request['contacts']];
            $sendingMessage = new SmsService();
            $sendingMessage->sms_oasis($post_data);
            Log::info($request['contacts'],["LONG AS WE DONT GOTTA"]);

            return response()->json($verificationCode, 201);
        }
            return response()->json("Resend failed", 400);

    }

    public function recoveryQuestion(Request $request): JsonResponse
    {

        $user = User::where('contacts', $request->contacts)->first();

        if ($user) {
            if ($request['account_type']==="Hospital") {
                if ($user->facility_id === $request->hospital) {
                    return response()->json(["message" => "Your answers matched our records"], 201);
                }
                return response()->json(["message" => "Your answers for this account couldn't match our records", "status" => 400]);
            } else if ($request['account_type']==="District") {
                if ($user->district_id === $request['district'] && str_starts_with($user->uid, '3000') ) {
                    return response()->json(["message" => "Your answers matched our records"], 201);
                }
                return response()->json(["message" => "Your answers for this account couldn't match our records", "status" => 400]);
            } else if($request['account_type']==="Region") {
                if ($user->region_id === $request['region'] && str_starts_with($user->uid, '2000')) {
                    return response()->json(["message" => "Your answers matched our records"], 201);
                }
                return response()->json(["message" => "Your answers for this account couldn't match our records", "status" => 400]);
            }else if($request['account_type'] === "Ministry") {
                if (str_starts_with($user->uid, '1000')) {
                    return response()->json(["message" => "Your answers matched our records"], 201);
                }
                return response()->json(["message" => "Your answers for this account couldn't match our records", "status" => 400]);
            }
        }
        return response()->json(['message' => "User not found", 'status' => 401]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = User::where('contacts', $request['contacts'])->first();

        if ($user) {
            $userUpdated = $user->update(['password' => Hash::make($request['password'])]);
            if ($userUpdated) {
                $post_data = ['message' => "Your password has been updated, You can now login in the system", 'recipient'=>request('contacts')];
                $sendingMessage = new SmsService();
                $sendingMessage->sms_oasis($post_data);
                return response()->json(["message"=>"Password updated successfully", "status"=>201]);
            }
            return response()->json(["message"=>"Password update failed","status"=> 401]);
        }
        return response()->json(['message' => "User not found", 'status' => 401]);
    }

    // child data

    public function childData($id){
        $child = Child::where('card_no',$id)->first();

        $childVaccinationData = [];
        if($child){
            $vaccinations = ChildVaccination::where('child_id',$child->card_no)->get();

            foreach ($vaccinations as  $value) {
                $childVaccinationData[] = ["name"=>$value->vaccinations()->first()->abbrev,"total"=>$value->vaccinations()->first()->frequency,"received"=>$child->child_vaccination_schedules->where('child_vaccination_id',$value->id)->count()];
            }
            return response()->json($childVaccinationData,200);
        }

        return response()->json("Child not found",404);
    }
}
