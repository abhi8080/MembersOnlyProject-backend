<?php

namespace App\Http\Controllers;

use App\Utils\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * Get all messages. 
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $authorizationHeader, $matches)) {
            $token = $matches[1];
            $decodedToken = JWTToken::verifyToken($token);
            $id = $decodedToken->id;

            return DB::transaction(function () use ($id) {
                $messages = Message::join('users', 'messages.user_id', '=', 'users.id')
                    ->select('messages.id', 'messages.title', 'messages.text', 'messages.timestamp', 'users.full_name')
                    ->get();

                $obj = User::select('membership-status')->find($id);
                if (!($obj->{'membership-status'} == 'active')) {
                    foreach ($messages as &$message) {
                        $message->full_name = "Anonymous";
                        $message->timestamp = "";
                    }
                }
                return response()->json($messages, 200);
            });
        }
    }

    /**
     * Add a new message.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addMessage(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $message = Message::create($request->all());
            return response($message, 201);
        });
    }

    /**
     * Delete a message.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessage($id)
    {
        return DB::transaction(function () use ($id) {
            $message = Message::find($id);

            if (!$message) {
                return response()->json(['message' => 'Message not found'], 404);
            }

            $message->delete();

            return response()->json(null, 200);
        });
    }
}


