<?php

namespace App\Auth\Application\Commands\SignUp;

use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Application\UsersRepositoryInterface;

class ConfirmEmailHandler
{
    private UsersQueryServiceInterface $usersQueryService;
    private UsersRepositoryInterface $usersRepo;

    public function __construct(UsersQueryServiceInterface $usersQueryService, UsersRepositoryInterface $repo)
    {
        $this->usersQueryService = $usersQueryService;
        $this->usersRepo = $repo;
    }

    public function handle(ConfirmEmailCommand $command): void
    {
        $user = $this->usersQueryService->getOneBy(['confirmToken' => $command->token]);
        $user->confirmRegistration();
        $this->usersRepo->update($user);
    }
}
