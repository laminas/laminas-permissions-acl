<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Assertion\AssertionManager;

class AssertionManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $manager;

    public function setUp()
    {
        $this->manager = new AssertionManager();
    }

    public function testValidatePlugin()
    {
        $assertion = $this->getMockForAbstractClass('Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $this->assertTrue($this->manager->validatePlugin($assertion));

        $this->setExpectedException('Laminas\Permissions\Acl\Exception\InvalidArgumentException');

        $this->manager->validatePlugin('invalid plugin');
    }
}
