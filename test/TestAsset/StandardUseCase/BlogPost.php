<?php

namespace LaminasTest\Permissions\Acl\TestAsset\StandardUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface
{
    public $owner = null;

    public function getResourceId()
    {
        return 'blogPost';
    }
}
