<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Check if the email has been verified
            if (is_null($user->email_verified_at)) {
                return response()->json(['error' => 'Email not verified'], 403);  // Forbidden
            }

            // Check if the account is banned (status is 0)
            if ($user->status == 0) {
                return response()->json(['error' => 'Your account is banned. Please contact us.'], 403);  // Forbidden
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not valid'], 401);
        }

        return $next($request);
    }
}
