<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion\TestSubclasses;

final class AssertionAggregate extends \Laminas\Permissions\Acl\Assertion\AssertionAggregate
{
    public function peakAssertions(): array
    {
        return $this->assertions;
    }

    /**
     *
     * @return string
     */
    public function peakMode(): string
    {
        return $this->mode;
    }
}
