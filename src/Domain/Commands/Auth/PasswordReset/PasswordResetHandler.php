<?php

namespace App\Domain\Commands\Auth\PasswordReset;

use App\Domain\Entities\User\User;
use App\Domain\Repositories\UsersRepository;
use App\Domain\Services\Auth\PasswordHasher;

class PasswordResetHandler
{
    private UsersRepository $usersRepo;


    public function __construct(UsersRepository $repo, PasswordHasher $hasher)
    {
        $this->usersRepo = $repo;
        $this->passwordHasher = $hasher;
    }


    public function handle(PasswordResetCommand $command): void
    {
        /** @var User $user */
        $user = $this->usersRepo->getOneBy(['resetToken.token' => $command->token]);
        $user->resetPassword($this->passwordHasher->hash($command->password));
        $this->usersRepo->flush();
    }
}
