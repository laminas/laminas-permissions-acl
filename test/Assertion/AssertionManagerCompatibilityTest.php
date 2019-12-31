<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use PHPUnit_Framework_TestCase as TestCase;

class AssertionManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

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
