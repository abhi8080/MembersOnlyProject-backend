<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function getMessages() {
        $messages = Message::all();
        $obj = User::select('membership-status')->find(1);

        if (!($obj->{'membership-status'} == 'active')) {
            foreach ($messages as &$message) {
                $message->user_id = "Anonymous";
                $message->timestamp = "";
              }
        }
        return response()->json($messages, 200);
    }
    public function addMessage(Request $request) {
        $message = Message::create($request->all());
        return response($message, 201);
    }

}
