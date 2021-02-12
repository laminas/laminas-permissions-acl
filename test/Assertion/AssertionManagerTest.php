<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class AssertionManagerTest extends TestCase
{
    protected $manager;

    protected function setUp(): void
    {
        $this->manager = new AssertionManager(new ServiceManager);
    }

    public function testValidatePlugin()
    {
        $assertion = $this->getMockForAbstractClass(AssertionInterface::class);

        $this->assertNull($this->manager->validate($assertion));

        $this->expectException(InvalidServiceException::class);

        $this->manager->validate('invalid plugin');
    }
}
