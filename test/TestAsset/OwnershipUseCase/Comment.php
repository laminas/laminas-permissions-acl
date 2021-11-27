<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class Comment implements ResourceInterface
{
    public function getResourceId()
    {
        return 'comment';
    }
}
