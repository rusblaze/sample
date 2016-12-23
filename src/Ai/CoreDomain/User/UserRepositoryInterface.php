<?php
/**
 * User: aivanov
 * Date: 16.08.16
 * Time: 13:27
 */
namespace Ai\CoreDomain\User;

use Ai\CoreDomain\User\Exception\BadCreateUserDataException;
use Ai\CoreDomain\User\Exception\UserNotFoundException;
use Ai\CoreDomain\User\User;

/**
 * Interface UserRepositoryInterface
 * @package Iml\CoreDomain\User
 */
interface UserRepositoryInterface
{
    /**
     * @param UserId $userId
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function findById(UserId $userId): User;

    /**
     * @param string $email
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): User;

    /**
     * @param bool $includeDeleted
     * @return User[]
     */
    public function findAll(bool $includeDeleted = false): array;

    /**
     * @param User $user
     *
     * @return void
     *
     * @throws BadCreateUserDataException
     */
    public function add(User $user);

    /**
     * @param User $user
     *
     * @return void
     */
    public function update(User $user);

    /**
     * @return void
     */
    public function markForDeleting();

    /**
     * @return void
     */
    public function removeMarked();
}