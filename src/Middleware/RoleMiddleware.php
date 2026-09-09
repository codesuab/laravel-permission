<?php

namespace Codesuab\Permission\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $roles = collect($roles)
            ->flatMap(fn ($item) => explode(',', $item))
            ->filter()
            ->values()
            ->all();

        if (app('permission')->hasAnyRole($request->user(), $roles)) {
            return $next($request);
        }

        abort($request->user() ? 403 : 401);
    }
}
