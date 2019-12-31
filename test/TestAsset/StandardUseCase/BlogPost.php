<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

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
