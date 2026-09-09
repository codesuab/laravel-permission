<?php

namespace Codesuab\Permission\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAllMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $roles = collect($roles)
            ->flatMap(fn ($item) => explode(',', $item))
            ->filter()
            ->values()
            ->all();

        if (app('permission')->hasAllRoles($request->user(), $roles)) {
            return $next($request);
        }

        abort($request->user() ? 403 : 401);
    }
}
