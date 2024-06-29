<?php

namespace App\Http\Controllers;

use App\Models\CommunityHealthworkerFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function submitFeedback(Request $request)
    {

        $reason = '';
        if ($request->feedback['reason'] != null) {
            $reason = $request->feedback['reason'];
        } else if ($request->feedback['otherReason'] != null) {
            $reason = $request->feedback['otherReason'];
        }
        CommunityHealthworkerFeedback::create([
            'child_id' => $request->feedback['child_card_no'],
            'facility_id' => $request->feedback['facility_reg_no'],
            'reason_for_absence' => $reason,
        ]);

        return response()->json([
            'response' => 'Response submitted successfully!',
            'status' => 200
        ]);
    }
}
