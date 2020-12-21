<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Entities\User\User;

interface UsersRepositoryInterface extends RepositoryInterface
{
    public function existsByEmail(string $email): bool;

    public function findOneByEmail(string $email): ?User;

    public function getOneByEmail(string $email): User;

    public function getOneById(int $id): User;
}
