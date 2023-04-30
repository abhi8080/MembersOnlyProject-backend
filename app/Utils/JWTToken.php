<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use TheSeer\Tokenizer\Exception;


class JWTToken {

    public static function generateToken($user) {      
        if (
          !isset($user['id']) ||
          !isset($user['username']) ||
          !isset($user['membership-status'])
        ) {
          throw new Exception('Could not generate token.');
        }
      
        $payload = [
          'id' => $user['id'],
          'username' => $user['username'],
          'membership-status' => $user['membership-status']
        ];
      
        $jwt = JWT::encode($payload, getenv('JWT_SECRET'), 'HS256');
      
        return $jwt;
      
      }
      

}
