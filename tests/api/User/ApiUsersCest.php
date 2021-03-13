<?php

namespace App\Tests;

use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\Other\LoginApi;

class ApiUsersCest
{
    use LoginApi;

    public function testProfileOk(ApiTester $I)
    {
        $accessToken = $this->login($I);
        $I->amBearerAuthenticated($accessToken);
        $I->sendGet('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'email' => UsersFixtures::CONFIRMED_USER_EMAIL,
        ]);
    }
}
