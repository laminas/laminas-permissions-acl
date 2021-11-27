<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface
{
    public $owner;

    public function getResourceId()
    {
        return 'blogPost';
    }
}
