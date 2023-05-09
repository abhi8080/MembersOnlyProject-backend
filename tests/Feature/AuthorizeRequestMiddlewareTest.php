<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Http\Middleware\AuthorizeRequestMiddleware;

use Illuminate\Http\Response;
use App\Utils\JWTToken;
use Illuminate\Http\Request;

class AuthorizeRequestMiddlewareTest extends TestCase
{

    public function testHandleWithValidToken(): void
    {
        $middleware = new AuthorizeRequestMiddleware();

        $token = JWTToken::generateToken(['id' => 1, 'username' => 'user', 'membership-status' => 'active']);

        $request = Request::create('/api/messages', 'GET');
        $request->headers->set('Authorization', "Bearer $token");

        $response = $middleware->handle($request, function ($request) {
            return new Response('next');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('next', $response->getContent());
    }

    public function testHandleWithInvalidToken(): void
    {
        $middleware = new AuthorizeRequestMiddleware();

        $token = 'invalid_token';

        $request = Request::create('/api/messages', 'GET');
        $request->headers->set('Authorization', "Bearer $token");

        $response = $middleware->handle($request, function ($request) {
            return new Response('next');
        });

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('{"error":"Bad token"}', $response->getContent());
    }

    public function testHandleWithMissingToken(): void
    {
        $middleware = new AuthorizeRequestMiddleware();

        $request = Request::create('/api/messages', 'GET');

        $response = $middleware->handle($request, function ($request) {
            return new Response('next');
        });

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('{"error":"No token"}', $response->getContent());
    }
}




