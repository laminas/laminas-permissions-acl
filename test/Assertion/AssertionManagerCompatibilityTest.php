<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

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

    // This method deprecated at PHPUnit 5.*, removed at 6.*.
    // This code does nothing actually but shows errors by phpcs.
    // phpcs:disable
    public function setExpectedException($exception, $message = '', $code = null)
    {
        $this->expectException($exception, $message, $code);
    }
    // phpcs:enable

    protected function getPluginManager(): AssertionManager
    {
        return new AssertionManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException(): string
    {
        return InvalidArgumentException::class;
    }

    protected function getInstanceOf(): string
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
