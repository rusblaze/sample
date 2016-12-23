<?php
// UserProvider.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 12:43
 */
namespace Ai\CoreDomainBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SessionUserProvider implements UserProviderInterface
{
    /**
     * @var SessionUserProviderInterface
     */
    private $userRepository;

    /**
     * SessionUserProvider constructor.
     *
     * @param SessionUserProviderInterface $userRepository
     */
    public function __construct(SessionUserProviderInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * In our case, it is session token
     *
     * @param string $token
     */
    public function loadUserByUsername($token)
    {
        return $this->userRepository->findBySessionToken($token);
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }

}