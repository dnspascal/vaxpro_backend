<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\testingEvent;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    //

    public function store(Request $request){
        ChatMessage::create($request->toArray());

        $receiver = User::find($request->receiver);
        $sender = User::find($request->sender);

        broadcast(new MessageSent($receiver,$sender,$request->message));
        // event(new testingEvent("Hellow world"));
        return response()->json(['receiver'=>$receiver->id,'sender'=>$sender->id]);
    }
}
