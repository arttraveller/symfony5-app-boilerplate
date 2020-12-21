<?php

namespace App\Tests\Other;

use App\Domain\Entities\User\User;
use App\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;
use App\Tests\FunctionalTester;
use Symfony\Component\Security\Core\Security;

trait LoginFunctional
{
    protected function login(FunctionalTester $I, $email = UsersFixtures::CONFIRMED_USER_EMAIL, $password = UsersFixtures::CONFIRMED_USER_PASSWORD): void
    {
        $I->amOnPage('/signin');
        $I->fillField('Email', $email);
        $I->fillField('Password', $password);
        $I->click('Sign in', '.btn');
    }


    protected function getCurrentUser(FunctionalTester $I): User
    {
        $security = $I->grabService(Security::class);
        $usersRepository = $I->grabService(UsersRepository::class);

        return $usersRepository->getOneById($security->getUser()->getId());
    }
}
