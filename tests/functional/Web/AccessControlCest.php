<?php

namespace App\Tests;

use App\Tests\Other\LoginFunctional;

class AccessControlCest
{

    use LoginFunctional;


    public function _before(FunctionalTester $I)
    {
    }

    public function testGuestRequestsPageForLoggedInUsersOnly(FunctionalTester $I)
    {
        $I->amOnPage('/profile');
        $I->seeCurrentUrlEquals('/signin');
        $I->see('Sign in', 'button');
    }

    public function testUserRequestsPageForLoggedInUsersOnly(FunctionalTester $I)
    {
        $this->login($I);
        $I->amOnPage('/profile');
        $I->see('Your profile', 'h2');
    }
}
