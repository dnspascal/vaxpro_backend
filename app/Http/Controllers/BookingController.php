<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\SmsService;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{

    public function store(Request $request): JsonResponse
    {
            // return response()->json( [ "request"=> $request->all()], 200 );
            $values = $request->only(['facility_id', 'vaccine_list', 'child_id', 'vaccination_date']);
            $vaccinationDate = Carbon::parse($request->vaccination_date)->toDateTimeString();
            $booking = Booking::create(
                ["facility_id" => $request->facility_id, "vaccine_list" => $request->vaccine_list, "child_id" => $request->child_id, "vaccination_date" => $vaccinationDate]
            );

            return response()->json('booking set success', 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $booking = Booking::with(['facilities', 'children'])->where('facility_id', $id)->orderBy('created_at','desc')->get();

        return response()->json($booking, 200);
    }

    public function indexBooking(string $id): JsonResponse
    {

        $booking = Booking::where('id', $id)->first();
         if ($booking){
//             $bookingDetails = array() ;
             $bookingDetails = $booking;
             $bookingDetails['children'] = $booking->children;

             return response()->json($bookingDetails, 200);

         }
         return response()->json('booking not found', 404);
    }


    public function update(Request $request, string $id): JsonResponse
    {
          $bookingUpdate = Booking::where('id', $id)->first();
          if($bookingUpdate){
              $isBookingUpdateSuccess = $bookingUpdate->update($request->all());

              if($isBookingUpdateSuccess){
                  $bookingDetailsAfterUpdate = Booking::with(['facilities', 'children'])->where('id', $id)->first();

                  $facility_name = strtoupper($bookingDetailsAfterUpdate->facilities->facility_name);
                  $vaccination_date = strtoUpper($bookingDetailsAfterUpdate->vaccination_date);
                  $first_name = strtoUpper($bookingDetailsAfterUpdate->children->firstname);
                  $last_name = strtoUpper($bookingDetailsAfterUpdate->children->surname);
                  $child_name = $first_name." ".$last_name;
                  $formatted_date = null;
                  $postData = null;
                  try{
                      $date = new \DateTime($vaccination_date);
                      $formatted_date = $date->format('d/m/Y');

                  }catch(\Exception $exception){
                      Log::info("THIS IS THE EXCEPTION THROWN ON DATE FORMATTING", [$exception->getMessage()]);
                  }

                  if($bookingDetailsAfterUpdate['status'] == 'confirmed'){
                      $today = date("d/m/Y");
                      $postData= [
                          "message" =>
                              $formatted_date == $today ? "Your booking with child name $child_name  has been confirmed, You can visit $facility_name, today."
                          : "Your booking with child name $child_name  has been confirmed, You can visit $facility_name on $formatted_date."
                          ,
                          "recipient"=> 255752451811
                      ];

                  } elseif ($bookingDetailsAfterUpdate->status == 'cancelled'){
                      $reject_reason = $bookingDetailsAfterUpdate->rejection_reason;
                      $postData= [
                          "message" => "Your booking with child name $child_name at $facility_name on $formatted_date has been cancelled because, $reject_reason."
                          ,
                          "recipient"=> 255745884099
                          ];
                      Log::info("THIS IS THE EXCEPTION THROWN ON DATE FORMATTING", [$bookingDetailsAfterUpdate]);

                  }
                  $sendingMessage = new SmsService();
                  $sendingMessage->sms_oasis($postData);
                  return response()->json('updated success', 201);
              }
              return response()->json('update failed', 400);
          }

            return response()->json(['message' => 'Booking not found!'], 404);
    }


    //parent booking

    public function parent_bookings(string $id):JsonResponse
    {
        $bookings = Booking::with(['facilities'])->where('child_id', $id)->orderBy('created_at','desc')->get();
        return response()->json($bookings, 200);
    }

    public function destroy(string $id):JsonResponse
    {
        $deletedSuccess=Booking::where('id', $id)->delete();

        if($deletedSuccess){
            return response()->json('booking deleted', 200);
        }
        return response()->json('booking not found', 404);
    }
}
