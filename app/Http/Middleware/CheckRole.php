<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
