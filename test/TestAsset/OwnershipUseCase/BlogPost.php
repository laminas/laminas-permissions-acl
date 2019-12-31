<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface, ProprietaryInterface
{
    public $author = null;

    public function getResourceId()
    {
        return 'blogPost';
    }

    public function getOwnerId()
    {
        if ($this->author === null) {
            return null;
        }

        return $this->author->getOwnerId();
    }
}
