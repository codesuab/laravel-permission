<?php

namespace Codesuab\Permission\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Codesuab\Permission\Authorization\PermissionManager;

class WildcardTest extends TestCase
{
    public function test_wildcards_match(): void
    {
        $manager = new PermissionManager();

        $ref = new \ReflectionMethod($manager, 'matches');
        $ref->setAccessible(true);

        $this->assertTrue($ref->invoke($manager, '*', 'users.delete'));
        $this->assertTrue($ref->invoke($manager, 'users.*', 'users.delete'));
        $this->assertTrue($ref->invoke($manager, 'users.*', 'users.create'));
        $this->assertFalse($ref->invoke($manager, 'users.*', 'posts.create'));
    }
}
