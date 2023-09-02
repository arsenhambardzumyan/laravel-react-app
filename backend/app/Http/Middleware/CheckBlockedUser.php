<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBlockedUser
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->blocked) {
            return response()->json(['message' => 'Your account is blocked.'], 403);
        }

        return $next($request);
    }
}
