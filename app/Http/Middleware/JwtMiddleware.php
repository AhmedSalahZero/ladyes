<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        try {
            // Call the JWT middleware
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Handle token expired exception
            return response()->json(['error' => 401, 'msg' => 'Token expired']);
        } catch (TokenInvalidException $e) {
            // Handle token invalid exception
            return response()->json(['error' => 401, 'msg' => 'Token invalid']);
        } catch (JWTException $e) {
            // Handle generic JWT exception
            return response()->json(['error' => 401, 'msg' => 'Token absent']);
        }

        return $next($request);
    }
}
