<?php

namespace App\Tests;

use App\DataFixtures\PostsFixtures;

class ApiPostsControllerCest
{

    public function testListOk(ApiTester $I)
    {
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
