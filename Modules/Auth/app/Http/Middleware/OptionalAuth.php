<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        // Set the user if authentication was successful
        if ($user) {
            Auth::setUser(
                Auth::guard('sanctum')->user()
            );
        }

        return $next($request);
    }
}
