<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken() && $user->currentAccessToken()->expires_at) {
            $token = $request->user()->currentAccessToken();
            if ($token && $token->expires_at->lte(now()->addMinutes(5))) {
                $request->user()->tokens()->where("id", $token->id)->delete();
                $newToken = $request->user()->createToken("API value")->plainTextToken;
                $response = $next($request);
                // return response()->json("IM WORKING BABY HERE IM THE MIDDLEMAN", 200);
                if ($response instanceof \Illuminate\Http\Response) {
                    return $response->withHeaders(['Authorization' => 'Bearer ' . $newToken]);
                } else {
                    // return $response->header('Authorization', 'Bearer ' . $newToken);
                    $response->headers->set('Authorization', 'Bearer ' . $newToken);
                    return $response;
                }
            }
        }
        return $next($request);
    }
}
