<?php

namespace LaminasTest\Permissions\Acl\Assertion;

use InvalidArgumentException as PHPInvalidArgumentException;
use Laminas\Permissions\Acl\Acl;
use LaminasTest\Permissions\Acl\Assertion\TestSubclasses\AssertionAggregate;
use Laminas\Permissions\Acl\Assertion\Exception\InvalidAssertionException;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\Permissions\Acl\Exception\RuntimeException;
use Laminas\Permissions\Acl\Resource\GenericResource;
use Laminas\Permissions\Acl\Role\GenericRole;
use PHPUnit\Framework\TestCase;

class AssertionAggregateTest extends TestCase
{
    protected $assertionAggregate;

    protected function setUp(): void
    {
        $this->assertionAggregate = new AssertionAggregate();
    }

    public function testAddAssertion()
    {
        $assertion = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $this->assertionAggregate->addAssertion($assertion);

        $this->assertEquals([$assertion], $this->assertionAggregate->peakAssertions());

        $aggregate = $this->assertionAggregate->addAssertion('other.assertion');
        $this->assertEquals([
            $assertion,
            'other.assertion'
        ], $this->assertionAggregate->peakAssertions());

        // test fluent interface
        $this->assertSame($this->assertionAggregate, $aggregate);

        return clone $this->assertionAggregate;
    }

    public function testAddAssertions()
    {
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $aggregate = $this->assertionAggregate->addAssertions($assertions);

        $this->assertEquals($assertions, $this->assertionAggregate->peakAssertions());

        // test fluent interface
        $this->assertSame($this->assertionAggregate, $aggregate);
    }

    /**
     * @depends testAddAssertion
     */
    public function testClearAssertions(AssertionAggregate $assertionAggregate)
    {
        $this->assertCount(2, $assertionAggregate->peakAssertions());

        $aggregate = $assertionAggregate->clearAssertions();

        $this->assertEmpty($assertionAggregate->peakAssertions());

        // test fluent interface
        $this->assertSame($assertionAggregate, $aggregate);
    }

    public function testDefaultModeValue()
    {
        $this->assertEquals(AssertionAggregate::MODE_ALL, $this->assertionAggregate->peakMode());
    }

    /**
     * @dataProvider getDataForTestSetMode
     */
    public function testSetMode($mode, $exception = false)
    {
        if ($exception) {
            $this->expectException(InvalidArgumentException::class);
            $this->assertionAggregate->setMode($mode);
        } else {
            $this->assertionAggregate->setMode($mode);
            $this->assertEquals($mode, $this->assertionAggregate->peakMode());
        }
    }

    public static function getDataForTestSetMode()
    {
        return [
            [
                AssertionAggregate::MODE_ALL
            ],
            [
                AssertionAggregate::MODE_AT_LEAST_ONE
            ],
            [
                'invalid mode',
                true
            ]
        ];
    }

    public function testManagerAccessors()
    {
        $manager = $this->getMockBuilder('Laminas\Permissions\Acl\Assertion\AssertionManager')
                        ->disableOriginalConstructor()
                        ->getMock();

        $aggregate = $this->assertionAggregate->setAssertionManager($manager);
        $this->assertEquals($manager, $this->assertionAggregate->getAssertionManager());
        $this->assertSame($this->assertionAggregate, $aggregate);
    }

    public function testCallingAssertWillFetchAssertionFromManager()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock()
            ;

        $assertion = $this->getMockForAbstractClass('Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertion->expects($this->once())
            ->method('assert')
            ->will($this->returnValue(true));

        $manager = $this->getMockBuilder('Laminas\Permissions\Acl\Assertion\AssertionManager')
                        ->disableOriginalConstructor()
                        ->getMock();

        $manager->expects($this->once())
            ->method('get')
            ->with('assertion')
            ->will($this->returnValue($assertion));

        $this->assertionAggregate->setAssertionManager($manager);
        $this->assertionAggregate->addAssertion('assertion');

        $this->assertTrue($this->assertionAggregate->assert($acl, $role, $resource, 'privilege'));
    }

    public function testAssertThrowsAnExceptionWhenReferingToNonExistentAssertion()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock()
            ;

        $manager = $this->getMockBuilder('Laminas\Permissions\Acl\Assertion\AssertionManager')
                        ->disableOriginalConstructor()
                        ->getMock();

        $manager->expects($this->once())
            ->method('get')
            ->with('assertion')
            ->will($this->throwException(new PHPInvalidArgumentException()));

        $this->assertionAggregate->setAssertionManager($manager);

        $this->expectException(InvalidAssertionException::class);
        $this->assertionAggregate->addAssertion('assertion');
        $this->assertionAggregate->assert($acl, $role, $resource, 'privilege');
    }

    public function testAssertWithModeAll()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock()
            ;

        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $assertions[0]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));
        $assertions[1]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));
        $assertions[2]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));

        foreach ($assertions as $assertion) {
            $this->assertionAggregate->addAssertion($assertion);
        }

        $this->assertTrue($this->assertionAggregate->assert($acl, $role, $resource, 'privilege'));
    }

    public function testAssertWithModeAtLeastOne()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock()
            ;

        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $assertions[0]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));
        $assertions[1]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));
        $assertions[2]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));

        foreach ($assertions as $assertion) {
            $this->assertionAggregate->addAssertion($assertion);
        }

        $this->assertionAggregate->setMode(AssertionAggregate::MODE_AT_LEAST_ONE);
        $this->assertTrue($this->assertionAggregate->assert($acl, $role, $resource, 'privilege'));
    }

    public function testDoesNotAssertWithModeAll()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $assertions[0]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));
        $assertions[1]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(true));
        $assertions[2]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));

        foreach ($assertions as $assertion) {
            $this->assertionAggregate->addAssertion($assertion);
        }

        $this->assertFalse($this->assertionAggregate->assert($acl, $role, $resource, 'privilege'));
    }

    public function testDoesNotAssertWithModeAtLeastOne()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');
        $assertions[] = $this->getMockForAbstractClass('\Laminas\Permissions\Acl\Assertion\AssertionInterface');

        $assertions[0]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));
        $assertions[1]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));
        $assertions[2]->expects($this->once())
            ->method('assert')
            ->with($acl, $role, $resource, 'privilege')
            ->will($this->returnValue(false));

        foreach ($assertions as $assertion) {
            $this->assertionAggregate->addAssertion($assertion);
        }

        $this->assertionAggregate->setMode(AssertionAggregate::MODE_AT_LEAST_ONE);
        $this->assertFalse($this->assertionAggregate->assert($acl, $role, $resource, 'privilege'));
    }

    public function testAssertThrowsAnExceptionWhenNoAssertionIsAggregated()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock()
            ;

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock()
            ;

        $this->expectException(RuntimeException::class);

        $this->assertionAggregate->assert($acl, $role, $resource, 'privilege');
    }
}
