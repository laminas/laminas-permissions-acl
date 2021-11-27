<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

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
    /** @var bool */
    private $value;

    public function __construct($value)
    {
        $this->value = (bool) $value;
    }

    public function assert(Acl $acl, ?RoleInterface $role = null, ?ResourceInterface $resource = null, $privilege = null)
    {
        return $this->value;
    }
}
