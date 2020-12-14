<?php

namespace App\Security;

use App\Domain\Entities\User\User;
use App\Domain\Fetchers\Interfaces\CurrentUserFetcherInterface;
use App\Domain\Repositories\UsersRepository;
use App\Exceptions\DomainException;
use Symfony\Component\Security\Core\Security;


class CurrentUserFetcher implements CurrentUserFetcherInterface
{
    private Security $security;
    private UsersRepository $usersRepo;


    public function __construct(Security $security, UsersRepository $usersRepo)
    {
        $this->security = $security;
        $this->usersRepo = $usersRepo;
    }

    public function getUser(): User
    {
        $userIdentity = $this->security->getUser();
        if ($userIdentity === null) {
            throw new DomainException('User not found');
        }

        return $this->usersRepo->getOneById($userIdentity->getId());
    }
}