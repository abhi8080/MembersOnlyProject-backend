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
            'membership-status' => $request->membership_status,
            'is_admin' => $request->has('is_admin') ? true : false,
        ];
        $user = User::create($data);
        return response($user, 201);
    }

    public function login(Request $request) {
        $user = User::where('username',  $request->username)->first();
        if(is_null($user)) {
            return response()->json(['message' => 'Account does not exist'], 401);
        }
 if (!password_verify( $request->password, $user->password))
      return response()->json(['message' => 'Wrong password'], 401);
    $token = JWTToken::generateToken($user);
    return response()->json(['id' => $user->id, 'jwt' => $token], 200);
}

    public function updateUser($id) {
        $user = User::find($id);
        $user->update(['membership-status' => 'active']);
        return response(['message' => 'Updated successfully'], 201);
    }

public function getUserAdminStatus($id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    $isAdmin = $user->is_admin;
    return response()->json(['isAdmin' => $isAdmin], 200);
}

}
