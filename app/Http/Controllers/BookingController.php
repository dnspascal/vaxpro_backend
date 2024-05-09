<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function store(Request $request)
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
    public function show(string $id)
    {
//        $booking= Array();
        $booking = Booking::with('facilities')->where('facility_id', $id)->orderBy('vaccine_list')->get();
//        $booking['facility'] = $booking->facilities;
        return response()->json($booking, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
