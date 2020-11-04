<?php

namespace App\Tests;

use App\Tests\FunctionalTester;

class SiteControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testMainpageOk(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
        $I->see('Hello!', 'h1');
    }
}
