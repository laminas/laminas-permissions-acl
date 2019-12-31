<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-acl/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-acl/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Acl\TestAsset;

use Laminas\Permissions\Acl;

class ExtendedAclLaminas2234 extends Acl\Acl
{
    public function exroleDFSVisitAllPrivileges(
        Acl\Role\RoleInterface $role,
        Acl\Resource\ResourceInterface $resource = null,
        &$dfs = null
    ) {

        return $this->roleDFSVisitAllPrivileges($role, $resource, $dfs);
    }

    public function exroleDFSOnePrivilege(
        Acl\Role\RoleInterface $role,
        Acl\Resource\ResourceInterface $resource = null,
        $privilege = null
    ) {

        return $this->roleDFSOnePrivilege($role, $resource, $privilege);
    }

    public function exroleDFSVisitOnePrivilege(
        Acl\Role\RoleInterface $role,
        Acl\Resource\ResourceInterface $resource = null,
        $privilege = null,
        &$dfs = null
    ) {

        return $this->roleDFSVisitOnePrivilege($role, $resource, $privilege, $dfs);
    }
}
