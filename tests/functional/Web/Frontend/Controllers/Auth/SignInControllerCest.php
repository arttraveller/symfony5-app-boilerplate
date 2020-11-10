<?php

namespace App\Tests;

use App\DataFixtures\UsersFixtures;

class SignInControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testSignInOk(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->seeResponseCodeIs(200);
        $I->see('Please sign in', 'h2');
        $I->fillField('Email', UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->fillField('Password', UsersFixtures::CONFIRMED_USER_PASSwORD);
        $I->click('Sign in', '.btn');

        $I->seeCurrentUrlEquals('/');
        $I->seeLink('Sign out', '/logout');
    }

    public function testSignInUnknownUser(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->fillField('Email', 'unknown@example.com');
        $I->fillField('Password', 'password');
        $I->click('Sign in', '.btn');

        $I->seeCurrentUrlEquals('/signin');
        $I->see('Username could not be found.', 'div.alert-danger');
    }

    public function testSignInWithIncorrectPassword(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->fillField('Email', UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->fillField('Password', '1234512345');
        $I->click('Sign in', '.btn');

        $I->seeCurrentUrlEquals('/signin');
        $I->see('Invalid credentials.', 'div.alert-danger');
    }


    public function testSignInUnconfirmedUser(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->fillField('Email', UsersFixtures::UNCONFIRMED_USER_EMAIL);
        $I->fillField('Password', 'password');
        $I->click('Sign in', '.btn');

        $I->seeCurrentUrlEquals('/signin');
        $I->see('Your user account is not active.', 'div.alert-danger');
    }
}
