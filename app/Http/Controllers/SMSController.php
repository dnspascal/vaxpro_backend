<?php

namespace App\Http\Controllers;

use App\Services\SmsService;


class SMSController extends Controller
{
    protected $smsService;
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    public function sendSms()
    {
        $postData = [
            'source_addr' => 'INFO',
            'encoding' => 0,
            'schedule_time' => '',
            'message' => 'Umesajiliwa kikamilifu kwenye mfumo wa VaxPro, tumia password-"'."password"." na uid ",
            'recipients' => [
                ['recipient_id' => '1', 'dest_addr' => '255745884099'],
                ['recipient_id' => '2', 'dest_addr' => '255658004980']
            ]
        ];

        try {
            
            $this->smsService->send__multiple_recipient();

         return response()->json(["message sent successfully"]);
        } catch (\Exception $e) {
            // Handle exception
            return ['error' => $e->getMessage()];
        }
        
    }
}