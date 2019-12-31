<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\UseCase1;

use Laminas\Permissions\Acl\Resource;

class BlogPost implements Resource\ResourceInterface
{
    public $owner = null;
    public function getResourceId()
    {
        return 'blogPost';
    }
}
