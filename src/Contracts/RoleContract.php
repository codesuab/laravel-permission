<?php

namespace Codesuab\Permission\Contracts;

interface RoleContract
{
    public function hasPermission(string $permission): bool;
}
