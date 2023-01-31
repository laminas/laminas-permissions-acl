<?php

declare(strict_types=1);

namespace LaminasBench\PermissionsAcl;

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole;

/**
 * @Revs(2)
 * @Iterations(2)
 * @Warmup(1)
 */
class AclBench
{
    private const NUM_ROLES_WITHOUT_PARENT = 135;
    private const NUM_ROLES_WITH_PARENT = 70;
    private const NUM_RESOURCES = 324;
    private const NUM_ALLOW_CALLED = 300;
    private const NUM_DENY_CALLED = 300;

    private Acl $acl;

    public function __construct()
    {
        $this->acl = new Acl();
    }

    public function benchSetService(): void
    {
        $acl = new Acl();
        $role = new GenericRole('A');
        $acl->addRole($role);
        for ($i = 0; $i < self::NUM_ROLES_WITHOUT_PARENT; $i++) {
            $acl->addRole((string) $i);
        }
        for ($i = 0; $i < self::NUM_ROLES_WITH_PARENT; $i++) {
            $acl->addRole((string) ($i + self::NUM_ROLES_WITHOUT_PARENT), $i % self::NUM_ROLES_WITHOUT_PARENT);
        }
        for ($i = 0; $i < self::NUM_RESOURCES; $i++) {
            if ($i === 0) {
                $parent = null;
            } else {
                $parent = (string) (intdiv($i * 3, 2) % $i);
            }
            $acl->addResource((string) $i, $parent);
        }
        for ($i = 0; $i < self::NUM_ALLOW_CALLED; $i++) {
            $role = (string) (intdiv($i * 3, 2) % (self::NUM_ROLES_WITHOUT_PARENT + self::NUM_ROLES_WITH_PARENT));
            if ($i %2 ) {
                $resource = (string) (intdiv($i * 7, 5) % self::NUM_RESOURCES);
            } else {
                $resource = null;
            }
            $acl->allow($role, $resource);
        }
        for ($i = 0; $i < self::NUM_DENY_CALLED; $i++) {
            $role = (string) (intdiv($i * 13, 11) % (self::NUM_ROLES_WITHOUT_PARENT + self::NUM_ROLES_WITH_PARENT));
            if ($i %2 ) {
                $resource = (string) (intdiv($i * 19, 17) % self::NUM_RESOURCES);
            } else {
                $resource = null;
            }
            $acl->allow($role, $resource);
        }
    }
}
