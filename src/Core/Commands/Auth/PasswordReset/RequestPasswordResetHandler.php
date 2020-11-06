<?php

namespace App\Core\Commands\Auth\PasswordReset;

use App\Core\Entities\User\ResetToken;
use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\Core\Services\Auth\Tokenizer;
use App\Core\Services\Auth\TokenSender;

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
