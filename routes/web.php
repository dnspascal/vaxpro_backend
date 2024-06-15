<?php

use App\Events\MessageSent;
use App\Events\PrivateEvent;
use App\Events\testingEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/trial_login', function (Request $request) {
    
    $credentials = $request->only(["uid", "password"]);

    if (Auth::attempt($credentials)) {
    
        $token  = $request->user()->createToken("vaxPro")->plainTextToken;
    
        return redirect('/')->withCookie(cookie('auth_token', $token)); 
    
    }   
        
    return redirect('/login')->withErrors(['message' => 'Invalid credentials']);

});

Route::get('/trial_private', function () {
    // event(new testingEvent("Hellow world"));
    $user = User::find(1);
    $user2 = User::find(102);
    // broadcast(new PrivateEvent("Hello world private channel"));
    broadcast(new MessageSent($user2,$user,"This worked"));
    return "done";
});

Route::get('/trial_public', function () {
    event(new testingEvent("Hellow world"));
   
    return "done";
});