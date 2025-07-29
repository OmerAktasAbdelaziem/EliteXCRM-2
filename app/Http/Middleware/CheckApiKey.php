<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY') ;

        // ✅ check if key exists in request
        if (!$apiKey) {
            return response()->json(['message' => 'API Key is required'], 401);
        }

        // ✅ check key 
        $validApiKeys = [
            config('services.myapi.key1'),
            config('services.myapi.key2'),
        ];

        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json(['message' => 'Invalid API Key'], 403);
        }

        // ✅ Rate limiting 60 per minute
        $key = 'api-key:' . $apiKey;

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json(['message' => 'Too many requests'], 429);
        }

        RateLimiter::hit($key, 60); 
        return $next($request);
    }
}
