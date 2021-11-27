<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\Acl as BaseAcl;
use Laminas\Permissions\Acl\Assertion\OwnershipAssertion;

class Acl extends BaseAcl
{
    public function __construct()
    {
        $this->addRole('guest');
        $this->addRole('member', 'guest');
        $this->addRole('author', 'member');
        $this->addRole('admin');

        $this->addResource(new BlogPost());
        $this->addResource(new Comment());

        $this->allow('guest', 'blogPost', 'view');
        $this->allow('guest', 'comment', ['view', 'submit']);
        $this->allow('author', 'blogPost', 'write');
        $this->allow('author', 'blogPost', 'edit', new OwnershipAssertion());
        $this->allow('admin');
    }
}
