<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

class Author2 extends User
{
    public ?int $id = 2;

    public string $role = 'author';
}
