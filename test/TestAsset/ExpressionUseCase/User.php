<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface
{
    public ?string $username = null;
    public string $role      = 'guest';
    public ?int $age         = null;

    /** @param array{username?: string, age?: int, role?: string} $data */
    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    public function getRoleId(): string
    {
        return $this->role;
    }

    public function isAdult(): bool
    {
        return $this->age >= 18;
    }
}
