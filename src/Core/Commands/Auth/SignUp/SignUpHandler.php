<?php

namespace App\Core\Commands\Auth\SignUp;

use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\Core\Services\Auth\PasswordHasher;
use App\Core\Services\Auth\Tokenizer;

class SignUpHandler
{
    private PasswordHasher $passwordHasher;
    private UsersRepository $usersRepo;
    private Tokenizer $tokenizer;


    public function __construct(UsersRepository $repo, PasswordHasher $hasher, Tokenizer $tokenizer)
    {
        $this->usersRepo = $repo;
        $this->passwordHasher = $hasher;
        $this->tokenizer = $tokenizer;
    }


    public function handle(SignUpCommand $command): void
    {
        $email = trim($command->email);
        $password = trim($command->password);
        $newUser = User::registerByEmail(
            $email,
            $this->passwordHasher->hash($password),
            $confirmToken = $this->tokenizer->generateConfirmToken(),
        );

        $this->usersRepo->add($newUser);
    }
}
