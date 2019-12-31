<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface
{
    public $role = 'guest';

    public function getRoleId()
    {
        return $this->role;
    }
}
