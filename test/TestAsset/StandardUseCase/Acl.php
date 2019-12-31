<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Acl as BaseAcl;
use Laminas\Permissions\Acl\Resource\GenericResource;
use Laminas\Permissions\Acl\Role\GenericRole;

class Acl extends BaseAcl
{
    public $customAssertion = null;

    public function __construct()
    {
        $this->customAssertion = new UserIsBlogPostOwnerAssertion();

        $this->addRole(new GenericRole('guest'));
        $this->addRole(new GenericRole('contributor'), 'guest');
        $this->addRole(new GenericRole('publisher'), 'contributor');
        $this->addRole(new GenericRole('admin'));

        $this->addResource(new GenericResource('blogPost'));

        $this->allow('guest', 'blogPost', 'view');
        $this->allow('contributor', 'blogPost', 'contribute');
        $this->allow('contributor', 'blogPost', 'modify', $this->customAssertion);
        $this->allow('publisher', 'blogPost', 'publish');
    }
}
