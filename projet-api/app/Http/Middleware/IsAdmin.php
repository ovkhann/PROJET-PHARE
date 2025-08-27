<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->IsAdmin()) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        return $next($request);
    }
}
