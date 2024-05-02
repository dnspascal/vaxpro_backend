<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\VaccinationSchedules;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

Route::post('createVaccine', [VaccinationController::class, 'createVaccine']);
Route::get('getVaccines', [VaccinationController::class, 'getVaccines']);
Route::get('getVaccine/{id}', [VaccinationController::class, 'getVaccine']);
Route::put('updateVaccine/{id}', [VaccinationController::class, 'updateVaccine']);
Route::delete('deleteVaccine/{id}', [VaccinationController::class, 'deleteVaccine']);
Route::post('parentChildData', [ChildController::class, 'parentChildData']);
Route::get('generateSchedule', [VaccinationSchedules::class, 'vaccine']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'userData']);
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/all_users', [UserController::class,'allUsers']);
});

Route::post('/login', [AuthController::class, 'login']);
