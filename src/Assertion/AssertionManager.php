<?php

declare(strict_types=1);

namespace Laminas\Permissions\Acl\Assertion;

use Laminas\ServiceManager\AbstractSingleInstancePluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;

use function gettype;
use function is_object;
use function sprintf;

/** @extends AbstractSingleInstancePluginManager<AssertionInterface> */
class AssertionManager extends AbstractSingleInstancePluginManager
{
    /** @var class-string<AssertionInterface> */
    protected string $instanceOf = AssertionInterface::class;

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Validates against `$instanceOf`.
     *
     * @throws InvalidServiceException
     * @psalm-assert AssertionInterface $instance
     */
    public function validate(mixed $instance): void
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                self::class,
                $this->instanceOf,
                is_object($instance) ? $instance::class : gettype($instance)
            ));
        }
    }
}
