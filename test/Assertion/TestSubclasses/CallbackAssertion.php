<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion\TestSubclasses;

final class CallbackAssertion extends \Laminas\Permissions\Acl\Assertion\CallbackAssertion
{
    public function peakCallback(): callable
    {
        return $this->callback;
    }
}
