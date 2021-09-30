<?php

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Acl as LaminasAcl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

class UserIsBlogPostOwnerAssertion implements AssertionInterface
{
    public $lastAssertRole = null;
    public $lastAssertResource = null;
    public $lastAssertPrivilege = null;
    public $assertReturnValue = true;

    public function assert(
        LaminasAcl $acl,
        RoleInterface $user = null,
        ResourceInterface $blogPost = null,
        $privilege = null
    ) {
        $this->lastAssertRole      = $user;
        $this->lastAssertResource  = $blogPost;
        $this->lastAssertPrivilege = $privilege;
        return $this->assertReturnValue;
    }
}
