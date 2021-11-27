<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace Laminas\Permissions\Acl\Assertion;

use Exception as PHPException;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\Permissions\Acl\Exception\RuntimeException;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Laminas\Permissions\Acl\Role\RoleInterface;

use function class_exists;

class AssertionAggregate implements AssertionInterface
{
    public const MODE_ALL = 'all';

    public const MODE_AT_LEAST_ONE = 'at_least_one';

    protected array $assertions = [];

    protected AssertionManager $assertionManager;

    protected string $mode = self::MODE_ALL;

    /**
     * Stacks an assertion in aggregate
     *
     * @param AssertionInterface|string $assertion
     *    if string, must match a AssertionManager declared service (checked later)
     */
    public function addAssertion($assertion): self
    {
        $this->assertions[] = $assertion;

        return $this;
    }

    public function addAssertions(array $assertions): self
    {
        foreach ($assertions as $assertion) {
            $this->addAssertion($assertion);
        }

        return $this;
    }

    /**
     * Empties assertions stack
     */
    public function clearAssertions(): self
    {
        $this->assertions = [];

        return $this;
    }

    public function setAssertionManager(AssertionManager $manager): self
    {
        $this->assertionManager = $manager;

        return $this;
    }

    public function getAssertionManager(): AssertionManager
    {
        return $this->assertionManager;
    }

    /**
     * Set assertion chain behavior
     *
     * AssertionAggregate should assert to true when:
     *
     * - all assertions are true with MODE_ALL
     * - at least one assertion is true with MODE_AT_LEAST_ONE
     *
     * @throws InvalidArgumentException
     */
    public function setMode(string $mode): self
    {
        if ($mode !== self::MODE_ALL && $mode !== self::MODE_AT_LEAST_ONE) {
            throw new InvalidArgumentException('invalid assertion aggregate mode');
        }

        $this->mode = $mode;

        return $this;
    }

    /**
     * Return current mode
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @see \Laminas\Permissions\Acl\Assertion\AssertionInterface::assert()
     *
     * @throws RuntimeException
     */
    public function assert(
        Acl $acl,
        ?RoleInterface $role = null,
        ?ResourceInterface $resource = null,
        ?string $privilege = null
    ): bool {
        // check if assertions are set
        if (! $this->assertions) {
            throw new RuntimeException('no assertion have been aggregated to this AssertionAggregate');
        }

        foreach ($this->assertions as $assertion) {
            // jit assertion mloading
            if (! $assertion instanceof AssertionInterface) {
                if (class_exists($assertion)) {
                    $assertion = new $assertion();
                } else {
                    if ($manager = $this->getAssertionManager()) {
                        try {
                            $assertion = $manager->get($assertion);
                        } catch (PHPException $e) {
                            throw new Exception\InvalidAssertionException(
                                'assertion "'
                                . $assertion
                                . '" is not defined in assertion manager'
                            );
                        }
                    } else {
                        throw new RuntimeException('no assertion manager is set - cannot look up for assertions');
                    }
                }
            }

            $result = (bool) $assertion->assert($acl, $role, $resource, $privilege);

            if ($this->getMode() === self::MODE_ALL && ! $result) {
                // on false is enough
                return false;
            }

            if ($this->getMode() === self::MODE_AT_LEAST_ONE && $result) {
                // one true is enough
                return true;
            }
        }

        if ($this->getMode() === self::MODE_ALL) {
            // none of the assertions returned false
            return true;
        } else {
            return false;
        }
    }
}
