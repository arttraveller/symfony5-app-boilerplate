<?php

namespace App\Tests;

use App\DataFixtures\UsersFixtures;

class SignInControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testSignInUnknownUser(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->seeResponseCodeIs(200);
        $I->fillField('Email', 'unknown@example.com');
        $I->fillField('Password', 'password');
        $I->click('Sign in');

        $I->seeCurrentUrlEquals('/signin');
        $I->see('Username could not be found.', 'div.alert-danger');
    }


    public function testSignInWithIncorrectPassword(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->seeResponseCodeIs(200);
        $I->fillField('Email', UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->fillField('Password', '1234512345');
        $I->click('Sign in');

        $I->seeCurrentUrlEquals('/signin');
        $I->see('Invalid credentials.', 'div.alert-danger');
    }


    public function testSignInOk(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->seeResponseCodeIs(200);
        $I->fillField('Email', UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->fillField('Password', UsersFixtures::CONFIRMED_USER_PASSwORD);
        $I->click('Sign in');

        $I->seeCurrentUrlEquals('/');
        $I->seeLink('Log Out', '/logout');
    }

}
