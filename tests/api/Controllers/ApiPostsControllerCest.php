<?php

namespace App\Tests;

use App\Domain\Repositories\PostsRepository;
use App\DataFixtures\PostsFixtures;
use App\DataFixtures\UsersFixtures;
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
            'records' => [
                'user' => [
                    'full_name' => UsersFixtures::getConfirmedUserFullName(),
                ],
                'title' => PostsFixtures::LAST_POST_TITLE,
            ],
            'pagination' => [
                'page' => 1,
            ]
        ]);
    }


    public function testCreateOk(ApiTester $I)
    {
        $accessToken = $this->login($I);
        $I->amBearerAuthenticated($accessToken);
        $I->sendPost('/posts', [
            'title' => $title = 'New post',
            'text' => $text = 'New post content here...'
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string',
            'created_at' => 'string:date'
        ]);
        $I->seeResponseContainsJson([
            'title' => $title,
        ]);
    }


    public function testCreateWithInvalidData(ApiTester $I)
    {
        $accessToken = $this->login($I);
        $I->amBearerAuthenticated($accessToken);
        $I->sendPost('/posts', [
            'text' => $text = '...'
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'title' => [
                'This value should not be blank.',
            ],
        ]);
    }


    public function testShowOk(ApiTester $I)
    {
        $postsRepo = $I->grabService(PostsRepository::class);
        $post = $postsRepo->getOneBy(['title' => PostsFixtures::LAST_POST_TITLE]);

        $accessToken = $this->login($I);
        $I->amBearerAuthenticated($accessToken);
        $I->sendGet('/posts/' . $post->getId(), []);
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string',
            'text' => 'string',
            'created_at' => 'string:date'
        ]);
        $I->seeResponseContainsJson([
            'title' => PostsFixtures::LAST_POST_TITLE,
        ]);
    }
}
