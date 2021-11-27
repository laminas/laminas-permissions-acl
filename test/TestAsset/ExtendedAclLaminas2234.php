<?php

/**
 * @see https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class ExtendedAclLaminas2234 extends Acl\Acl
{
    /**
     * @param  mixed|null  $dfs
     */
    public function exroleDFSVisitAllPrivileges(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        &$dfs = null
    ): ?bool {
        return $this->roleDFSVisitAllPrivileges($role, $resource, $dfs);
    }

    /**
     * @param  mixed|null  $privilege
     */
    public function exroleDFSOnePrivilege(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        $privilege = null
    ): ?bool {
        return $this->roleDFSOnePrivilege($role, $resource, $privilege);
    }

    /**
     * @param  mixed|null  $privilege
     * @param  mixed|null  $dfs
     */
    public function exroleDFSVisitOnePrivilege(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        $privilege = null,
        &$dfs = null
    ): ?bool {
        return $this->roleDFSVisitOnePrivilege($role, $resource, $privilege, $dfs);
    }
}
