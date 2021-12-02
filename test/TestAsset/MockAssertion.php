<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

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
