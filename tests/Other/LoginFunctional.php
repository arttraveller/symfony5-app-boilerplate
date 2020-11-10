<?php

namespace App\Tests\Other;

use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;
use App\Tests\FunctionalTester;
use Symfony\Component\Security\Core\Security;

trait LoginFunctional
{
    protected function login(FunctionalTester $I, $email = UsersFixtures::CONFIRMED_USER_EMAIL, $password = UsersFixtures::CONFIRMED_USER_PASSwORD): void
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
