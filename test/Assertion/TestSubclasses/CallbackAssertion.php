<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion\TestSubclasses;

final class CallbackAssertion extends \Laminas\Permissions\Acl\Assertion\CallbackAssertion
{
    /**
     *
     * @return string
     */
    public function peakCallback(): callable
    {
        return $this->callback;
    }
}
