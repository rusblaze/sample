<?php
// SessionUserProviderInterface.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 13:24
 */

namespace Ai\CoreDomainBundle\Security;


use Ai\CoreDomainBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

interface SessionUserProviderInterface
{
    /**
     * @param string $sessionToken
     *
     * @return User
     *
     * @throws UsernameNotFoundException
     */
    public function findBySessionToken(string $sessionToken): User;
}