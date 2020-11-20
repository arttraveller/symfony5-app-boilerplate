<?php

namespace App\Tests\Other;

use App\DataFixtures\UsersFixtures;
use App\Tests\ApiTester;

trait LoginApi
{
    protected function login(ApiTester $I, $email = UsersFixtures::CONFIRMED_USER_EMAIL, $password = UsersFixtures::CONFIRMED_USER_PASSwORD): string
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/signin', [
            'username' => $email,
            'password' => $password
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $accessToken = $I->grabDataFromResponseByJsonPath('$.token')[0];

        return $accessToken;
    }

}
