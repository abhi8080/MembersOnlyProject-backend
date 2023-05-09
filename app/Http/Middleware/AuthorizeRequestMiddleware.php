<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Utils\JWTToken;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $authorizationHeader, $matches)) {
            $token = $matches[1];
            try {
                JWTToken::verifyToken($token);
                return $next($request);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 401);
            }
        }
            return response()->json(['error' => 'No token'], 403);
    }
}
