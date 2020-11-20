<?php

namespace App\Tests;

use App\DataFixtures\PostsFixtures;
use App\Tests\Other\LoginApi;

class ApiPostsControllerCest
{
    use LoginApi;


    public function testIndexOk(ApiTester $I)
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
