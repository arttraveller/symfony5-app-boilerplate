<?php

namespace App\Tests\Other;

use App\DataFixtures\UsersFixtures;
use App\Tests\FunctionalTester;

trait LoginFunctional
{
    public function login(FunctionalTester $I, $email = UsersFixtures::CONFIRMED_USER_EMAIL, $password = UsersFixtures::CONFIRMED_USER_PASSwORD): void
    {
        $I->amOnPage('/signin');
        $I->fillField('Email', $email);
        $I->fillField('Password', $password);
        $I->click('Sign in');
    }

}
