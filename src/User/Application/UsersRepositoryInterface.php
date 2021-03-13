<?php

namespace App\User\Application;

use App\User\Domain\User;

interface UsersRepositoryInterface
{
    public function create(User $user): void;

    public function update(User $user): void;

    public function delete(User $user): void;
}
