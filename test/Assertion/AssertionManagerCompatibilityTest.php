<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use PHPUnit\Framework\TestCase;
use Throwable;

class AssertionManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager(): AssertionManager
    {
        return new AssertionManager(new ServiceManager());
    }

    /** @return class-string<Throwable> */
    protected function getV2InvalidPluginException(): string
    {
        return InvalidArgumentException::class;
    }

    /** @return class-string */
    protected function getInstanceOf(): string
    {
        return AssertionInterface::class;
    }

    public function testPluginAliasesResolve(): void
    {
        $this->markTestSkipped(
            'No aliases yet defined; remove implementation if/when aliases are added to AssertionManager'
        );
    }
}
