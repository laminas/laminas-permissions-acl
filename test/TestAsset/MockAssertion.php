<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class MockAssertion implements Acl\Assertion\AssertionInterface
{
    public function __construct(protected bool $returnValue)
    {
    }

    /** @inheritDoc */
    public function assert(
        Acl\Acl $acl,
        ?Acl\Role\RoleInterface $role = null,
        ?Acl\Resource\ResourceInterface $resource = null,
        $privilege = null
    ) {
        return $this->returnValue;
    }
}
