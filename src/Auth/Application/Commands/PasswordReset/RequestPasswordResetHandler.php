<?php

namespace App\Auth\Application\Commands\PasswordReset;

use App\Auth\Application\Services\Tokenizer;
use App\Auth\Application\Services\TokenSender;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Application\UsersRepositoryInterface;
use App\User\Domain\ValueObjects\ResetToken;

class RequestPasswordResetHandler
{
    private UsersRepositoryInterface $usersRepo;
    private UsersQueryServiceInterface $usersQueryService;
    private Tokenizer $tokenizer;
    private TokenSender $tokenSender;

    public function __construct(
        UsersRepositoryInterface $repo,
        UsersQueryServiceInterface $usersQueryService,
        Tokenizer $tokenizer,
        TokenSender $tokenSender
    )
    {
        $this->usersRepo = $repo;
        $this->usersQueryService = $usersQueryService;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }

    public function handle(RequestPasswordResetCommand $command): void
    {
        $user = $this->usersQueryService->getOneByEmail($command->email);
        $resetToken = new ResetToken($this->tokenizer->generateResetToken());
        $user->requestPasswordReset($resetToken);
        $this->usersRepo->update($user);

        $this->tokenSender->sendResetToken($command->email, $resetToken);
    }
}
