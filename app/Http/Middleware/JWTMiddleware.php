<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $message = '';

        try {
            // Check Token Validation
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $ex) {
            $message = 'Token Expired';
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $ex) {
            $message = 'Invalid Token';
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            $message = 'Provide Token';
        } 

        return response()->json([
            'success' => false,
            'message' => $message
        ]);
    }
}
