<?php

namespace App\Auth\Application\QueryServices;

use App\Shared\Exceptions\UserNotFoundException;
use App\User\Domain\User;

interface CurrentUserFetcherInterface
{
    /**
     * @return User
     * @throws UserNotFoundException
     */
    public function getUser(): User;
}
