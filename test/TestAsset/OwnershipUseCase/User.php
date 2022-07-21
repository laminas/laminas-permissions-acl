<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface, ProprietaryInterface
{
    public ?int $id     = null;
    public string $role = 'guest';

    public function getRoleId(): string
    {
        return $this->role;
    }

    public function getOwnerId(): ?int
    {
        return $this->id;
    }
}
