<?php

namespace App\Tests;

use App\DataFixtures\PostsFixtures;
use App\Tests\Other\LoginApi;

class ApiAccessControlCest
{
    use LoginApi;

    public function testRequestWithoutAccessToken(ApiTester $I)
    {
        $I->sendGet('/posts');
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
        $I->sendGet('/posts');
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
        $I->sendGet('/posts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'data' => [
                'title' => PostsFixtures::LAST_POST_TITLE,
            ]
        ]);
    }
}
