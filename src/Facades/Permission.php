<?php

namespace Codesuab\Permission\Facades;

use Illuminate\Support\Facades\Facade;

class Permission extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'permission';
    }
}
