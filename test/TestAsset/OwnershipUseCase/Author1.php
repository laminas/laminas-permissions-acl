<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

class Author1 extends User
{
    public ?int $id = 1;

    public string $role = 'author';
}
