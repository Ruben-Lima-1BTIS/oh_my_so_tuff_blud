<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (Session::get('role') !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
