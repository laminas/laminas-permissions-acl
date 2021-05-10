<?php

namespace LaminasTest\Permissions\Acl\TestAsset\ExpressionUseCase;

use Laminas\Permissions\Acl\Resource\ResourceInterface;

class BlogPost implements ResourceInterface
{
    public $title;

    public $shortDescription;

    public $content;

    public $author;

    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    public function getResourceId()
    {
        return 'blogPost';
    }

    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    public function getAuthorName()
    {
        return $this->author ? $this->author->username : '';
    }
}
