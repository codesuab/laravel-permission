<?php

namespace Codesuab\Permission\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $permissions = collect($permissions)
            ->flatMap(fn ($item) => explode(',', $item))
            ->filter()
            ->values();

        foreach ($permissions as $permission) {
            if (app('permission')->check($request->user(), $permission)) {
                return $next($request);
            }
        }

        abort($request->user() ? 403 : 401);
    }
}
