<?php

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class Comment implements ResourceInterface
{
    public function getResourceId()
    {
        return 'comment';
    }
}
