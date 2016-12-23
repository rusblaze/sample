<?php
// RoleRepositoryInterface.php
/**
 * User: aivanov
 * Date: 23.12.16
 * Time: 11:39
 */

namespace Ai\CoreDomain\User;

use Ai\CoreDomain\User\Exception\RoleNotFoundException;

/**
 * Interface RoleRepositoryInterface
 * @package Ai\CoreDomain\User
 */
interface RoleRepositoryInterface
{
    /**
     * @param string $roleName
     *
     * @return Role
     *
     * @throws RoleNotFoundException
     */
    public function findByName(string $roleName): Role;


    /**
     * @return Role
     *
     * @throws RoleNotFoundException
     */
    public function getDefaultRole(): Role;

    /**
     * @param Role $role
     *
     * @return void
     */
    public function add(Role $role);
}