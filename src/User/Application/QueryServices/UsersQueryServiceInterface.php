<?php

namespace App\User\Application\QueryServices;

use App\Shared\Exceptions\UserNotFoundException;
use App\User\Domain\User;

interface UsersQueryServiceInterface
{
    public function existsByEmail(string $email): bool;

    public function findOneByEmail(string $email): ?User;

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function getOneByEmail(string $email): User;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getOneById(int $id): User;

    /**
     * @param array $criteria
     * @return User
     * @throws UserNotFoundException
     */
    public function getOneBy(array $criteria): User;
}
