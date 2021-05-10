<?php

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
