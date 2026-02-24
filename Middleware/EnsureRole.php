<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Usage: ->middleware(['auth', 'role:OWNER']) or role:ADMIN
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) abort(401);
        if ($user->role !== $role) abort(403, 'Forbidden');
        return $next($request);
    }
}
