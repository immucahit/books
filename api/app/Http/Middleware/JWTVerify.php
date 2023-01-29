<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class JWTVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        try{
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_KEY'), 'HS256'));
            $request->merge(['user'=>$data->user]);
            return $next($request);
        }catch(SignatureInvalidException $signatureInvalidException){
            return response()
                ->json([
                    'message' => 'Token doğrulanamadı!'
                ],401);
        }
    }
}
