<?php
// UserNotFoundException.php
/**
 * User: aivanov
 * Date: 19.12.16
 * Time: 18:39
 */
namespace Ai\CoreDomain\User\Exception;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserNotFoundException extends UsernameNotFoundException
{

}