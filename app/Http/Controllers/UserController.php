<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\User;

use App\Utils\JWTToken;

class UserController extends Controller
{
    public function addUser(Request $request) {
        $data = [
            'username' => $request->username,
            'full_name' => $request->full_name,
            'password' => password_hash($request->password, PASSWORD_BCRYPT),
            'membership-status' => $request->membership_status
        ];
        $user = User::create($data);
        return response($user, 201);
    }

    public function login(Request $request) {
        $user = User::where('username',  $request->query('username'))->first();
        if(is_null($user)) {
            return response()->json(['message' => 'Account does not exist'], 401);
        }
 if (!password_verify( $request->query('password'), $user->password))
      return response()->json(['message' => 'Wrong password'], 401);

    $token = JWTToken::generateToken($user);
    $minutes = 3000;
    $response = new Response('Successful');
    $response->withCookie(cookie('access_token', $token, $minutes));
    return $response;
    }

    public function updateUser() {
        $user = User::find(1);
        $user->update(['membership-status' => 'active']);
        return response(['message' => 'Updated successfully'], 201);
    }

}
