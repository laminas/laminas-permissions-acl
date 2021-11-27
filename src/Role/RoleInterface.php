<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace Laminas\Permissions\Acl\Role;

interface RoleInterface
{
    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId();
}
