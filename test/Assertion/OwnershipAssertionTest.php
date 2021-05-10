<?php

namespace LaminasTest\Permissions\Acl\Assertion;

use LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;
use PHPUnit\Framework\TestCase;

class OwnershipAssertionTest extends TestCase
{
    public function testAssertPassesIfRoleIsNotProprietary()
    {
        $acl = new OwnershipUseCase\Acl();

        $this->assertTrue($acl->isAllowed('guest', 'blogPost', 'view'));
        $this->assertFalse($acl->isAllowed('guest', 'blogPost', 'delete'));
    }

    public function testAssertPassesIfResourceIsNotProprietary()
    {
        $acl = new OwnershipUseCase\Acl();

        $author = new OwnershipUseCase\Author1();

        $this->assertTrue($acl->isAllowed($author, 'comment', 'view'));
        $this->assertFalse($acl->isAllowed($author, 'comment', 'delete'));
    }

    public function testAssertPassesIfResourceDoesNotHaveOwner()
    {
        $acl = new OwnershipUseCase\Acl();

        $author = new OwnershipUseCase\Author1();

        $blogPost = new OwnershipUseCase\BlogPost();
        $blogPost->author = null;

        $this->assertTrue($acl->isAllowed($author, 'blogPost', 'write'));
        $this->assertTrue($acl->isAllowed($author, $blogPost, 'edit'));
    }

    public function testAssertFailsIfResourceHasOwnerOtherThanRoleOwner()
    {
        $acl = new OwnershipUseCase\Acl();

        $author1 = new OwnershipUseCase\Author1();
        $author2 = new OwnershipUseCase\Author2();

        $blogPost = new OwnershipUseCase\BlogPost();
        $blogPost->author = $author1;

        $this->assertTrue($acl->isAllowed($author2, 'blogPost', 'write'));
        $this->assertFalse($acl->isAllowed($author2, $blogPost, 'edit'));
    }
}
