<?php

namespace Codesuab\Permission\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Codesuab\Permission\Models\Permission;

class SyncRoutesCommand extends Command
{
    protected $signature = 'permission:sync-routes';
    protected $description = 'Generate CRUD permissions from resource controllers';

    public function handle(): int
    {
        $map = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];

        foreach (Route::getRoutes() as $route) {
            $method = strtolower($route->getActionMethod());

            if (!isset($map[$method])) {
                continue;
            }

            $action = $route->getActionName();

            if (!str_contains($action, '@')) {
                continue;
            }

            [$controller] = explode('@', $action, 2);

            $short = str($controller)->afterLast('\\')->beforeLast('Controller')->toString();

            if (!$short) {
                continue;
            }

            $resource = str($short)->kebab()->toString();
            $permission = "$resource.{$map[$method]}";

            Permission::ensure($permission);

            $this->line("✓ $permission");
        }

        return self::SUCCESS;
    }
}
