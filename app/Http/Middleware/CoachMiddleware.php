<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoachMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        try {
            $user=JWTAuth::parseToken()->authenticate();
            //code...
            if($user && user()-> role === 'coache'){
            return $next($request);
            }
            return response()->json(['message'=>'Acces refuse:reserve aux coachs'],403);
        } catch (\Throwable $th) {
            //throw $th;
             return response()->json(['message'=>'Token invalide ou expire'],401);
        }
        // if(auth()->check() && auth()->user()-> role === 'coache'){
        //     return $next($request);
        // }
        // return response()->json(['message'=>'Acces refuse:reserve aux coachs'],403);

    }
}
