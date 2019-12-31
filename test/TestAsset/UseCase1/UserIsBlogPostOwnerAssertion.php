<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\UseCase1;

use Laminas\Permissions\Acl as LaminasAcl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;

class UserIsBlogPostOwnerAssertion implements AssertionInterface
{

    public $lastAssertRole = null;
    public $lastAssertResource = null;
    public $lastAssertPrivilege = null;
    public $assertReturnValue = true;

    public function assert(LaminasAcl\Acl $acl, LaminasAcl\Role\RoleInterface $user = null, LaminasAcl\Resource\ResourceInterface $blogPost = null, $privilege = null)
    {
        $this->lastAssertRole      = $user;
        $this->lastAssertResource  = $blogPost;
        $this->lastAssertPrivilege = $privilege;
        return $this->assertReturnValue;
    }
}
