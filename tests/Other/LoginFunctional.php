<?php

namespace App\Tests\Other;

use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\FunctionalTester;
use App\User\Domain\User;
use Symfony\Component\Security\Core\Security;

trait LoginFunctional
{
    use UsersServices;

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
        $usersQs = $this->getUsersQueryService($I);

        return $usersQs->getOneById($security->getUser()->getId());
    }
}
