<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class apiProtectRoute extends BaseMiddleware
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
        } catch (\Exception $exception) {

            if ($exception instanceof TokenInvalidException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token is Invalid'
                ], 401);
            } else if ($exception instanceof TokenExpiredException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token is Expired'
                ], 401);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authorization token not found'
                ], 401);
            }
        }

        return $next($request);
    }
}
