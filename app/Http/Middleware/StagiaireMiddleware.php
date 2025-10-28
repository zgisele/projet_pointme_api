<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class StagiaireMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        //  try {
        //     $user = JWTAuth::parseToken()->authenticate();

        //     if ($user && $user->role === 'stagiaire') {
        //         return $next($request);
        //     }

        //     return response()->json(['message' => 'Accès refusé : réservé aux stagiaires.'], 403);
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Token invalide ou expiré.'], 401);
        // }

         try {
            $user=JWTAuth::parseToken()->authenticate();
            //code...
            if($user && $user-> role === 'stagiaire'){
            return $next($request);
            }
            return response()->json(['message'=>'Acces refuse:reserve aux stagiaire'],403);
        } 
        catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token expiré'], 401);

        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token invalide'], 401);

        } catch (JWTException $e) {
            return response()->json(['message' => 'Token absent'], 401);
        }

        // return $next($request);
        // if(auth()->check() && auth()->user()-> role === 'stagiaire'){
        //     return $next($request);
        // }
        // return response()->json(['message'=>'Acces refuse:reserve aux stagiaire'],403);

    }
}
