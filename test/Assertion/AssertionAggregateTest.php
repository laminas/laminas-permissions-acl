<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion;

use InvalidArgumentException as PHPInvalidArgumentException;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Assertion\AssertionInterface;
use Laminas\Permissions\Acl\Assertion\AssertionManager;
use Laminas\Permissions\Acl\Assertion\Exception\InvalidAssertionException;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\Permissions\Acl\Exception\RuntimeException;
use Laminas\Permissions\Acl\Resource\GenericResource;
use Laminas\Permissions\Acl\Role\GenericRole;
use LaminasTest\Permissions\Acl\Assertion\TestSubclasses\AssertionAggregate;
use PHPUnit\Framework\TestCase;

class AssertionAggregateTest extends TestCase
{
    private AssertionAggregate $assertionAggregate;

    protected function setUp(): void
    {
        $this->assertionAggregate = new AssertionAggregate();
    }

    public function testAddAssertion(): AssertionAggregate
    {
        $assertion = $this->getMockForAbstractClass(AssertionInterface::class);
        $this->assertionAggregate->addAssertion($assertion);

        $this->assertEquals([$assertion], $this->assertionAggregate->peakAssertions());

        $aggregate = $this->assertionAggregate->addAssertion('other.assertion');
        $this->assertEquals([
            $assertion,
            'other.assertion',
        ], $this->assertionAggregate->peakAssertions());

        // test fluent interface
        $this->assertSame($this->assertionAggregate, $aggregate);

        return clone $this->assertionAggregate;
    }

    public function testAddAssertions(): void
    {
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);

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

    public function testDefaultModeValue(): void
    {
        $this->assertEquals(AssertionAggregate::MODE_ALL, $this->assertionAggregate->peakMode());
    }

    /**
     * @dataProvider getDataForTestSetMode
     */
    public function testSetMode(string $mode, bool $exception): void
    {
        if ($exception) {
            $this->expectException(InvalidArgumentException::class);
            $this->assertionAggregate->setMode($mode);
        } else {
            $this->assertionAggregate->setMode($mode);
            $this->assertEquals($mode, $this->assertionAggregate->peakMode());
        }
    }

    /** @return array<array-key, array{0: string, 1: bool}> */
    public static function getDataForTestSetMode(): array
    {
        return [
            [
                AssertionAggregate::MODE_ALL,
                false,
            ],
            [
                AssertionAggregate::MODE_AT_LEAST_ONE,
                false,
            ],
            [
                'invalid mode',
                true,
            ],
        ];
    }

    public function testManagerAccessors(): void
    {
        $manager = $this->getMockBuilder(AssertionManager::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $aggregate = $this->assertionAggregate->setAssertionManager($manager);
        $this->assertEquals($manager, $this->assertionAggregate->getAssertionManager());
        $this->assertSame($this->assertionAggregate, $aggregate);
    }

    public function testCallingAssertWillFetchAssertionFromManager(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock();

        $assertion = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertion->expects($this->once())
            ->method('assert')
            ->will($this->returnValue(true));

        $manager = $this->getMockBuilder(AssertionManager::class)
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

    public function testAssertThrowsAnExceptionWhenReferingToNonExistentAssertion(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock();

        $manager = $this->getMockBuilder(AssertionManager::class)
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

    public function testAssertWithModeAll(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock();

        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);

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

    public function testAssertWithModeAtLeastOne(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->getMock();

        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);

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

    public function testDoesNotAssertWithModeAll(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock();

        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);

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

    public function testDoesNotAssertWithModeAtLeastOne(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock();

        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);
        $assertions[] = $this->getMockForAbstractClass(AssertionInterface::class);

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

    public function testAssertThrowsAnExceptionWhenNoAssertionIsAggregated(): void
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->getMock();

        $role = $this->getMockBuilder(GenericRole::class)
            ->setConstructorArgs(['test.role'])
            ->setMethods(['assert'])
            ->getMock();

        $resource = $this->getMockBuilder(GenericResource::class)
            ->setConstructorArgs(['test.resource'])
            ->setMethods(['assert'])
            ->getMock();

        $this->expectException(RuntimeException::class);

        $this->assertionAggregate->assert($acl, $role, $resource, 'privilege');
    }
}
