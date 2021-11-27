<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace Laminas\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;

use function get_class;
use function gettype;
use function is_object;
use function sprintf;

class AssertionManager extends AbstractPluginManager
{
    /** @var mixed */
    protected $instanceOf = AssertionInterface::class;

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Validates against `$instanceOf`.
     *
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                static::class,
                $this->instanceOf,
                // phpcs:disable SlevomatCodingStandard.Classes.ModernClassNameReference
                is_object($instance) ? get_class($instance) : gettype($instance)
                // phpcs:enable
            ));
        }
    }

    /**
     * Validate the plugin is of the expected type (v2).
     *
     * Proxies to `validate()`.
     *
     * @deprecated Please use {@see AssertionManager::validate()} instead.
     *
     * @param mixed $instance
     * @throws InvalidArgumentException
     */
    public function validatePlugin($instance)
    {
        try {
            $this->validate($instance);
        } catch (InvalidServiceException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
