<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
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
        $booking = Booking::with('facilities')->where('facility_id', $id)->orderBy('vaccine_list')->get();
        return response()->json($booking, 200);
    }

    public function indexBooking(string $id): JsonResponse
    {

        $booking = Booking::where('id', $id)->first();
         if ($booking){
             $bookingDetails = array() ;
             $bookingDetails = $booking;
             $bookingDetails['children'] = $booking->children;

             return response()->json($bookingDetails, 200);

         }
         return response()->json('booking not found', 404);
    }

    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
          $bookingUpdate = Booking::where('id', $id)->first();
        if (!$bookingUpdate) {
            return response()->json(['message' => 'Booking not found!'], 404);
        }
        $bookingUpdate->update($request->all());

        return response()->json('updated success', 201);
    }


    public function destroy(string $id)
    {
        //
    }
}
