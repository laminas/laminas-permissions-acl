<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface
{
    public function getResourceId(): string
    {
        return 'blogPost';
    }
}
