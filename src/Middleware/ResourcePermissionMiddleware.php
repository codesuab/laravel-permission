<?php

namespace Codesuab\Permission\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourcePermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $method = strtolower($request->route()?->getActionMethod() ?? '');
        $map = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];

        if (!isset($map[$method])) {
            return $next($request);
        }

        $permission = "$resource.{$map[$method]}";

        if (app('permission')->check($request->user(), $permission)) {
            return $next($request);
        }

        abort($request->user() ? 403 : 401);
    }
}
