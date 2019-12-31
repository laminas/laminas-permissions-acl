<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\Permissions\Acl\Assertion;

use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\ServiceManager\AbstractPluginManager;

class AssertionManager extends AbstractPluginManager
{

    protected $sharedByDefault = true;

    /**
     * Validate the plugin
     *
     * Checks that the element is an instance of AssertionInterface
     *
     * @param mixed $plugin
     *
     * @throws InvalidArgumentException
     * @return bool
     */
    public function validatePlugin($plugin)
    {
        if (! $plugin instanceof AssertionInterface) {
            throw new InvalidArgumentException(sprintf('Plugin of type %s is invalid; must implement
                Laminas\Permissions\Acl\Assertion\AssertionInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin))));
        }

        return true;
    }
}
