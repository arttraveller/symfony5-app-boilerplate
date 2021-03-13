<?php

namespace App\Tests;

use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\Other\LoginFunctional;

class AccessControlCest
{
    use LoginFunctional;

    public function testGuestRequestsPageForUsersOnly(FunctionalTester $I)
    {
        $I->amOnPage('/profile');
        $I->seeCurrentUrlEquals('/signin');
        $I->see('Sign in', 'button');
    }

    public function testUserRequestsPageForUsersOnly(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/profile');
        $I->see('Your profile', 'h2');
        $I->see(UsersFixtures::CONFIRMED_USER_EMAIL, 'td');
    }

    public function testUserRequestsPageForAdminOnly(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/admin');
        $I->seeResponseCodeIs(403);
    }

    public function testAdminRequestsPageForAdminOnly(FunctionalTester $I)
    {
        $this->login($I, UsersFixtures::ADMIN_EMAIL, UsersFixtures::ADMIN_PASSWORD);
        $I->amOnPage('/admin');
        $I->seeResponseCodeIs(200);
    }
}
