<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'userData']);
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/all_users', [UserController::class,'allUsers']);
});

Route::post('/login', [AuthController::class, 'login']);
