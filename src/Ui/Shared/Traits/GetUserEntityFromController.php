<?php

namespace App\Ui\Shared\Traits;

use App\Core\Entities\User\User;
use App\Exceptions\DomainException;

trait GetUserEntityFromController
{
    /**
     * Gets user entiny.
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

        return $userIdentity->getEntity();
    }

}