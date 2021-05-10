<?php

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class MockAssertion implements Acl\Assertion\AssertionInterface
{
    protected $returnValue;

    public function __construct($returnValue)
    {
        $this->returnValue = (bool) $returnValue;
    }

    public function assert(
        Acl\Acl $acl,
        Acl\Role\RoleInterface $role = null,
        Acl\Resource\ResourceInterface $resource = null,
        $privilege = null
    ) {

        return $this->returnValue;
    }
}
