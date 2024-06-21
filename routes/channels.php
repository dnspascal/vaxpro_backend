<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user,$id) {
    
    return $user->id == $id;
});

Broadcast::channel('booking.{facility}', function ($user,$facility) {
    
    return $user->facilities->facility_reg_no == $facility;
});