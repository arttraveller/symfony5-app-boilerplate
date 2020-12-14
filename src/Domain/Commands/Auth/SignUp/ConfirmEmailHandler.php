<?php

namespace App\Domain\Commands\Auth\SignUp;

use App\Domain\Repositories\UsersRepository;

class ConfirmEmailHandler
{
    private UsersRepository $usersRepo;


    public function __construct(UsersRepository $repo)
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
