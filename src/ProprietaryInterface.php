<?php

declare(strict_types=1);

/**
 * @see       https://github.com/laminas/laminas-permissions-acl for the canonical source repository
 */

namespace Laminas\Permissions\Acl;

/**
 * Applicable to Resources and Roles.
 *
 * Provides information about the owner of some object. Used in conjunction
 * with the Ownership assertion.
 */
interface ProprietaryInterface
{
    /**
     * @return mixed
     */
    public function getOwnerId();
}
