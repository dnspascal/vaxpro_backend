<?php

use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\WardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// regions endpoints
Route::get('regions', [RegionController::class,'showAll']);
Route::post('region', [RegionController::class,'create']);
Route::get('region/{id}', [RegionController::class,'show']);
Route::put('region/{id}', [RegionController::class,'update']);
Route::delete('region/{id}', [RegionController::class,'destroy']);

// district endpoints
Route::get('district_facilities/{id}', [DistrictController::class,'show_facilities']);
Route::get('district_wards/{id}', [DistrictController::class,'show_wards']);
Route::get('districts', [DistrictController::class,'showAll']);
Route::post('district', [DistrictController::class,'create']);
Route::get('district/{id}', [DistrictController::class,'show']);
Route::put('district/{id}', [DistrictController::class,'update']);
Route::delete('district/{id}', [DistrictController::class,'destroy']);


// wards endpoints
Route::get('wards', [WardController::class,'showAll']);
Route::post('ward', [WardController::class,'create']);
Route::get('ward/{id}', [WardController::class,'show']);
Route::put('ward/{id}', [WardController::class,'update']);
Route::delete('ward/{id}', [WardController::class,'destroy']);

// facility endpoints
Route::get('facilities', [FacilityController::class,'showAll']);
Route::post('facility', [FacilityController::class,'create']);
Route::get('facility/{id}', [FacilityController::class,'show']);
Route::put('facility/{id}', [FacilityController::class,'update']);
Route::delete('facility/{id}', [FacilityController::class,'destroy']);