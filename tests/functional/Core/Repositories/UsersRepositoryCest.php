<?php

namespace App\Tests;

use App\Core\Entities\User\User;
use App\DataFixtures\UsersFixtures;

class UsersRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $I->seeInRepository(User::class, [
            'email' => UsersFixtures::CONFIRMED_USER_EMAIL,
        ]);
    }
}
