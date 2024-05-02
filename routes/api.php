<?php

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



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
