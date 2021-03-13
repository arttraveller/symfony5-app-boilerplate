<?php

namespace App\Auth\Application\Commands\SignUp;

use App\Auth\Application\Services\PasswordHasher;
use App\Auth\Application\Services\Tokenizer;
use App\Auth\Application\Services\TokenSender;
use App\User\Application\UsersRepositoryInterface;
use App\User\Domain\User;
use App\User\Domain\ValueObjects\Name;

class SignUpHandler
{
    private PasswordHasher $passwordHasher;
    private Tokenizer $tokenizer;

    public function __construct(UsersRepositoryInterface $repo, PasswordHasher $hasher, Tokenizer $tokenizer, TokenSender $tokenSender)
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

        $this->usersRepo->create($newUser);

        $this->tokenSender->sendConfirmToken($email, $confirmToken);
    }
}
