<?php

namespace App\Tests;

use App\Shared\DataFixtures\UsersFixtures;

class ApiSignInCest
{
    public function testSignInOk(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/signin', [
            'username' => UsersFixtures::CONFIRMED_USER_EMAIL,
            'password' => UsersFixtures::CONFIRMED_USER_PASSWORD
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'token' => 'string',
        ]);
    }

    public function testSignInUnknownUser(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/signin', [
            'username' => 'unknown@example.com',
            'password' => UsersFixtures::CONFIRMED_USER_PASSWORD
        ]);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContainsJson([
            'message' => 'Username could not be found.',
        ]);
    }

    public function testSignInWithIncorrectPassword(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/signin', [
            'username' => UsersFixtures::CONFIRMED_USER_EMAIL,
            'password' => '1234512345'
        ]);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContainsJson([
            'message' => 'Invalid credentials.',
        ]);
    }

    public function testSignInUnconfirmedUser(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/signin', [
            'username' => UsersFixtures::UNCONFIRMED_USER_EMAIL,
            'password' => 'password'
        ]);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContainsJson([
            'message' => 'Your user account is not active.',
        ]);
    }
}
