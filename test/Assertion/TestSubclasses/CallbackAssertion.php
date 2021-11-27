<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion\TestSubclasses;

final class CallbackAssertion extends \Laminas\Permissions\Acl\Assertion\CallbackAssertion
{
    public function peakCallback(): callable
    {
        return $this->callback;
    }
}
