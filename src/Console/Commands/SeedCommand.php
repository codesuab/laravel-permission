<?php

namespace Codesuab\Permission\Console\Commands;

use Illuminate\Console\Command;
use Codesuab\Permission\Models\Permission;
use Codesuab\Permission\Models\Role;

class SeedCommand extends Command
{
    protected $signature = 'permission:seed';
    protected $description = 'Create the default super-admin role';

    public function handle(): int
    {
        Permission::ensure('*');

        $role = Role::createRole(
            config('permission.super_admin.role', 'super-admin')
        );

        $role->update([
            'is_system' => true,
            'is_active' => true,
        ]);

        $role->allow('*');

        $this->info('Super admin role ready.');

        return self::SUCCESS;
    }
}
