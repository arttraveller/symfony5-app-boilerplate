<?php

namespace App\Auth\Application\Commands\PasswordReset;

use App\Auth\Application\Services\PasswordHasher;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Application\UsersRepositoryInterface;

class PasswordResetHandler
{
    private UsersRepositoryInterface $usersRepo;
    private UsersQueryServiceInterface $usersQueryService;

    public function __construct(UsersRepositoryInterface $repo, UsersQueryServiceInterface $usersQueryService, PasswordHasher $hasher)
    {
        $this->usersRepo = $repo;
        $this->usersQueryService = $usersQueryService;
        $this->passwordHasher = $hasher;
    }

    public function handle(PasswordResetCommand $command): void
    {
        $user = $this->usersQueryService->getOneBy(['resetToken.token' => $command->token]);
        $user->resetPassword($this->passwordHasher->hash($command->password));
        $this->usersRepo->update($user);
    }
}
