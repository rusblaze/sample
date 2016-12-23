<?php
// Role.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 15:43
 */
namespace Ai\CoreDomain\User;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @package Ai\CoreDomain\User
 */
class Role implements RoleInterface
{
    /**
     * @var string
     */
    protected $role;

    /**
     * @var bool
     */
    private $default = false;

    /**
     * @var User[]
     */
    private $users;

    /**
     * Role constructor.
     *
     * @param string $role
     * @param bool $default
     * @param array $users
     */
    public function __construct(string $role, bool $default = false, array $users = [])
    {
        $this->role = $role;
        $this->default = $default;
        $this->users = $users;
    }

    /**
     * @see RoleInterface
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Safely compares self with another role
     * @param Role $role
     * @return bool
     */
    public function isEqual(Role $role): bool
    {
        return $this->role === $role->getRole();
    }

    /**
     * @return boolean
     */
    public function isDefault(): bool
    {
        return $this->default;
    }
}