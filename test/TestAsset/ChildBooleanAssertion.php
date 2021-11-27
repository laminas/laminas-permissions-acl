<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * @see https://github.com/laminas/laminas-permissions-acl/issues/2
 */
class ChildBooleanAssertion implements AssertionInterface
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function assert(
        Acl $acl,
        ?RoleInterface $role = null,
        ?ResourceInterface $resource = null,
        ?string $privilege = null
    ): bool {
        return $this->value;
    }
}
