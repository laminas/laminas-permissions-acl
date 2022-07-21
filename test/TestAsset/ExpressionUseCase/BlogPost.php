<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * phpcs:disable Generic.Files.LineLength.TooLong
 */
class BlogPost implements ResourceInterface
{
    public ?string $title            = null;
    public ?string $shortDescription = null;
    public ?string $content          = null;
    public ?User $author             = null;

    /**
     * @param array{title?: string|null, shortDescription?: string|null, content?: string|null, author?: User|null} $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    public function getResourceId(): string
    {
        return 'blogPost';
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getAuthorName(): ?string
    {
        return $this->author ? $this->author->username : '';
    }
}
