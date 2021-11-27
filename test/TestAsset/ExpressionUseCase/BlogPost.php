<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface
{
    /** @var mixed */
    public $title;

    /** @var mixed */
    public $shortDescription;

    /** @var mixed */
    public $content;

    /** @var mixed */
    public $author;

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

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    public function getAuthorName(): string
    {
        return $this->author ? $this->author->username : '';
    }
}
