<?php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\VaccinationSchedulesController;
use App\Http\Controllers\CertificatesController;
use App\Http\Controllers\ChatMessageController;
use Illuminate\Support\Facades\Route;

Route::post('createVaccine', [VaccinationController::class, 'createVaccine']);
Route::get('getVaccines', [VaccinationController::class, 'getVaccines']);
Route::get('getVaccine/{id}', [VaccinationController::class, 'getVaccine']);
Route::put('updateVaccine/{id}', [VaccinationController::class, 'updateVaccine']);
Route::delete('deleteVaccine/{id}', [VaccinationController::class, 'deleteVaccine']);
Route::post('parentChildData', [ChildController::class, 'parentChildData']);
Route::get('getSavedSchedules/{id}', [VaccinationSchedulesController::class, 'getSavedSchedules']);
Route::get('getChildParentData', [ChildController::class, 'getChildParentData']);
Route::get('children', [ChildController::class, 'children']);
Route::get('parents', [ParentController::class, 'parents']);
Route::get('getChildData/{id}', [ChildController::class, 'getChildData']);
Route::post('getAllChildSchedules', [VaccinationSchedulesController::class, 'vaccine']);
Route::get('getVacSchedules/{id}', [VaccinationSchedulesController::class, 'getVacSchedules']);
Route::post('updateChildVacSchedule', [VaccinationSchedulesController::class, 'updateChildVacSchedule']);
Route::get('getChildVaccines', [VaccinationController::class, 'getVaccines']);
Route::post('all_children',[ChildController::class,'children_data']);
Route::get('fetchVaccineIds', [VaccinationController::class, 'fetchVaccineIds']);
Route::post('updateSelectedVacs', [VaccinationSchedulesController::class,'updateSelectedVacs']);


Route::middleware(['auth:sanctum', 'tokenExpiration'])->group(function () {

    //auth endpoints
    Route::post('/register', [AuthController::class, 'register']);
    Route::patch('update_user/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //user endpoints
    Route::get('/user', [UserController::class, 'userData']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    Route::get('/all_users/{id}', [UserController::class, 'allUsers']);


    //address endpoints
    Route::get('/regions', [RegionController::class, 'showAll']);
    Route::get('region_districts/{region_id}', [DistrictController::class, 'region_districts']);
    Route::get('districts_wards/{district_id}', [WardController::class, 'districts_wards']);

    //roles endpoints
    Route::get("/roles", [RoleController::class, "index"]);

    //certificates

    //booking endpoints


    //send msg endpoint 
    Route::post('send_message',[ChatMessageController::class,'store']);
});
Route::post("/certificates", [CertificatesController::class,"store"]);


Route::get('hospital_bookings/{id}', [BookingController::class,'show']);
Route::get("indexBooking/{card_no}", [BookingController::class,'indexBooking']);
Route::put('/update_booking/{id}', [BookingController::class, 'update']);

//parent bookings
Route::get('parent_bookings/{id}', [BookingController::class,'parent_bookings']);
Route::get('delete_booking/{id}', [BookingController::class, 'destroy']);


// regions endpoints
Route::post('region', [RegionController::class, 'create']);
Route::get('region/{id}', [RegionController::class, 'show']);
Route::put('region/{id}', [RegionController::class, 'update']);
Route::delete('region/{id}', [RegionController::class, 'destroy']);

// district endpoints
Route::get('district_facilities/{id}', [DistrictController::class, 'show_facilities']);
Route::get('district_wards/{id}', [DistrictController::class, 'show_wards']);
Route::get('districts', [DistrictController::class, 'showAll']);
Route::post('district', [DistrictController::class, 'create']);
Route::get('district/{id}', [DistrictController::class, 'show']);
Route::put('district/{id}', [DistrictController::class, 'update']);
Route::delete('district/{id}', [DistrictController::class, 'destroy']);


// wards endpoints
Route::get('wards', [WardController::class, 'showAll']);
Route::post('ward', [WardController::class, 'create']);
Route::get('ward/{id}', [WardController::class, 'show']);
Route::put('ward/{id}', [WardController::class, 'update']);
Route::delete('ward/{id}', [WardController::class, 'destroy']);

// facility endpoints
Route::get('facilities', [FacilityController::class, 'showAll']);
Route::post('facility', [FacilityController::class, 'create']);
Route::get('facility/{id}', [FacilityController::class, 'show']);
Route::put('facility/{id}', [FacilityController::class, 'update']);
Route::delete('facility/{id}', [FacilityController::class, 'destroy']);

// children



// send sms
Route::post('send_sms', [SMSController::class, 'sendSms']);
Route::post('sms', [SMSController::class, 'sms_oasis']);


// booking endpoints
Route::post('add_booking', [BookingController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/parent_login', [AuthController::class, 'parent_login']);

