<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $allRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $allRoles[] = trim($r);
            }
        }

        if (!Auth::check() || !in_array(Auth::user()->role, $allRoles)) {
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
