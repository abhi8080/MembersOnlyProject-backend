<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utils\JWTToken;

class JWTTokenTest extends TestCase
{

    public function testGenerateToken()
    {
        $user = [
            'id' => 1,
            'username' => 'testuser',
            'membership-status' => 'active'
        ];

        $jwt = JWTToken::generateToken($user);
        $this->assertIsString($jwt);
    }

    public function testGenerateTokenWithInvalidUser()
    {
        $this->expectException(\Exception::class);

        $user = [
            'username' => 'testuser',
            'membership-status' => 'active'
        ];

        JWTToken::generateToken($user);
    }

    public function testVerifyToken()
    {
        $user = [
            'id' => 1,
            'username' => 'testuser',
            'membership-status' => 'active'
        ];

        $jwt = JWTToken::generateToken($user);

        $decoded = JWTToken::verifyToken($jwt);
        $this->assertEquals($decoded->id, $user['id']);
        $this->assertEquals($decoded->username, $user['username']);
        $this->assertEquals($decoded->{'membership-status'}, $user['membership-status']);
    }

    public function testVerifyTokenWithBadToken()
    {
        $this->expectException(\Exception::class);

        JWTToken::verifyToken('invalid-token');
    }
}

