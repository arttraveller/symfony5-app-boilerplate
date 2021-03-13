<?php

namespace App\Tests;

use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\Other\LoginApi;

class ApiAccessControlCest
{
    use LoginApi;

    public function testRequestWithoutAccessToken(ApiTester $I)
    {
        $I->sendGet('/profile');
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'JWT Token not found',
        ]);
    }

    public function testRequestWithInvalidAccessToken(ApiTester $I)
    {
        $I->amBearerAuthenticated('12345');
        $I->sendGet('/profile');
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 401,
            'message' => 'Invalid JWT Token',
        ]);
    }

    public function testRequestWithCorrectAccessToken(ApiTester $I)
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
