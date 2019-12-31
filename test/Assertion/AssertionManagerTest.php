<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\ServiceManager\ServiceManager;

class AssertionManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        $this->manager = new AssertionManager(new ServiceManager);
    }

    public function testValidatePlugin()
    {
        $assertion = $this->getMockForAbstractClass('Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $this->assertNull($this->manager->validate($assertion));

        $this->setExpectedException('Laminas\ServiceManager\Exception\InvalidServiceException');

        $this->manager->validate('invalid plugin');
    }
}
