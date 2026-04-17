<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized – Login required.');
        }
        $validRoleNames = Role::pluck('name')->toArray();

        if (empty($validRoleNames)) {
            abort(403, 'No valid roles defined in the system.');
        }
        if (!auth()->user()->hasAnyRole($validRoleNames)) {
            abort(403, 'You do not have a valid role assigned.');
        }

        return $next($request);
    }
}
