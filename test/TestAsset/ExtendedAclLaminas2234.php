<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class ExtendedAclLaminas2234 extends Acl\Acl
{
    /**
     * @param array|null &$dfs
     * @return bool|void
     */
    public function exroleDFSVisitAllPrivileges(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        &$dfs = null
    ) {
        return $this->roleDFSVisitAllPrivileges($role, $resource, $dfs);
    }

    /** @return bool|void */
    public function exroleDFSOnePrivilege(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        ?string $privilege = null
    ) {
        return $this->roleDFSOnePrivilege($role, $resource, $privilege);
    }

    /** @return bool|void */
    public function exroleDFSVisitOnePrivilege(
        Acl\Role\RoleInterface $role,
        ?Acl\Resource\ResourceInterface $resource = null,
        ?string $privilege = null,
        ?array &$dfs = null
    ) {
        return $this->roleDFSVisitOnePrivilege($role, $resource, $privilege, $dfs);
    }
}
