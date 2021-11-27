<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class MockAssertion implements Acl\Assertion\AssertionInterface
{
    protected bool $returnValue;

    public function __construct(bool $returnValue)
    {
        $this->returnValue = $returnValue;
    }

    public function assert(
        Acl\Acl $acl,
        ?Acl\Role\RoleInterface $role = null,
        ?Acl\Resource\ResourceInterface $resource = null,
        ?string $privilege = null
    ): bool {
        return $this->returnValue;
    }
}
