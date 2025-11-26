<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-SUPER-SECRET-KEY');
        $expectedKey = config('app.super_secret_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization key is required'
            ], 401);
        }

        if ($apiKey !== $expectedKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid authorization key'
            ], 403);
        }

        return $next($request);
    }
}
