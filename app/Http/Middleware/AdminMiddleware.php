<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized – Login required.');
        }

        // Optional: allow only specific roles
        if (
            !auth()
                ->user()
                ->hasAnyRole(['admin', 'user'])
        ) {
            abort(403, 'You do not have permission.');
        }

        return $next($request);
    }
}
