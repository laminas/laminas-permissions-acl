<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface, ProprietaryInterface
{
    public $author;

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
