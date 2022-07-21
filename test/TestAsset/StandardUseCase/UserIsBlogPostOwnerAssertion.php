<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Acl as LaminasAcl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

class UserIsBlogPostOwnerAssertion implements AssertionInterface
{
    public ?RoleInterface $lastAssertRole         = null;
    public ?ResourceInterface $lastAssertResource = null;
    public ?string $lastAssertPrivilege           = null;
    public bool $assertReturnValue                = true;

    /** @inheritDoc */
    public function assert(
        LaminasAcl $acl,
        ?RoleInterface $user = null,
        ?ResourceInterface $blogPost = null,
        $privilege = null
    ) {
        $this->lastAssertRole      = $user;
        $this->lastAssertResource  = $blogPost;
        $this->lastAssertPrivilege = $privilege;
        return $this->assertReturnValue;
    }
}
