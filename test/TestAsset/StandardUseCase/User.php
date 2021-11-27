<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
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
