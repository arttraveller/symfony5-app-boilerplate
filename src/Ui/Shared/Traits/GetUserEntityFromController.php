<?php

namespace App\Ui\Shared\Traits;

use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\Exceptions\DomainException;

trait GetUserEntityFromController
{

    private UsersRepository $usersRepo;


    /**
     * @required
     */
    public function setUsersRepo(UsersRepository $usersRepo): void
    {
        $this->usersRepo = $usersRepo;
    }


    /**
     * Gets user entity.
     *
     * @return User
     *
     * @throws DomainException if user not found
     */
    protected function getUserEntity(): User
    {
        $userIdentity = $this->getUser();
        if ($userIdentity === null) {
            throw new DomainException('User not found');
        }

        // TODO check associations
        // $user = $userIdentity->getEntity();
        $user = $this->usersRepo->getOneById($userIdentity->getId());

        return $user;
    }

}