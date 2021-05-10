<?php

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;

class AssertionLaminas7973 implements AssertionInterface
{
    public function assert(
        Acl\Acl $acl,
        Acl\Role\RoleInterface $role = null,
        Acl\Resource\ResourceInterface $resource = null,
        $privilege = null
    ) {
        if ($privilege != 'privilege') {
            return false;
        }

        return true;
    }
}
