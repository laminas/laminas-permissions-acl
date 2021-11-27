<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Acl as LaminasAcl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

class UserIsBlogPostOwnerAssertion implements AssertionInterface
{
    public ?RoleInterface $lastAssertRole;
    public ?ResourceInterface $lastAssertResource;

    /** @var mixed|null */
    public $lastAssertPrivilege;
    public bool $assertReturnValue = true;

    public function assert(
        LaminasAcl $acl,
        ?RoleInterface $role = null,
        ?ResourceInterface $resource = null,
        ?string $privilege = null
    ): bool {
        $this->lastAssertRole      = $role;
        $this->lastAssertResource  = $resource;
        $this->lastAssertPrivilege = $privilege;
        return $this->assertReturnValue;
    }
}
