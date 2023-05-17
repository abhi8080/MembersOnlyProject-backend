<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use TheSeer\Tokenizer\Exception;

class JWTToken {
    
    /**
     * Generate a JWT token for the given user.
     *
     * @param array $user
     * @return string
     * @throws Exception
     */
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
      
    /**
     * Verify the validity of a JWT token and return the decoded token.
     *
     * @param string $token
     * @return object
     * @throws Exception
     */
    public static function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET'), 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            throw new Exception('Bad token');
        }
    }
}


