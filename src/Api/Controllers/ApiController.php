<?php

namespace App\Api\Controllers;

use App\Core\Entities\User\User;
use App\Exceptions\DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ApiController extends AbstractController
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