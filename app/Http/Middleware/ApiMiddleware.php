<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Illuminate\Http\Response;


class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            JWTAuth::parseToken()->authenticate();
            //JWTAuth::setRequest($request)->parseToken()->authenticate();

        }
        catch (Exception $e) {

            if ($e instanceof TokenBlacklistedException) {
                return response()->json(['error' => 'Token  has been blacklisted'], Response::HTTP_UNAUTHORIZED);
            }

            else if ($e instanceof TokenInvalidException){
                return response()->json(['error' => 'Token is Invalid'], Response::HTTP_UNAUTHORIZED);
            }
            else if ($e instanceof TokenExpiredException){
                return response()->json(['error' => 'Token is Expired'], Response::HTTP_UNAUTHORIZED);
            }
            else{
                return response()->json(['error' => 'Authorization Token not found'], Response::HTTP_UNAUTHORIZED);
            }

        }
        return $next($request);
    }
}
