<?php

namespace App\Auth\Infrastructure\Symfony;

use App\Auth\Application\QueryServices\CurrentUserFetcherInterface;
use App\Shared\Exceptions\UserNotFoundException;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Domain\User;
use Symfony\Component\Security\Core\Security;

class SecurityCurrentUserFetcher implements CurrentUserFetcherInterface
{
    private Security $security;
    private UsersQueryServiceInterface $usersQueryService;

    public function __construct(Security $security, UsersQueryServiceInterface $usersQueryService)
    {
        $this->security = $security;
        $this->usersQueryService = $usersQueryService;
    }

    public function getUser(): User
    {
        $userIdentity = $this->security->getUser();
        if ($userIdentity === null) {
            throw new UserNotFoundException();
        }

        return $this->usersQueryService->getOneById($userIdentity->getId());
    }
}