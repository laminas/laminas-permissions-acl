<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface
{
    public $username;

    public $role = 'guest';

    public $age;

    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    public function getRoleId()
    {
        return $this->role;
    }

    public function isAdult()
    {
        return $this->age >= 18;
    }
}
