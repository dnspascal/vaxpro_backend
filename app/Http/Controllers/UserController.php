<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    public function userData()
    {
        if (Auth::check()) {
            $user = array();
            $user = Auth::user();
            $user['role'] = Auth::user()->role;
            return response()->json([$user]);
        }
        return response()->json(["message" => "user not authenticated"], 401);
    }

    public function allUsers(Request $request)
    {
    
        $allUsers = User::with([
          'role',
          
        ])->get();
       return  $allUsers;

    }
}
