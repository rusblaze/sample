<?php
// DoctrineRoleRepository.php
/**
 * User: aivanov
 * Date: 23.12.16
 * Time: 13:14
 */
namespace Ai\CoreDomainBundle\Repository;

use Ai\CoreDomain\User\{
    Exception\RoleNotFoundException,
    Role,
    RoleRepositoryInterface
};
use Doctrine\ORM\EntityManager;

class DoctrineRoleRepository implements RoleRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $roleName): Role
    {
        $role = $this->em->getRepository('AiCoreDomain:User\Role')->findOneBy(['role' => $roleName]);
        if (is_null($role)) {
            throw new RoleNotFoundException();
        }
        return $role;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultRole(): Role
    {
        $role = $this->em->getRepository('AiCoreDomain:User\Role')->findOneBy(['default' => true]);
        if (is_null($role)) {
            throw new RoleNotFoundException();
        }
        return $role;
    }

    /**
     * @inheritDoc
     */
    public function add(Role $role)
    {
        $this->em->persist($role);
        $this->em->flush($role);
    }
}