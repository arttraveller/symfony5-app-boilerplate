<?php

namespace App\Domain\Commands\Auth\SignUp;

use App\Domain\Entities\User\Name;
use App\Domain\Entities\User\User;
use App\Domain\Repositories\UsersRepository;
use App\Domain\Services\Auth\PasswordHasher;
use App\Domain\Services\Auth\Tokenizer;
use App\Domain\Services\Auth\TokenSender;

class SignUpHandler
{
    private PasswordHasher $passwordHasher;
    private UsersRepository $usersRepo;
    private Tokenizer $tokenizer;


    public function __construct(UsersRepository $repo, PasswordHasher $hasher, Tokenizer $tokenizer, TokenSender $tokenSender)
    {
        $this->usersRepo = $repo;
        $this->passwordHasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }


    public function handle(SignUpCommand $command): void
    {
        $email = trim($command->email);
        $password = trim($command->password);
        $newUser = User::registerByEmail(
            $email,
            $this->passwordHasher->hash($password),
            $confirmToken = $this->tokenizer->generateConfirmToken(),
            new Name($command->firstName, $command->lastName)
        );

        $this->usersRepo->add($newUser);

        $this->tokenSender->sendConfirmToken($email, $confirmToken);
    }
}
