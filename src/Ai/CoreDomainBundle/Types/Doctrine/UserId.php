<?php
// UserId.php
/**
 * User: aivanov
 * Date: 20.12.16
 * Time: 12:37
 */
namespace Ai\CoreDomainBundle\Types\Doctrine;

class UserId extends EntityId
{
    public function getName()
    {
        return 'UserId';
    }

    protected function getNamespace()
    {
        return 'Ai\CoreDomain\User';
    }
}