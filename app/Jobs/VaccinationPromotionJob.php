<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\ParentsGuardians;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VaccinationPromotionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $smsService;
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        
        $parent_guardians = ParentsGuardians::all();

        foreach ($parent_guardians as $parent) {
            $randomMessage = Notification::inRandomOrder()->first();   
            Log::info("This is the post message sent", [$randomMessage->message,$parent->user->contacts]);
            $postData = [

                'message' => $randomMessage->message,
                'recipient' =>$parent->user->contacts
            ];
             $this->smsService->sms_oasis($postData);
            }
       
        // $randomMessage = Notification::inRandomOrder()->first();   
        // // Log::info("This is the post message sent", [$randomMessage->message,$parent->user->contacts]);
        // $postData = [

        //     'message' => $randomMessage->message,
        //     'recipient' =>'255745884099'
        // ];
        //$this->smsService->sms_oasis($postData);
        

    }
}
