<?php

namespace App\Domain\Commands\Auth\PasswordReset;

use App\Domain\Entities\User\ResetToken;
use App\Domain\Entities\User\User;
use App\Domain\Repositories\UsersRepository;
use App\Domain\Services\Auth\Tokenizer;
use App\Domain\Services\Auth\TokenSender;

class RequestPasswordResetHandler
{
    private UsersRepository $usersRepo;


    public function __construct(UsersRepository $repo, Tokenizer $tokenizer, TokenSender $tokenSender)
    {
        $this->usersRepo = $repo;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }


    public function handle(RequestPasswordResetCommand $command): void
    {
        /** @var User $user */
        $user = $this->usersRepo->getOneByEmail($command->email);
        $resetToken = new ResetToken($this->tokenizer->generateResetToken());
        $user->requestPasswordReset($resetToken);
        $this->usersRepo->flush();

        $this->tokenSender->sendResetToken($command->email, $resetToken);
    }
}
