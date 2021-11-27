<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

class Author2 extends User
{
    public $id = 2;

    public $role = 'author';
}
