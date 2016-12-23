<?php
// UserService.php
/**
 * User: aivanov
 * Date: 23.12.16
 * Time: 11:33
 */
namespace Ai\ApiBundle\Service;

use Ai\CoreDomain\User\Exception\BadCreateUserDataException;
use Ai\CoreDomain\User\Exception\RoleNotFoundException;
use Ai\CoreDomain\User\Exception\UserNotFoundException;
use Ai\CoreDomain\User\Role;
use Ai\CoreDomain\User\RoleRepositoryInterface;
use Ai\CoreDomain\User\UserId;
use Ai\CoreDomain\User\UserRepositoryInterface;
use Ai\CoreDomainBundle\Entity\User;
use Ramsey\Uuid\Uuid;

/**
 * Class UserService
 * @package Ai\ApiBundle\Service
 */
class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }


    /**
     * @param User[] $newUsersList
     *
     * @return array Array of errors
     */
    public function updateUsersList(array $newUsersList): array
    {
        $this->userRepository->markForDeleting();
        $errors = [];
        foreach ($newUsersList as $newUser) {
            try {
                $userIsNew = false;
                $existingUser = $this->userRepository->findByEmail($newUser->getEmail());
                if (!is_null($newUser->getRole())) {
                    try {
                        $newRole = $this->findNewUserRole($newUser, true);
                        $existingUser->changeRole($newRole);
                    } catch (RoleNotFoundException $e) {
                        $errors[$newUser->getEmail()] = new BadCreateUserDataException(
                            'No role found with name "' . $newUser->getRole()->getRole() . '"'
                        );
                    }
                }
            } catch (UserNotFoundException $e) {
                $userIsNew = true;
                if (is_null($newUser->getPassword())) {
                    $errors[$newUser->getEmail()] = new BadCreateUserDataException(
                        'No password supplied for newly created user'
                    );
                }
                $userId = new UserId(Uuid::uuid4()->toString());
                try {
                    $newRole = $this->findNewUserRole($newUser, true);
                } catch (RoleNotFoundException $e) {
                    $errors[$newUser->getEmail()] = $e;
                    continue;
                }
                $existingUser = User::register(
                    $userId,
                    $newUser->getEmail(),
                    $newUser->getPassword(),
                    $newRole,
                    $newUser->getFirstName(),
                    $newUser->getLastName()
                );
            }

            if (!is_null($newUser->getFirstName()) || !is_null($newUser->getLastName())) {
                $existingUser->changeUserData($newUser->getFirstName(), $newUser->getLastName());
            }
            if (!is_null($newUser->getPassword())) {
                $existingUser->changePassword($newUser->getPassword());
            }

            if ($userIsNew) {
                $this->userRepository->add($existingUser);
            } else {
                $existingUser->unmarkPotentialDeleting()
                             ->restore();
                $this->userRepository->update($existingUser);
            }
        }
        $this->userRepository->removeMarked();
        return $errors;
    }

    /**
     * @param User $newUser
     * @param bool $createIfNotExists
     *
     * @return Role
     *
     * @throws RoleNotFoundException
     */
    private function findNewUserRole(User $newUser, bool $createIfNotExists = false): Role
    {
        $newUsersRole = $newUser->getRole();
        if (!is_null($newUsersRole)) {
            try {
                return $this->roleRepository->findByName($newUsersRole->getRole());
            } catch (RoleNotFoundException $e) {
                if ($createIfNotExists) {
                    $this->roleRepository->add($newUsersRole);
                    return $newUsersRole;
                } else {
                    throw $e;
                }
            }
        } else {
            return $this->roleRepository->getDefaultRole();
        }
    }
}