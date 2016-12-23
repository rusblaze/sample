<?php
// User.php
/**
 * User: aivanov
 * Date: 22.12.16
 * Time: 12:44
 */
namespace Ai\CoreDomainBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *   definition="Session",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/ExistingUser"),
 *     @SWG\Schema(
 *        required={"token"}
 *     )
 *   }
 * )
 */
class User extends \Ai\CoreDomain\User\User implements UserInterface
{
    /**
     * @var string|null
     * @SWG\Property(
     *     type="string",
     *     property="token"
     * )
     */
    private $sessionToken;

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [parent::getRole()];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return parent::getPassword();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return parent::getEmail();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    public function startSession(string $sessionKey)
    {
        $this->sessionToken = $sessionKey;
    }

    public function endSession()
    {
        $this->sessionToken = null;
    }

    public function getActiveSession()
    {
        return $this->sessionToken;
    }
}