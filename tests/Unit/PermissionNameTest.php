<?php

namespace Codesuab\Permission\Tests\Unit;

use PHPUnit\Framework\TestCase;

class PermissionNameTest extends TestCase
{
    public function test_crud_names_are_predictable(): void
    {
        $resource = 'users';

        $this->assertSame(
            [
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
            ],
            [
                "$resource.view",
                "$resource.create",
                "$resource.update",
                "$resource.delete",
            ]
        );
    }
}
