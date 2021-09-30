<?php

namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use PHPUnit\Framework\TestCase;

class AssertionManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    public function setExpectedException($exception, $message = '', $code = null)
    {
        $this->expectException($exception, $message, $code);
    }

    protected function getPluginManager()
    {
        return new AssertionManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException()
    {
        return InvalidArgumentException::class;
    }

    protected function getInstanceOf()
    {
        return AssertionInterface::class;
    }

    public function testPluginAliasesResolve()
    {
        $this->markTestSkipped(
            'No aliases yet defined; remove implementation if/when aliases are added to AssertionManager'
        );
    }
}
