<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion\TestSubclasses;

final class AssertionAggregate extends \Laminas\Permissions\Acl\Assertion\AssertionAggregate
{
    public function peakAssertions(): array
    {
        return $this->assertions;
    }

    public function peakMode(): string
    {
        return $this->mode;
    }
}
