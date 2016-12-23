<?php
// DoctrineUserRepository.php
/**
 * User: aivanov
 * Date: 19.12.16
 * Time: 19:22
 */
namespace Ai\CoreDomainBundle\Repository;

use Ai\CoreDomain\User\Exception\{
    BadCreateUserDataException,
    UserNotFoundException
};
use Ai\CoreDomain\User\{
    User,
    UserId,
    UserRepositoryInterface
};
use Ai\CoreDomainBundle\Security\SessionUserProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class DoctrineUserRepository implements UserRepositoryInterface, SessionUserProviderInterface
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
    public function findById(UserId $userId): User
    {
        // TODO: Implement findById() method.
    }

    private function findByCriteria(array $criteria): User
    {
        if (!isset($criteria["deleted"])) {
            $criteria["deleted"] = false;
        }
        $user = $this->em->getRepository('AiCoreDomainBundle:User')->findOneBy($criteria);
        if (is_null($user)) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): User
    {
        return $this->findByCriteria(['email' => $email]);
    }

    /**
     * @inheritDoc
     */
    public function findBySessionToken(string $sessionToken): \Ai\CoreDomainBundle\Entity\User
    {
        return $this->findByCriteria(['sessionToken' => $sessionToken]);
    }

    /**
     * @inheritDoc
     */
    public function findAll(bool $includeDeleted = false): array
    {
        $criteria = [];
        if ($includeDeleted === false) {
            $criteria["deleted"] = false;
        }

        return $this->em->getRepository('AiCoreDomainBundle:User')->findBy($criteria);
    }

    /**
     * @inheritDoc
     */
    public function add(User $user)
    {
        $this->em->persist($user);
        $this->em->flush($user);
    }

    /**
     * @inheritDoc
     */
    public function update(User $user)
    {
        $this->em->persist($user);
        $this->em->flush($user);
    }

    /**
     * @inheritDoc
     */
    public function markForDeleting()
    {
        $q = $this->em->createQuery('update AiCoreDomainBundle:User u set u.potentialDeleted = true');
        $numUpdated = $q->execute();

        return $numUpdated;
    }

    /**
     * @inheritDoc
     */
    public function removeMarked()
    {
        $delTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $q = $this->em->createQuery('update AiCoreDomainBundle:User u set u.deleted = true, u.deletedAt = ?1 WHERE u.potentialDeleted = true');
        $q->setParameter(1, $delTime);
        $numUpdated = $q->execute();

        return $numUpdated;
    }
}