<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StagiaireMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        if(auth()->check() && auth()->user()-> role === 'stagiaire'){
            return $next($request);
        }
        return response()->json(['message'=>'Acces refuse:reserve aux stagiaire'],403);

    }
}
