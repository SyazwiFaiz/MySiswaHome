<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureLandlord
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        abort_unless($user && in_array($user->role, ['landlord', 'admin'], true) && $user->is_active, 403, 'Akses tuan rumah sahaja.');

        return $next($request);
    }
}
