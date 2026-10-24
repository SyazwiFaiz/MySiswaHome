<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'admin' && $user->is_active, 403, 'Akses admin sahaja.');

        return $next($request);
    }
}
