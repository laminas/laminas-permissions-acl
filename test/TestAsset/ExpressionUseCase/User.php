<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Role\RoleInterface;

class User implements RoleInterface
{
    /** @var mixed */
    public $username;

    public string $role = 'guest';

    /** @var mixed */
    public $age;

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
