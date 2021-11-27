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
    public $lastAssertRole;
    public $lastAssertResource;
    public $lastAssertPrivilege;
    public $assertReturnValue = true;

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
