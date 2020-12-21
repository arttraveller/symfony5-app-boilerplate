<?php

namespace App\Domain\Commands\Auth\SignUp;

use App\Domain\Repositories\Interfaces\UsersRepositoryInterface;

class ConfirmEmailHandler
{
    private UsersRepositoryInterface $usersRepo;


    public function __construct(UsersRepositoryInterface $repo)
    {
        $this->usersRepo = $repo;
    }


    public function handle(ConfirmEmailCommand $command): void
    {
        $user = $this->usersRepo->getOneBy(['confirmToken' => $command->token]);
        $user->confirmRegistration();
        $this->usersRepo->flush();
    }

}
