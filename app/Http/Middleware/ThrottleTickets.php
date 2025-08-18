<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleTickets
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many ticket submissions. Please wait {$seconds} seconds before trying again.");
        }
        
        RateLimiter::hit($key, 300); // 5 minutes
        
        return $next($request);
    }
}