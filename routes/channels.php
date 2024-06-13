<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    dd("reach here");
    return (int) $user->id === (int) $id;
});
