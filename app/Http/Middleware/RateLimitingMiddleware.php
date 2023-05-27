<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitingMiddleware
{
    static $limit;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $executed = $this->limiter("test", 4, 60);
        if (!$executed) {
            return response()->json([
                "message" => "too many requests your account has been hold for one minute"
            ], 429);
        }
        return $next($request);
    }

    public function limiter($key, $requestCount, $seconds)
    {
        return RateLimiter::attempt($key, $requestCount, function () {
        }, $seconds);
    }
}
