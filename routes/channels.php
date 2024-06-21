<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user,$id) {
    
    return $user->id == $id;
});

Broadcast::channel('rico', function ($user) {
    
    return true;
});