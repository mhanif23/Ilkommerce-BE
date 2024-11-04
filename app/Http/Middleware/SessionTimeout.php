<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        $timeout = config('session.lifetime') * 60; // Waktu dalam detik
        $lastActivity = session('lastActivityTime');
        $currentTime = time();

        if ($lastActivity && ($currentTime - $lastActivity) > $timeout) {
            Auth::logout();
            session()->flush();
            return response()->json(['message' => 'Your session has expired. Please log in again.'], 401);
        }

        session(['lastActivityTime' => $currentTime]);

        return $next($request);
    }
}
