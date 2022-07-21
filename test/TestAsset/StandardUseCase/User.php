<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface
{
    public string $role = 'guest';

    public function getRoleId(): string
    {
        return $this->role;
    }
}
