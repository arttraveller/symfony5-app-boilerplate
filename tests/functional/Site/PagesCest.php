<?php

namespace App\Tests;

use App\Tests\FunctionalTester;

class PagesCest
{
    public function testMainpageOk(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
        $I->see('Hello!', 'h1');
    }
}
