<?php
// User.php
/**
 * User: aivanov
 * Date: 19.12.16
 * Time: 18:29
 */
namespace Ai\CoreDomain\User;

use Swagger\Annotations as SWG;

/**
 * Class User
 * @package Ai\CoreDomain\User
 */
/**
 * @SWG\Definition(
 *   definition="ExistingUser",
 *   type="object",
 *   @SWG\Schema(
 *     required={"email"}
 *   )
 * )
 */
class User
{
    /**
     * @var string
     * @SWG\Property(type="string")
     */
    protected $email;

    /**
     * @var string|null
     * @SWG\Property(
     *     type="string",
     *     property="first_name"
     * )
     */
    protected $firstName;

    /**
     * @var UserId
     */
    protected $id;

    /**
     * @var string|null
     * @SWG\Property(
     *     type="string",
     *     property="last_name"
     * )
     */
    protected $lastName;

    /**
     * Encrypted password
     *
     * @var string
     */
    protected $password;

    /**
     * @var Role
     * @SWG\Property(
     *   property="role",
     *   type="string",
     *   description="User role",
     *   enum={"ROLE_ADMIN","ROLE_USER"}
     * )
     */
    protected $role;

    /**
     * Flag for marking user as deleted
     *
     * @var bool
     */
    protected $deleted = false;

    /**
     * Timestamp of user registration (UTC)
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Timestamp of user deletion (UTC)
     *
     * @var \DateTime|null
     */
    protected $deletedAt;

    /**
     * Flag used for marking user as potentially deleted while full updating
     *
     * @var bool
     */
    protected $potentialDeleted = false;

    /**
     * User constructor.
     *
     * @param UserId        $id            New user id
     * @param string        $email         E-mail address (should be unique)
     * @param string        $plainPassword New user plain password
     *                                     (will be encrypted on construction)
     * @param Role          $role          User role
     * @param \DateTime     $createdAt     Timestamp of user registration
     * @param string|null   $firstName     User first name
     * @param string|null   $lastName      User last name
     */
    protected function __construct(
        UserId $id,
        string $email,
        string $plainPassword,
        Role $role,
        \DateTime $createdAt,
        string $firstName = null,
        string $lastName = null
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
        $this->password = $this->hashPassword($plainPassword);
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    /**
     * Method for new users registration
     *
     * @param UserId        $id
     * @param string        $email
     * @param string        $plainPassword
     * @param Role          $role
     * @param string|null   $firstName
     * @param string|null   $lastName
     *
     * @return static
     */
    public static function register(
        UserId $id,
        string $email,
        string $plainPassword,
        Role $role,
        string $firstName = null,
        string $lastName = null
    ) {
        /**
         * @todo Could be added some business logic checks
         */

        $createdAt = new \DateTime('now', new \DateTimeZone('UTC'));

        return new static(
            $id,
            $email,
            $plainPassword,
            $role,
            $createdAt,
            $firstName,
            $lastName
        );
    }

    /**
     * Mark user as deleted
     * @todo make it possible to restore user. Need issue description
     */
    public function delete()
    {
        $this->deleted = true;
        $this->deletedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Password hashing method
     *
     * @param string $plainPassword
     *
     * @return bool|string
     */
    private function hashPassword(string $plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return Role|null
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param Role $newRole
     *
     * @return $this
     */
    public function changeRole(Role $newRole)
    {
        $this->role = $newRole;

        return $this;
    }

    /**
     * @param string $newPlainPassword
     *
     * @return $this
     */
    public function changePassword(string $newPlainPassword)
    {
        $this->password = $this->hashPassword($newPlainPassword);

        return $this;
    }

    /**
     * @param string|null $firstName
     * @param string|null $lastName
     *
     * @return $this
     */
    public function changeUserData(string $firstName = null, string $lastName = null)
    {
        if (!is_null($firstName)) {
            $this->firstName = $firstName;
        }
        if (!is_null($lastName)) {
            $this->lastName = $lastName;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function unmarkPotentialDeleting()
    {
        $this->potentialDeleted = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function restore()
    {
        $this->deleted = false;
        $this->deletedAt = null;

        return $this;
    }
}
/**
 * @SWG\Definition(
 *   definition="UpdateUser",
 *   type="object",
 *   allOf={
 *     @SWG\Schema(ref="#/definitions/ExistingUser"),
 *     @SWG\Schema(
 *        required={"email"},
 *        @SWG\Property(property="password", type="password")
 *     )
 *   }
 * )
 */