<?php

namespace App\Core\Commands\Auth\SignUp;

use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\Core\Services\Auth\PasswordHasher;
use App\Core\Services\Auth\Tokenizer;
use App\Core\Services\Auth\TokenSender;

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
        );

        $this->usersRepo->add($newUser);

        $this->tokenSender->sendConfirmToken($email, $confirmToken);
    }
}
