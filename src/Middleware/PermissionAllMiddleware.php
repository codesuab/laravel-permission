<?php

namespace Codesuab\Permission\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionAllMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $permissions = collect($permissions)
            ->flatMap(fn ($item) => explode(',', $item))
            ->filter()
            ->values()
            ->all();

        if (app('permission')->all($request->user(), $permissions)) {
            return $next($request);
        }

        abort($request->user() ? 403 : 401);
    }
}
