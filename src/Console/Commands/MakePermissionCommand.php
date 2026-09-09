<?php

namespace Codesuab\Permission\Console\Commands;

use Illuminate\Console\Command;
use Codesuab\Permission\Models\Permission;

class MakePermissionCommand extends Command
{
    protected $signature = 'permission:make {resources*} {--crud}';
    protected $description = 'Create permissions';

    public function handle(): int
    {
        foreach ($this->argument('resources') as $resource) {
            $permissions = $this->option('crud')
                ? Permission::crud($resource)
                : [$resource];

            foreach ($permissions as $permission) {
                Permission::ensure($permission);
                $this->line("✓ $permission");
            }
        }

        return self::SUCCESS;
    }
}
