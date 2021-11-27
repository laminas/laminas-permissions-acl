<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;
use LaminasTest\Permissions\Acl\Assertion\TestSubclasses\CallbackAssertion;
use PHPUnit\Framework\TestCase;

class CallbackAssertionTest extends TestCase
{
    /**
     * Ensures constructor throws InvalidArgumentException if not callable is provided
     */
    public function testConstructorThrowsExceptionIfNotCallable()
    {
        $this->expectException(
            InvalidArgumentException::class,
            'Invalid callback provided; not callable'
        );
        new CallbackAssertion('I am not callable!');
    }

    /**
     * Ensures callback is set in object
     */
    public function testCallbackIsSet()
    {
        $callback = function () {
        };
        $assert   = new CallbackAssertion($callback);
        $this->assertSame($callback, $assert->peakCallback());
    }

    /**
     * Ensures assert method provides callback with its arguments
     */
    public function testAssertMethodPassArgsToCallback()
    {
        $acl    = new Acl\Acl();
        $that   = $this;
        $assert = new CallbackAssertion(
            function ($aclArg, $roleArg, $resourceArg, $privilegeArg) use ($that, $acl) {
                $that->assertSame($acl, $aclArg);
                $that->assertInstanceOf(RoleInterface::class, $roleArg);
                $that->assertEquals('guest', $roleArg->getRoleId());
                $that->assertInstanceOf(ResourceInterface::class, $resourceArg);
                $that->assertEquals('area1', $resourceArg->getResourceId());
                $that->assertEquals('somePrivilege', $privilegeArg);
                return false;
            }
        );

        $acl->addRole('guest');
        $acl->addResource('area1');
        $acl->allow(null, null, null, $assert);
        $this->assertFalse($acl->isAllowed('guest', 'area1', 'somePrivilege'));
    }

    /**
     * Ensures assert method returns callback's function value
     */
    public function testAssertMethod()
    {
        $acl        = new Acl\Acl();
        $roleGuest  = new Acl\Role\GenericRole('guest');
        $assertMock = function ($value) {
            return function ($aclArg, $roleArg, $resourceArg, $privilegeArg) use ($value) {
                return $value;
            };
        };
        $acl->addRole($roleGuest);
        $acl->allow($roleGuest, null, 'somePrivilege', new CallbackAssertion($assertMock(true)));
        $this->assertTrue($acl->isAllowed($roleGuest, null, 'somePrivilege'));
        $acl->allow($roleGuest, null, 'somePrivilege', new CallbackAssertion($assertMock(false)));
        $this->assertFalse($acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }
}
