<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\OwnershipUseCase;

use Laminas\Permissions\Acl\ProprietaryInterface;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface, ProprietaryInterface
{
    public ?User $author = null;

    public function getResourceId(): string
    {
        return 'blogPost';
    }

    public function getOwnerId(): ?int
    {
        if ($this->author === null) {
            return null;
        }

        return $this->author->getOwnerId();
    }
}
