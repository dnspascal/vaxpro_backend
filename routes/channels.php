<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user,$id) {
    
    return $user->id == $id;
});

Broadcast::channel('booking.{id}', function ($user,$id) {
   
    return true;
    // return $user->facilities->facility_reg_no == $facility;
});