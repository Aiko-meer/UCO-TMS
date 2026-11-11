<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage in routes:  ->middleware('role:content_team,content_admin')
 *
 * The listed roles are allowed, and "admin" / "superadmin" are always allowed.
 * Everyone else gets a 403 (Forbidden) page.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userType = $request->user()?->user_type;

        $allowed = array_merge($roles, ['admin', 'superadmin']);

        if (! $userType || ! in_array($userType, $allowed, true)) {
            abort(403, 'You do not have permission to open this page.');
        }

        return $next($request);
    }
}
